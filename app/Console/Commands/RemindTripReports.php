<?php

namespace App\Console\Commands;

use App\Models\MailLog;
use App\Models\Trip;
use App\Services\TripMailer;
use Illuminate\Console\Command;

class RemindTripReports extends Command
{
    protected $signature = 'trips:remind {--days=3 : Nhắc trước hạn bao nhiêu ngày}';

    protected $description = 'Gửi email nhắc nộp báo cáo cho hồ sơ đã duyệt sắp hoặc đã quá hạn';

    public function handle(TripMailer $mailer): int
    {
        $limit = now()->addDays((int) $this->option('days'))->toDateString();
        $count = 0;

        Trip::where('status', 'approved')->whereNotNull('report_due_at')->whereDate('report_due_at', '<=', $limit)
            ->whereDate('return_at', '<', now())->get()
            ->each(function (Trip $t) use ($mailer, &$count) {
                // Mỗi hồ sơ chỉ nhắc tối đa 1 lần/ngày
                if (MailLog::where('trip_id', $t->id)->where('event', 'report_due')->whereDate('created_at', today())->exists()) {
                    return;
                }
                $mailer->fire('report_due', $t);
                $count++;
            });

        $this->info("Đã xử lý nhắc báo cáo cho {$count} hồ sơ.");

        return self::SUCCESS;
    }
}
