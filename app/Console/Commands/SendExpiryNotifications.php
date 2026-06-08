<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\EmailNotification;
use App\Models\NotificationReport;
use App\Mail\PassportExpiryNotification;
use App\Mail\VisaExpiryNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendExpiryNotifications extends Command
{
    protected $signature = 'notifications:send-expiry';
    protected $description = 'Gửi email nhắc nhở sinh viên về hộ chiếu/visa sắp hết hạn (< 30 ngày)';

    protected $sentDetails = [];

    public function handle()
    {
        $startTime = now();

        $this->info('🚀 Bắt đầu kiểm tra và gửi thông báo...');

        // Đánh dấu inactive các thông báo của tài liệu đã gia hạn vượt 29 ngày
        $this->markInactiveExtendedDocuments();

        $passportCount = $this->sendPassportNotifications();
        $visaCount = $this->sendVisaNotifications();

        $totalCount = $passportCount + $visaCount;
        $endTime = now();
        $duration = $startTime->diffInSeconds($endTime);

        // Lưu báo cáo vào database
        $this->saveReport($passportCount, $visaCount, $totalCount, $duration, $startTime);

        $this->info("✅ Hoàn thành! Đã gửi {$passportCount} email Passport và {$visaCount} email Visa. (Thời gian: {$duration}s)");

        return Command::SUCCESS;
    }

    protected function saveReport($passportCount, $visaCount, $totalCount, $duration, $runAt)
    {
        NotificationReport::create([
            'passport_count' => $passportCount,
            'visa_count' => $visaCount,
            'total_count' => $totalCount,
            'details' => $this->sentDetails,
            'duration_seconds' => (int) $duration,
            'run_at' => $runAt,
        ]);

        $this->info('💾 Đã lưu báo cáo vào database');
    }

    /**
     * Đánh dấu inactive các thông báo của tài liệu đã được gia hạn >= 30 ngày
     */
    protected function markInactiveExtendedDocuments()
    {
        $this->info('🧹 Kiểm tra tài liệu đã gia hạn...');

        // Kiểm tra Passport
        $passportNotifications = EmailNotification::where('notifiable_type', 'passport')
            ->whereIn('status', ['pending', 'stopped'])
            ->get();

        foreach ($passportNotifications as $notification) {
            $student = Student::with('passport')->find($notification->student_id);

            // Tài liệu bị xóa
            if (!$student || !$student->passport) {
                $notification->update([
                    'status' => 'inactive',
                    'next_send_at' => null,
                    'inactive_reason' => 'Document deleted',
                    'inactive_at' => Carbon::now(),
                ]);
                $this->info("🗑️  Passport của {$notification->student_id} đã bị xóa");
                continue;
            }

            $daysLeft = (int) Carbon::now()->diffInDays(
                Carbon::parse($student->passport->expiry_date)->endOfDay(),
                false
            );

            // Nếu gia hạn >= 30 ngày → Đánh dấu inactive
            if ($daysLeft >= 30) {
                $notification->update([
                    'status' => 'inactive',
                    'next_send_at' => null,
                    'inactive_reason' => 'Extended beyond 30 days',
                    'inactive_at' => Carbon::now(),
                ]);
                $this->info("🔄 Inactive passport: {$student->full_name} (còn {$daysLeft} ngày - >= 30 ngày)");
            }
        }

        // Kiểm tra Visa
        $visaNotifications = EmailNotification::where('notifiable_type', 'visa')
            ->whereIn('status', ['pending', 'stopped'])
            ->get();

        foreach ($visaNotifications as $notification) {
            $student = Student::with('visa')->find($notification->student_id);

            // Tài liệu bị xóa
            if (!$student || !$student->visa) {
                $notification->update([
                    'status' => 'inactive',
                    'next_send_at' => null,
                    'inactive_reason' => 'Document deleted',
                    'inactive_at' => Carbon::now(),
                ]);
                $this->info("🗑️  Visa của {$notification->student_id} đã bị xóa");
                continue;
            }

            $daysLeft = (int) Carbon::now()->diffInDays(
                Carbon::parse($student->visa->expiry_date)->endOfDay(),
                false
            );

            // Nếu gia hạn >= 30 ngày → Đánh dấu inactive
            if ($daysLeft >= 30) {
                $notification->update([
                    'status' => 'inactive',
                    'next_send_at' => null,
                    'inactive_reason' => 'Extended beyond 30 days',
                    'inactive_at' => Carbon::now(),
                ]);
                $this->info("🔄 Inactive visa: {$student->full_name} (còn {$daysLeft} ngày - >= 30 ngày)");
            }
        }
    }

    /**
     * ===================== PASSPORT =====================
     */
    protected function sendPassportNotifications()
    {
        $count = 0;

        // Lấy sinh viên có passport sắp hết hạn (< 30 ngày)
        $students = Student::with(['passport', 'user'])
            ->whereHas('passport', function ($query) {
                $query->where('expiry_date', '<', Carbon::now()->addDays(29)->endOfDay());
            })
            ->get();

        foreach ($students as $student) {
            if (!$student->passport || !$student->user) {
                continue;
            }

            $expiry = Carbon::parse($student->passport->expiry_date)->endOfDay();
            $now = Carbon::now();

            $daysLeft = (int) $now->diffInDays($expiry, false);
            $isExpired = $now->gt($expiry);

            if ($this->shouldSendNotification($student, 'passport', $student->passport->id, $isExpired)) {
                try {
                    Mail::to($student->user->email)
                        ->send(new PassportExpiryNotification($student));

                    $this->logNotification($student, 'passport', $student->passport->id, $isExpired);

                    $count++;

                    if ($isExpired) {
                        // Tính thời gian đã hết hạn chi tiết
                        $timeDetail = $this->calculateTimeDetail($expiry, true);

                        $this->line("📧 [PASSPORT - HẾT HẠN] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
                        $this->sentDetails[] = [
                            'type' => 'passport',
                            'student_name' => $student->full_name,
                            'student_email' => $student->user->email,
                            'status' => 'expired',
                            'days_left' => $daysLeft,
                            'time_detail' => $timeDetail,
                        ];
                    } else {
                        // Tính thời gian còn lại chi tiết
                        $timeDetail = $this->calculateTimeDetail($expiry, false);

                        $this->line("📧 [PASSPORT - SẮP HẾT] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
                        $this->sentDetails[] = [
                            'type' => 'passport',
                            'student_name' => $student->full_name,
                            'student_email' => $student->user->email,
                            'status' => 'expiring',
                            'days_left' => $daysLeft,
                            'time_detail' => $timeDetail,
                        ];
                    }
                } catch (\Exception $e) {
                    $this->error("❌ Lỗi gửi Passport cho {$student->full_name}: {$e->getMessage()}");
                }
            }
        }

        return $count;
    }

    /**
     * ===================== VISA =====================
     */
    protected function sendVisaNotifications()
    {
        $count = 0;

        // Lấy sinh viên có visa sắp hết hạn (< 30 ngày)
        $students = Student::with(['visa', 'user'])
            ->whereHas('visa', function ($query) {
                $query->where('expiry_date', '<', Carbon::now()->addDays(29)->endOfDay());
            })
            ->get();

        foreach ($students as $student) {
            if (!$student->visa || !$student->user) {
                continue;
            }

            $expiry = Carbon::parse($student->visa->expiry_date)->endOfDay();
            $now = Carbon::now();

            $daysLeft = (int) $now->diffInDays($expiry, false);
            $isExpired = $now->gt($expiry);

            if ($this->shouldSendNotification($student, 'visa', $student->visa->id, $isExpired)) {
                try {
                    Mail::to($student->user->email)
                        ->send(new VisaExpiryNotification($student));

                    $this->logNotification($student, 'visa', $student->visa->id, $isExpired);

                    $count++;

                    if ($isExpired) {
                        // Tính thời gian đã hết hạn chi tiết
                        $timeDetail = $this->calculateTimeDetail($expiry, true);

                        $this->line("📧 [VISA - HẾT HẠN] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
                        $this->sentDetails[] = [
                            'type' => 'visa',
                            'student_name' => $student->full_name,
                            'student_email' => $student->user->email,
                            'status' => 'expired',
                            'days_left' => $daysLeft,
                            'time_detail' => $timeDetail,
                        ];
                    } else {
                        // Tính thời gian còn lại chi tiết
                        $timeDetail = $this->calculateTimeDetail($expiry, false);

                        $this->line("📧 [VISA - SẮP HẾT] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
                        $this->sentDetails[] = [
                            'type' => 'visa',
                            'student_name' => $student->full_name,
                            'student_email' => $student->user->email,
                            'status' => 'expiring',
                            'days_left' => $daysLeft,
                            'time_detail' => $timeDetail,
                        ];
                    }
                } catch (\Exception $e) {
                    $this->error("❌ Lỗi gửi Visa cho {$student->full_name}: {$e->getMessage()}");
                }
            }
        }

        return $count;
    }

    /**
     * Tính thời gian chi tiết (ngày, giờ, phút)
     */
    protected function calculateTimeDetail($expiryDate, $isExpired)
    {
        $now = Carbon::now();
        $expiry = Carbon::parse($expiryDate)->endOfDay();

        // Tính tổng số giây
        $totalSeconds = abs($now->diffInSeconds($expiry, false));

        $days = floor($totalSeconds / 86400);
        $hours = floor(($totalSeconds % 86400) / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);

        // Tạo chuỗi hiển thị
        $parts = [];

        if ($days > 0) {
            $parts[] = "{$days} ngày";
        }
        if ($hours > 0) {
            $parts[] = "{$hours} giờ";
        }
        if ($minutes > 0 && $days == 0) { // Chỉ hiển thị phút nếu < 1 ngày
            $parts[] = "{$minutes} phút";
        }

        if (empty($parts)) {
            return $isExpired ? "Vừa mới hết hạn" : "Còn vài giây";
        }

        $timeStr = implode(' ', $parts);

        return $isExpired ? "Đã hết hạn {$timeStr}" : "Còn {$timeStr}";
    }

    protected function shouldSendNotification($student, $type, $notifiableId, $isExpired)
    {
        $document = $type === 'passport' ? $student->passport : $student->visa;
        $expiry   = Carbon::parse($document->expiry_date)->endOfDay();
        $now      = Carbon::now();

        $daysLeft    = (int) $now->diffInDays($expiry, false); // âm = đã hết hạn
        $isExpired   = $daysLeft < 0;
        $isExpiring  = $daysLeft >= 0;

        // Các mốc ngày sẽ gửi (còn X ngày)
        $sendMilestones = [15, 7, 3, 2, 1];

        $lastNotification = EmailNotification::where('student_id', $student->id)
            ->where('notifiable_type', $type)
            ->where('notifiable_id', $notifiableId)
            ->whereIn('status', ['pending', 'stopped'])
            ->orderByDesc('last_sent_at')
            ->first();

        // ========== ĐÃ HẾT HẠN ==========
        if ($isExpired) {
            if (!$lastNotification) {
                $this->info("🆕 {$student->full_name} - {$type}: Hết hạn, chưa gửi → Gửi lần đầu");
                return true;
            }

            // Đã gửi email khi hết hạn rồi → dừng hẳn
            $lastSentAfterExpiry = Carbon::parse($lastNotification->last_sent_at)->gt($expiry);
            if ($lastSentAfterExpiry) {
                if ($lastNotification->status !== 'stopped') {
                    $lastNotification->update(['status' => 'stopped', 'next_send_at' => null]);
                }
                $this->info("⏹️  {$student->full_name} - {$type}: Đã gửi email hết hạn → Dừng hẳn");
                return false;
            }

            // Chưa gửi email hết hạn → gửi 1 lần cuối
            $this->info("📧 {$student->full_name} - {$type}: Vừa hết hạn → Gửi lần cuối");
            return true;
        }

        // ========== CÒN HẠN NHƯNG > 15 NGÀY ==========
        if ($daysLeft > 15) {
            // Kiểm tra gia hạn: nếu đang có record stopped/pending mà ngày hết hạn thay đổi
            if ($lastNotification && $lastNotification->expiry_date_at_send) {
                $oldExpiry = Carbon::parse($lastNotification->expiry_date_at_send);
                $newExpiry = Carbon::parse($document->expiry_date);
                if (!$oldExpiry->equalTo($newExpiry) && $daysLeft > 15) {
                    $lastNotification->update([
                        'status'          => 'inactive',
                        'next_send_at'    => null,
                        'inactive_reason' => "Gia hạn từ {$oldExpiry->format('d/m/Y')} → {$newExpiry->format('d/m/Y')} (còn {$daysLeft} ngày)",
                        'inactive_at'     => Carbon::now(),
                    ]);
                    $this->info("🔄 {$student->full_name} - {$type}: Gia hạn xa, đánh inactive");
                }
            }
            $this->info("✅ {$student->full_name} - {$type}: Còn {$daysLeft} ngày, chưa đến mốc gửi");
            return false;
        }

        // ========== TRONG VÙNG < 15 NGÀY — kiểm tra mốc ==========

        // Kiểm tra gia hạn (ngày hết hạn thay đổi)
        if ($lastNotification && $lastNotification->expiry_date_at_send) {
            $oldExpiry = Carbon::parse($lastNotification->expiry_date_at_send);
            $newExpiry = Carbon::parse($document->expiry_date);
            if (!$oldExpiry->equalTo($newExpiry)) {
                $lastNotification->update([
                    'status'          => 'inactive',
                    'next_send_at'    => null,
                    'inactive_reason' => "Gia hạn từ {$oldExpiry->format('d/m/Y')} → {$newExpiry->format('d/m/Y')}",
                    'inactive_at'     => Carbon::now(),
                ]);
                $this->info("�� {$student->full_name} - {$type}: Gia hạn, reset chu kỳ");
                // Tiếp tục kiểm tra mốc mới
                $lastNotification = null;
            }
        }

        // Tìm mốc phù hợp với số ngày còn lại
        $targetMilestone = null;
        foreach ($sendMilestones as $m) {
            if ($daysLeft <= $m) {
                $targetMilestone = $m;
                break;
            }
        }

        if ($targetMilestone === null) {
            $this->info("✅ {$student->full_name} - {$type}: Còn {$daysLeft} ngày, chưa đến mốc");
            return false;
        }

        // Kiểm tra đã gửi ở mốc này chưa
        if ($lastNotification && $lastNotification->last_sent_at) {
            $lastDaysLeft = $lastNotification->send_count > 0
                ? (int) Carbon::parse($lastNotification->last_sent_at)->diffInDays($expiry, false)
                : null;

            // Tìm mốc của lần gửi cuối
            $lastMilestone = null;
            if ($lastDaysLeft !== null) {
                foreach ($sendMilestones as $m) {
                    if ($lastDaysLeft <= $m) {
                        $lastMilestone = $m;
                        break;
                    }
                }
            }

            if ($lastMilestone === $targetMilestone) {
                $this->info("⏭️  {$student->full_name} - {$type}: Đã gửi mốc {$targetMilestone} ngày, bỏ qua");
                return false;
            }
        }

        $this->info("📬 {$student->full_name} - {$type}: Còn {$daysLeft} ngày → Gửi mốc {$targetMilestone} ngày");
        return true;
    }

    protected function logNotification($student, $type, $notifiableId, $isExpired)
    {
        $notification = EmailNotification::where('student_id', $student->id)
            ->where('notifiable_type', $type)
            ->where('notifiable_id', $notifiableId)
            ->whereIn('status', ['pending', 'stopped'])
            ->first();

        $now = Carbon::now();
        $document = $type === 'passport' ? $student->passport : $student->visa;

        if ($notification) {
            $updateData = [
                'last_sent_at' => $now,
                'send_count' => $notification->send_count + 1,
                'expiry_date_at_send' => $document->expiry_date,
            ];

            if ($isExpired) {
                $updateData['status'] = 'stopped';
                $updateData['next_send_at'] = null;
            } else {
                $updateData['status'] = 'pending';
                $updateData['next_send_at'] = Carbon::tomorrow()->setTime(7, 45, 0);
            }

            $notification->update($updateData);
        } else {
            EmailNotification::create([
                'student_id' => $student->id,
                'notifiable_type' => $type,
                'notifiable_id' => $notifiableId,
                'expiry_date_at_send' => $document->expiry_date,
                'last_sent_at' => $now,
                'next_send_at' => $isExpired ? null : Carbon::tomorrow()->setTime(7, 45, 0),
                'send_count' => 1,
                'status' => $isExpired ? 'stopped' : 'pending',
            ]);
        }
    }
}






// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Student;
// use App\Models\EmailNotification;
// use App\Models\NotificationReport;
// use App\Mail\PassportExpiryNotification;
// use App\Mail\VisaExpiryNotification;
// use Illuminate\Support\Facades\Mail;
// use Carbon\Carbon;

// class SendExpiryNotifications extends Command
// {
//     protected $signature = 'notifications:send-expiry';
//     protected $description = 'Gửi email nhắc nhở sinh viên về hộ chiếu/visa sắp hết hạn (< 30 ngày)';

//     protected $sentDetails = [];

//     public function handle()
//     {
//         $startTime = now();

//         $this->info('🚀 Bắt đầu kiểm tra và gửi thông báo...');

//         // Đánh dấu inactive các thông báo của tài liệu đã gia hạn vượt 29 ngày
//         $this->markInactiveExtendedDocuments();

//         $passportCount = $this->sendPassportNotifications();
//         $visaCount = $this->sendVisaNotifications();

//         $totalCount = $passportCount + $visaCount;
//         $endTime = now();
//         $duration = $startTime->diffInSeconds($endTime);

//         // Lưu báo cáo vào database
//         $this->saveReport($passportCount, $visaCount, $totalCount, $duration, $startTime);

//         $this->info("✅ Hoàn thành! Đã gửi {$passportCount} email Passport và {$visaCount} email Visa. (Thời gian: {$duration}s)");

//         return Command::SUCCESS;
//     }

//     protected function saveReport($passportCount, $visaCount, $totalCount, $duration, $runAt)
//     {
//         NotificationReport::create([
//             'passport_count' => $passportCount,
//             'visa_count' => $visaCount,
//             'total_count' => $totalCount,
//             'details' => $this->sentDetails,
//             'duration_seconds' => (int) $duration,
//             'run_at' => $runAt,
//         ]);

//         $this->info('💾 Đã lưu báo cáo vào database');
//     }

//     /**
//      * Đánh dấu inactive các thông báo của tài liệu đã được gia hạn >= 30 ngày
//      */
//     protected function markInactiveExtendedDocuments()
//     {
//         $this->info('🧹 Kiểm tra tài liệu đã gia hạn...');

//         // Kiểm tra Passport
//         $passportNotifications = EmailNotification::where('notifiable_type', 'passport')
//             ->whereIn('status', ['pending', 'stopped'])
//             ->get();

//         foreach ($passportNotifications as $notification) {
//             $student = Student::with('passport')->find($notification->student_id);

//             // Tài liệu bị xóa
//             if (!$student || !$student->passport) {
//                 $notification->update([
//                     'status' => 'inactive',
//                     'next_send_at' => null,
//                     'inactive_reason' => 'Document deleted',
//                     'inactive_at' => Carbon::now(),
//                 ]);
//                 $this->info("🗑️  Passport của {$notification->student_id} đã bị xóa");
//                 continue;
//             }

//             $daysLeft = (int) Carbon::now()->diffInDays(
//                 Carbon::parse($student->passport->expiry_date)->endOfDay(),
//                 false
//             );

//             // Nếu gia hạn >= 30 ngày → Đánh dấu inactive
//             if ($daysLeft >= 30) {
//                 $notification->update([
//                     'status' => 'inactive',
//                     'next_send_at' => null,
//                     'inactive_reason' => 'Extended beyond 30 days',
//                     'inactive_at' => Carbon::now(),
//                 ]);
//                 $this->info("🔄 Inactive passport: {$student->full_name} (còn {$daysLeft} ngày - >= 30 ngày)");
//             }
//         }

//         // Kiểm tra Visa
//         $visaNotifications = EmailNotification::where('notifiable_type', 'visa')
//             ->whereIn('status', ['pending', 'stopped'])
//             ->get();

//         foreach ($visaNotifications as $notification) {
//             $student = Student::with('visa')->find($notification->student_id);

//             // Tài liệu bị xóa
//             if (!$student || !$student->visa) {
//                 $notification->update([
//                     'status' => 'inactive',
//                     'next_send_at' => null,
//                     'inactive_reason' => 'Document deleted',
//                     'inactive_at' => Carbon::now(),
//                 ]);
//                 $this->info("🗑️  Visa của {$notification->student_id} đã bị xóa");
//                 continue;
//             }

//             $daysLeft = (int) Carbon::now()->diffInDays(
//                 Carbon::parse($student->visa->expiry_date)->endOfDay(),
//                 false
//             );

//             // Nếu gia hạn >= 30 ngày → Đánh dấu inactive
//             if ($daysLeft >= 30) {
//                 $notification->update([
//                     'status' => 'inactive',
//                     'next_send_at' => null,
//                     'inactive_reason' => 'Extended beyond 30 days',
//                     'inactive_at' => Carbon::now(),
//                 ]);
//                 $this->info("🔄 Inactive visa: {$student->full_name} (còn {$daysLeft} ngày - >= 30 ngày)");
//             }
//         }
//     }

//     /**
//      * ===================== PASSPORT =====================
//      */
//     protected function sendPassportNotifications()
//     {
//         $count = 0;

//         // Lấy sinh viên có passport sắp hết hạn (< 30 ngày)
//         $students = Student::with(['passport', 'user'])
//             ->whereHas('passport', function ($query) {
//                 $query->where('expiry_date', '<', Carbon::now()->addDays(29)->endOfDay());
//             })
//             ->get();

//         foreach ($students as $student) {
//             if (!$student->passport || !$student->user) {
//                 continue;
//             }

//             $expiry = Carbon::parse($student->passport->expiry_date)->endOfDay();
//             $now = Carbon::now();

//             $daysLeft = (int) $now->diffInDays($expiry, false);
//             $isExpired = $now->gt($expiry);

//             if ($this->shouldSendNotification($student, 'passport', $student->passport->id, $isExpired)) {
//                 try {
//                     Mail::to($student->user->email)
//                         ->send(new PassportExpiryNotification($student));

//                     $this->logNotification($student, 'passport', $student->passport->id, $isExpired);

//                     $count++;

//                     if ($isExpired) {
//                         // Tính thời gian đã hết hạn chi tiết
//                         $timeDetail = $this->calculateTimeDetail($expiry, true);

//                         $this->line("📧 [PASSPORT - HẾT HẠN] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
//                         $this->sentDetails[] = [
//                             'type' => 'passport',
//                             'student_name' => $student->full_name,
//                             'student_email' => $student->user->email,
//                             'status' => 'expired',
//                             'days_left' => $daysLeft,
//                             'time_detail' => $timeDetail,
//                         ];
//                     } else {
//                         // Tính thời gian còn lại chi tiết
//                         $timeDetail = $this->calculateTimeDetail($expiry, false);

//                         $this->line("📧 [PASSPORT - SẮP HẾT] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
//                         $this->sentDetails[] = [
//                             'type' => 'passport',
//                             'student_name' => $student->full_name,
//                             'student_email' => $student->user->email,
//                             'status' => 'expiring',
//                             'days_left' => $daysLeft,
//                             'time_detail' => $timeDetail,
//                         ];
//                     }
//                 } catch (\Exception $e) {
//                     $this->error("❌ Lỗi gửi Passport cho {$student->full_name}: {$e->getMessage()}");
//                 }
//             }
//         }

//         return $count;
//     }

//     /**
//      * ===================== VISA =====================
//      */
//     protected function sendVisaNotifications()
//     {
//         $count = 0;

//         // Lấy sinh viên có visa sắp hết hạn (< 30 ngày)
//         $students = Student::with(['visa', 'user'])
//             ->whereHas('visa', function ($query) {
//                 $query->where('expiry_date', '<', Carbon::now()->addDays(29)->endOfDay());
//             })
//             ->get();

//         foreach ($students as $student) {
//             if (!$student->visa || !$student->user) {
//                 continue;
//             }

//             $expiry = Carbon::parse($student->visa->expiry_date)->endOfDay();
//             $now = Carbon::now();

//             $daysLeft = (int) $now->diffInDays($expiry, false);
//             $isExpired = $now->gt($expiry);

//             if ($this->shouldSendNotification($student, 'visa', $student->visa->id, $isExpired)) {
//                 try {
//                     Mail::to($student->user->email)
//                         ->send(new VisaExpiryNotification($student));

//                     $this->logNotification($student, 'visa', $student->visa->id, $isExpired);

//                     $count++;

//                     if ($isExpired) {
//                         // Tính thời gian đã hết hạn chi tiết
//                         $timeDetail = $this->calculateTimeDetail($expiry, true);

//                         $this->line("📧 [VISA - HẾT HẠN] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
//                         $this->sentDetails[] = [
//                             'type' => 'visa',
//                             'student_name' => $student->full_name,
//                             'student_email' => $student->user->email,
//                             'status' => 'expired',
//                             'days_left' => $daysLeft,
//                             'time_detail' => $timeDetail,
//                         ];
//                     } else {
//                         // Tính thời gian còn lại chi tiết
//                         $timeDetail = $this->calculateTimeDetail($expiry, false);

//                         $this->line("📧 [VISA - SẮP HẾT] {$student->full_name} ({$student->user->email}) - {$timeDetail}");
//                         $this->sentDetails[] = [
//                             'type' => 'visa',
//                             'student_name' => $student->full_name,
//                             'student_email' => $student->user->email,
//                             'status' => 'expiring',
//                             'days_left' => $daysLeft,
//                             'time_detail' => $timeDetail,
//                         ];
//                     }
//                 } catch (\Exception $e) {
//                     $this->error("❌ Lỗi gửi Visa cho {$student->full_name}: {$e->getMessage()}");
//                 }
//             }
//         }

//         return $count;
//     }

//     /**
//      * Tính thời gian chi tiết (ngày, giờ, phút)
//      */
//     protected function calculateTimeDetail($expiryDate, $isExpired)
//     {
//         $now = Carbon::now();
//         $expiry = Carbon::parse($expiryDate)->endOfDay();

//         // Tính tổng số giây
//         $totalSeconds = abs($now->diffInSeconds($expiry, false));

//         $days = floor($totalSeconds / 86400);
//         $hours = floor(($totalSeconds % 86400) / 3600);
//         $minutes = floor(($totalSeconds % 3600) / 60);

//         // Tạo chuỗi hiển thị
//         $parts = [];

//         if ($days > 0) {
//             $parts[] = "{$days} ngày";
//         }
//         if ($hours > 0) {
//             $parts[] = "{$hours} giờ";
//         }
//         if ($minutes > 0 && $days == 0) { // Chỉ hiển thị phút nếu < 1 ngày
//             $parts[] = "{$minutes} phút";
//         }

//         if (empty($parts)) {
//             return $isExpired ? "Vừa mới hết hạn" : "Còn vài giây";
//         }

//         $timeStr = implode(' ', $parts);

//         return $isExpired ? "Đã hết hạn {$timeStr}" : "Còn {$timeStr}";
//     }

//     protected function shouldSendNotification($student, $type, $notifiableId, $isExpired)
//     {
//         $lastNotification = EmailNotification::where('student_id', $student->id)
//             ->where('notifiable_type', $type)
//             ->where('notifiable_id', $notifiableId)
//             ->whereIn('status', ['pending', 'stopped'])
//             ->first();

//         $document = $type === 'passport' ? $student->passport : $student->visa;
//         $expiry = Carbon::parse($document->expiry_date)->endOfDay();
//         $now = Carbon::now();

//         // dùng giây thay vì ngày
//         $secondsLeft = $now->diffInSeconds($expiry, false);

//         $isExpired   = $secondsLeft <= 0;
//         $isExpiring  = $secondsLeft > 0 && $secondsLeft <= 30 * 86400;
//         $isValid     = $secondsLeft > 30 * 86400;

//         // ========== XỬ LÝ TRẠNG THÁI 'STOPPED' BỊ BẬT LẠI ==========
//         if ($lastNotification && $lastNotification->status === 'stopped') {
//             // ✅ Kiểm tra xem có phải do gia hạn không
//             if ($lastNotification->expiry_date_at_send) {
//                 $oldExpiry = Carbon::parse($lastNotification->expiry_date_at_send);
//                 $newExpiry = Carbon::parse($document->expiry_date);

//                 if (!$oldExpiry->equalTo($newExpiry)) {
//                     // ✅ CÓ GIA HẠN

//                     if ($isValid) {
//                         // ✅ GIA HẠN LÊN CÒN HẠN XA (> 30 ngày)
//                         $lastNotification->update([
//                             'status' => 'inactive',
//                             'next_send_at' => null,
//                             'inactive_reason' => "Renewed from {$oldExpiry->format('d/m/Y')} (expired) to {$newExpiry->format('d/m/Y')} (valid > 30 days)",
//                             'inactive_at' => Carbon::now(),
//                         ]);

//                         $this->info("🔄 {$student->full_name} - {$type}: Gia hạn từ HẾT HẠN → CÒN HẠN XA ({$newExpiry->format('d/m/Y')}, {$secondsLeft} giây)");

//                         // ✅ Không gửi email, chờ đến khi < 30 ngày
//                         return false;

//                     } elseif ($isExpiring) {
//                         // ✅ GIA HẠN LÊN SẮP HẾT HẠN (< 30 ngày)
//                         $lastNotification->update([
//                             'status' => 'inactive',
//                             'next_send_at' => null,
//                             'inactive_reason' => "Renewed from {$oldExpiry->format('d/m/Y')} (expired) to {$newExpiry->format('d/m/Y')} (expiring)",
//                             'inactive_at' => Carbon::now(),
//                         ]);

//                         $this->info("🔄 {$student->full_name} - {$type}: Gia hạn từ HẾT HẠN → SẮP HẾT ({$newExpiry->format('d/m/Y')})");

//                         // TEST MODE: Bỏ check "đã gửi hôm nay" để test được
//                         // $sentToday = EmailNotification::where('student_id', $student->id)
//                         //     ->where('notifiable_type', $type)
//                         //     ->whereDate('last_sent_at', Carbon::today())
//                         //     ->where('id', '!=', $lastNotification->id)
//                         //     ->exists();

//                         // if ($sentToday) {
//                         //     $this->info("⏭️  {$student->full_name} - {$type}: Đã gửi hôm nay, gia hạn sẽ gửi vào ngày mai");
//                         //     EmailNotification::create([
//                         //         'student_id' => $student->id,
//                         //         'notifiable_type' => $type,
//                         //         'notifiable_id' => $notifiableId,
//                         //         'expiry_date_at_send' => $document->expiry_date,
//                         //         'last_sent_at' => null,
//                         //         'next_send_at' => Carbon::tomorrow()->setTime(7, 45, 0),
//                         //         'send_count' => 0,
//                         //         'status' => 'pending',
//                         //     ]);
//                         //     return false;
//                         // }

//                         // ✅ Cho phép gửi ngay
//                         return true;

//                     } else {
//                         // ✅ GIA HẠN NHƯNG VẪN HẾT HẠN (gia hạn ngày quá khứ - edge case)
//                         $lastNotification->update([
//                             'status' => 'inactive',
//                             'next_send_at' => null,
//                             'inactive_reason' => "Renewed from {$oldExpiry->format('d/m/Y')} to {$newExpiry->format('d/m/Y')} (still expired)",
//                             'inactive_at' => Carbon::now(),
//                         ]);

//                         $this->info("🔄 {$student->full_name} - {$type}: Gia hạn nhưng vẫn hết hạn ({$newExpiry->format('d/m/Y')})");

//                         // ✅ Cho phép gửi email "hết hạn" cho ngày mới
//                         return true;
//                     }
//                 }
//             }

//             // ✅ Không có gia hạn - chỉ kiểm tra trạng thái hiện tại
//             if ($isExpiring) {
//                 $lastNotification->update([
//                     'status' => 'pending',
//                     'next_send_at' => Carbon::now(), // TEST: gửi ngay
//                 ]);
//                 $this->info("🔄 {$student->full_name} - {$type}: Bật lại do còn hạn ({$secondsLeft} giây)");
//                 return true;
//             }

//             return false;
//         }

//         // ========== XỬ LÝ GIA HẠN (TỪ PENDING/EXPIRING) ==========
//         if ($lastNotification && $lastNotification->last_sent_at && $document->updated_at->gt($lastNotification->last_sent_at)) {
//             if ($lastNotification->expiry_date_at_send) {
//                 $oldExpiry = Carbon::parse($lastNotification->expiry_date_at_send);
//                 $newExpiry = Carbon::parse($document->expiry_date);

//                 if (!$oldExpiry->equalTo($newExpiry)) {
//                     $lastNotification->update([
//                         'status' => 'inactive',
//                         'next_send_at' => null,
//                         'inactive_reason' => "Renewed from {$oldExpiry->format('d/m/Y')} to {$newExpiry->format('d/m/Y')}",
//                         'inactive_at' => Carbon::now(),
//                     ]);

//                     $this->info("🔄 {$student->full_name} gia hạn {$type} ({$oldExpiry->format('d/m/Y')} → {$newExpiry->format('d/m/Y')})");

//                     // ✅ Nếu gia hạn lên > 30 ngày → Không gửi email
//                     if ($isValid) {
//                         $this->info("✅ {$student->full_name} - {$type}: Gia hạn lên còn hạn xa (> 30 ngày), không gửi email");
//                         return false;
//                     }

//                     // ✅ Nếu vẫn sắp hết hoặc hết hạn → Gửi email
//                     // TEST MODE: Bỏ check "đã gửi hôm nay"
//                     // $sentToday = EmailNotification::where('student_id', $student->id)
//                     //     ->where('notifiable_type', $type)
//                     //     ->whereDate('last_sent_at', Carbon::today())
//                     //     ->where('id', '!=', $lastNotification->id)
//                     //     ->exists();

//                     // if ($sentToday) {
//                     //     $this->info("⏭️  {$student->full_name} - {$type}: Đã gửi hôm nay, gia hạn sẽ gửi vào ngày mai");
//                     //     EmailNotification::create([
//                     //         'student_id' => $student->id,
//                     //         'notifiable_type' => $type,
//                     //         'notifiable_id' => $notifiableId,
//                     //         'expiry_date_at_send' => $document->expiry_date,
//                     //         'last_sent_at' => null,
//                     //         'next_send_at' => Carbon::tomorrow()->setTime(7, 45, 0),
//                     //         'send_count' => 0,
//                     //         'status' => 'pending',
//                     //     ]);
//                     //     return false;
//                     // }

//                     return true;
//                 } else {
//                     $this->info("ℹ️  {$student->full_name} cập nhật {$type} (không đổi ngày hết hạn)");
//                 }
//             } else {
//                 $lastNotification->update([
//                     'status' => 'inactive',
//                     'next_send_at' => null,
//                     'inactive_reason' => 'Document updated - legacy record',
//                     'inactive_at' => Carbon::now(),
//                 ]);
//                 $this->info("🔄 {$student->full_name} cập nhật {$type} - Bắt đầu chu kỳ mới");
//                 return true;
//             }
//         }

//         // ========== KIỂM TRA ĐÃ GỬI HÔM NAY CHƯA ==========
//         // TEST MODE: Bỏ check này để có thể gửi lại nhiều lần
//         // if ($lastNotification && $lastNotification->last_sent_at) {
//         //     $lastSentDate = Carbon::parse($lastNotification->last_sent_at)->startOfDay();
//         //     $today = Carbon::now()->startOfDay();

//         //     if ($lastSentDate->equalTo($today)) {
//         //         $this->info("⏭️  {$student->full_name} - {$type}: Đã gửi hôm nay, bỏ qua");
//         //         return false;
//         //     }
//         // }

//         // ========== XỬ LÝ TÀI LIỆU ĐÃ HẾT HẠN ==========
//         if ($isExpired) {
//             if (!$lastNotification) {
//                 $this->info("🆕 {$student->full_name} - {$type}: Hết hạn, chưa gửi email → Gửi lần đầu");
//                 return true;
//             }

//             if ($lastNotification->send_count > 0) {
//                 // ✅ Kiểm tra xem đã gửi email "hết hạn" chưa
//                 $lastExpiryDate = $lastNotification->expiry_date_at_send
//                     ? Carbon::parse($lastNotification->expiry_date_at_send)->endOfDay()
//                     : null;

//                 // So sánh thời điểm gửi email cuối với ngày hết hạn
//                 $lastSentWhenExpired = $lastExpiryDate && Carbon::parse($lastNotification->last_sent_at)->gt($lastExpiryDate);

//                 if ($lastSentWhenExpired) {
//                     // Đã gửi email khi tài liệu đã hết hạn rồi → Dừng hẳn
//                     if ($lastNotification->status !== 'stopped' || $lastNotification->next_send_at !== null) {
//                         $lastNotification->update([
//                             'status' => 'stopped',
//                             'next_send_at' => null
//                         ]);
//                         $this->info("⏹️  {$student->full_name} - {$type}: Đã gửi email hết hạn → Dừng hẳn");
//                     }
//                     return false;
//                 } else {
//                     // Chưa gửi email "hết hạn" → Cho phép gửi lần cuối
//                     $this->info("📧 {$student->full_name} - {$type}: Vừa hết hạn → Gửi email lần cuối");
//                     return true;
//                 }
//             }

//             return true;
//         }

//         // ========== XỬ LÝ TÀI LIỆU SẮP HẾT HẠN ==========
//         if (!$lastNotification) {
//             $this->info("🆕 {$student->full_name} - {$type}: Sắp hết hạn, chưa có record → Gửi lần đầu");
//             return true;
//         }

//         if ($lastNotification->next_send_at) {
//             $nextSendAt = Carbon::parse($lastNotification->next_send_at);
//             $canSend = Carbon::now()->gte($nextSendAt);

//             if (!$canSend) {
//                 $this->info("⏳ {$student->full_name} - {$type}: Chưa đến giờ gửi (next: {$nextSendAt->format('d/m/Y H:i')})");
//             }

//             return $canSend;
//         }

//         return false;
//     }

//     protected function logNotification($student, $type, $notifiableId, $isExpired)
//     {
//         $notification = EmailNotification::where('student_id', $student->id)
//             ->where('notifiable_type', $type)
//             ->where('notifiable_id', $notifiableId)
//             ->whereIn('status', ['pending', 'stopped'])
//             ->first();

//         $now = Carbon::now();
//         $document = $type === 'passport' ? $student->passport : $student->visa;

//         if ($notification) {
//             $updateData = [
//                 'last_sent_at' => $now,
//                 'send_count' => $notification->send_count + 1,
//                 'expiry_date_at_send' => $document->expiry_date,
//             ];

//             if ($isExpired) {
//                 $updateData['status'] = 'stopped';
//                 $updateData['next_send_at'] = null;
//             } else {
//                 $updateData['status'] = 'pending';
//                 $updateData['next_send_at'] = Carbon::now(); // TEST: gửi ngay thay vì ngày mai
//             }

//             $notification->update($updateData);
//         } else {
//             EmailNotification::create([
//                 'student_id' => $student->id,
//                 'notifiable_type' => $type,
//                 'notifiable_id' => $notifiableId,
//                 'expiry_date_at_send' => $document->expiry_date,
//                 'last_sent_at' => $now,
//                 'next_send_at' => $isExpired ? null : Carbon::now(), // TEST: gửi ngay
//                 'send_count' => 1,
//                 'status' => $isExpired ? 'stopped' : 'pending',
//             ]);
//         }
//     }
// }
