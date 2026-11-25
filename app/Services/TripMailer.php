<?php

namespace App\Services;

use App\Mail\TripEventMail;
use App\Models\MailLog;
use App\Models\MailRule;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Gửi email theo quy tắc (bảng mail_rules). Lỗi gửi mail không bao giờ làm hỏng nghiệp vụ: chỉ ghi vào mail_logs.
 */
class TripMailer
{
    public function fire(string $event, Trip $trip, ?string $note = null): void
    {
        try {
            $trip->loadMissing(['unit', 'creator', 'members']);
            $rules = MailRule::where('event', $event)->where('enabled', true)
                ->where(fn ($q) => $q->whereNull('direction')->orWhere('direction', $trip->direction))
                ->get();

            foreach ($rules as $rule) {
                $this->send($rule, $trip, $note);
            }
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function send(MailRule $rule, Trip $trip, ?string $note): void
    {
        $to = $this->recipients($rule, $trip);
        [$subject, $headline] = $this->texts($rule->event, $trip);

        if (empty($to)) {
            return;
        }

        try {
            $files = [];
            $docs = new TripDocuments();
            $available = TripDocuments::available($trip);
            foreach ((array) $rule->attachments as $key) {
                if (!isset($available[$key])) {
                    continue;
                }
                try {
                    $files[] = $docs->build($trip, $key);
                } catch (Throwable $e) {
                    report($e); // lỗi sinh biểu mẫu không được chặn email
                }
            }

            $latest = in_array($rule->event, ['returned', 'rejected'], true)
                ? $trip->approvals()->reorder()->whereNotNull('comment')->latest('id')->value('comment')
                : null;
            Mail::to($to)->send(new TripEventMail($trip, $subject, $headline, $note ?? $latest, $files));
            $status = 'sent';
            $error = null;
        } catch (Throwable $e) {
            report($e);
            $status = 'failed';
            $error = mb_substr($e->getMessage(), 0, 500);
        }

        MailLog::create([
            'trip_id' => $trip->id, 'mail_rule_id' => $rule->id, 'event' => $rule->event,
            'recipients' => implode(', ', $to), 'subject' => $subject, 'status' => $status, 'error' => $error,
        ]);
    }

    /** @return string[] danh sách email duy nhất */
    private function recipients(MailRule $rule, Trip $trip): array
    {
        $emails = [];

        if ($rule->to_creator && $trip->creator?->email) {
            $emails[] = $trip->creator->email;
        }
        if ($rule->to_members) {
            foreach ($trip->members as $m) {
                if ($m->email) {
                    $emails[] = $m->email;
                }
            }
            $userIds = $trip->members->pluck('user_id')->filter();
            if ($userIds->isNotEmpty()) {
                $emails = array_merge($emails, User::whereIn('id', $userIds)->pluck('email')->all());
            }
        }
        if ($rule->to_next_approver && ($step = $trip->pendingStep())) {
            $role = config('trips.step_roles')[$step] ?? null;
            if ($role) {
                $q = User::where('role', $role);
                $users = $q->get();
                if ($step === 'unit') {
                    $users = $users->filter(fn (User $u) => $u->unit_id && in_array($trip->unit_id, $u->unitSubtreeIds(), true));
                }
                $emails = array_merge($emails, $users->pluck('email')->all());
            }
        }
        if ($rule->to_unit_manager && $trip->unit_id) {
            $users = User::where('role', 'unit_manager')->get()
                ->filter(fn (User $u) => $u->unit_id && in_array($trip->unit_id, $u->unitSubtreeIds(), true));
            $emails = array_merge($emails, $users->pluck('email')->all());
        }
        if (!empty($rule->to_roles)) {
            $emails = array_merge($emails, User::whereIn('role', $rule->to_roles)->pluck('email')->all());
        }
        if ($rule->extra_emails) {
            $emails = array_merge($emails, array_map('trim', preg_split('/[,;\s]+/', $rule->extra_emails)));
        }

        return array_values(array_unique(array_filter($emails, fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))));
    }

    /** @return array{0:string,1:string} [tiêu đề email, câu mở đầu] */
    private function texts(string $event, Trip $trip): array
    {
        $tag = $trip->direction_label . ' ' . $trip->code;
        $what = $trip->isIncoming() ? 'tiếp đoàn' : 'chuyến công tác';

        return match ($event) {
            'step_pending'     => ["[Chờ duyệt] {$tag}: {$trip->title}", "Có hồ sơ {$what} đang chờ bạn xử lý."],
            'approved'         => ["[Đã duyệt] {$tag}: {$trip->title}", "Hồ sơ {$what} đã được phê duyệt." . ($trip->isIncoming() ? ' Đề nghị các đơn vị liên quan phối hợp đón tiếp.' : '')],
            'returned'         => ["[Trả lại] {$tag}: {$trip->title}", "Hồ sơ {$what} bị trả lại, cần bổ sung."],
            'rejected'         => ["[Không duyệt] {$tag}: {$trip->title}", "Hồ sơ {$what} không được duyệt."],
            'report_submitted' => ["[Báo cáo] {$tag}: {$trip->title}", "Đã nộp báo cáo kết quả {$what}."],
            'report_due'       => ["[Nhắc báo cáo] {$tag}: {$trip->title}", 'Sắp đến hạn nộp báo cáo' . ($trip->report_due_at ? ' (' . $trip->report_due_at->format('d/m/Y') . ')' : '') . '.'],
            default            => ["{$tag}: {$trip->title}", 'Thông báo từ hệ thống.'],
        };
    }
}
