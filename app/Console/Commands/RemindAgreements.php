<?php

namespace App\Console\Commands;

use App\Models\Agreement;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RemindAgreements extends Command
{
    protected $signature = 'agreements:remind';

    protected $description = 'Gửi email tổng hợp các thỏa thuận (MOU) sắp hết hạn cho phòng chuyên trách và quản trị viên';

    public function handle(): int
    {
        $list = Agreement::with('partner')->expiringSoon()->orderBy('expires_at')->get();
        if ($list->isEmpty()) {
            $this->info('Không có thỏa thuận nào sắp hết hạn.');

            return self::SUCCESS;
        }

        $to = User::whereIn('role', ['htqt', 'admin'])->pluck('email')->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))->unique()->values()->all();
        if (!$to) {
            return self::SUCCESS;
        }

        $rows = $list->map(fn (Agreement $a) => '<tr><td style="padding:6px 10px;border-bottom:1px solid #e2e8f0;">' . e($a->partner?->name) . '</td>'
            . '<td style="padding:6px 10px;border-bottom:1px solid #e2e8f0;">' . e($a->title) . '</td>'
            . '<td style="padding:6px 10px;border-bottom:1px solid #e2e8f0;white-space:nowrap;">' . $a->expires_at->format('d/m/Y') . ($a->expires_at->isPast() ? ' (đã hết hạn)' : '') . '</td></tr>')->implode('');

        $html = '<div style="font-family:Arial,sans-serif;font-size:14px;color:#334155;"><p>Các thỏa thuận hợp tác sau sắp hết hạn trong ' . Agreement::WARN_DAYS . ' ngày tới, đề nghị xem xét gia hạn hoặc ký mới:</p>'
            . '<table style="border-collapse:collapse;"><tr style="background:#f1f5f9;"><th align="left" style="padding:6px 10px;">Đối tác</th><th align="left" style="padding:6px 10px;">Thỏa thuận</th><th align="left" style="padding:6px 10px;">Hết hạn</th></tr>' . $rows . '</table>'
            . '<p><a href="' . route('admin.agreements.index', ['state' => 'expiring']) . '">Xem trên hệ thống</a></p></div>';

        try {
            Mail::html($html, fn ($m) => $m->to($to)->subject('[Nhắc] ' . $list->count() . ' thỏa thuận hợp tác sắp hết hạn'));
            $this->info('Đã gửi tổng hợp tới ' . count($to) . ' người.');
        } catch (\Throwable $e) {
            report($e);
            $this->error('Gửi mail lỗi: ' . $e->getMessage());
        }

        return self::SUCCESS;
    }
}
