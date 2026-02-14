<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;
use Carbon\Carbon;

class PassportExpiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $daysRemaining;
    public $hoursRemaining;
    public $minutesRemaining;
    public $secondsRemaining;
    public $expiryDate;
    public $isExpired;

    public function __construct(Student $student)
    {
        $this->student = $student;

        if ($student->passport && $student->passport->expiry_date) {
            // Parse ngày hết hạn và set endOfDay (23:59:59)
            $expiryDate = Carbon::parse($student->passport->expiry_date)->endOfDay();
            $now = Carbon::now();

            // Tính tổng số giây còn lại
            $totalSeconds = $now->diffInSeconds($expiryDate, false);
            $this->isExpired = $totalSeconds < 0;

            // Nếu đã hết hạn, lấy giá trị tuyệt đối
            $totalSeconds = abs($totalSeconds);

            // Tính ngày, giờ, phút, giây
            $this->daysRemaining = floor($totalSeconds / (60 * 60 * 24));
            $this->hoursRemaining = floor(($totalSeconds % (60 * 60 * 24)) / (60 * 60));
            $this->minutesRemaining = floor(($totalSeconds % (60 * 60)) / 60);
            $this->secondsRemaining = $totalSeconds % 60;

            $this->expiryDate = Carbon::parse($student->passport->expiry_date)->format('d/m/Y');
        } else {
            $this->daysRemaining = 0;
            $this->hoursRemaining = 0;
            $this->minutesRemaining = 0;
            $this->secondsRemaining = 0;
            $this->expiryDate = null;
            $this->isExpired = false;
        }
    }

    public function envelope(): Envelope
    {
        $subject = $this->isExpired
            ? '⚠️ HỘ CHIẾU ĐÃ HẾT HẠN - Cần cập nhật ngay!'
            : '⏰ NHẮC NHỞ: Hộ chiếu sắp hết hạn';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.passport-expiry',
            with: [
                'student' => $this->student,
                'daysRemaining' => $this->daysRemaining,
                'hoursRemaining' => $this->hoursRemaining,
                'minutesRemaining' => $this->minutesRemaining,
                'secondsRemaining' => $this->secondsRemaining,
                'expiryDate' => $this->expiryDate,
                'isExpired' => $this->isExpired,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
