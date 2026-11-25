<?php

namespace App\Providers;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Throwable;

/**
 * Ghi nhật ký thao tác: tạo / sửa / xóa các bản ghi nghiệp vụ và đăng nhập.
 */
class AuditServiceProvider extends ServiceProvider
{
    /** Lớp model được ghi nhật ký => nhãn hiển thị */
    private const LABELS = [
        \App\Models\Trip::class                => 'Hồ sơ đoàn',
        \App\Models\TripApproval::class        => 'Phê duyệt',
        \App\Models\TripReport::class          => 'Báo cáo',
        \App\Models\TripFile::class            => 'Tệp đính kèm',
        \App\Models\TripExpense::class         => 'Khoản chi',
        \App\Models\Partner::class             => 'Đối tác',
        \App\Models\Agreement::class           => 'Thỏa thuận',
        \App\Models\ExchangeProgram::class     => 'Chương trình trao đổi',
        \App\Models\ExchangeApplication::class => 'Hồ sơ đăng ký trao đổi',
        \App\Models\AnnualPlan::class          => 'Kế hoạch năm',
        \App\Models\MailRule::class            => 'Quy tắc email',
        \App\Models\Person::class              => 'Hồ sơ nhân sự',
        \App\Models\Unit::class                => 'Đơn vị',
        \App\Models\User::class                => 'Tài khoản',
        \App\Models\Passport::class            => 'Hộ chiếu',
        \App\Models\Visa::class                => 'Visa',
        \App\Models\Delegation::class          => 'Công văn báo đoàn',
    ];

    private const HIDDEN = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];

    private const SKIP = ['updated_at', 'created_at'];

    public function boot(): void
    {
        foreach (['created', 'updated', 'deleted'] as $action) {
            Event::listen("eloquent.{$action}: *", function (string $event, array $payload) use ($action) {
                $model = $payload[0] ?? null;
                if ($model instanceof Model && isset(self::LABELS[get_class($model)])) {
                    $this->record($action, $model);
                }
            });
        }

        Event::listen(Login::class, function (Login $e) {
            try {
                AuditLog::create([
                    'user_id' => $e->user->getAuthIdentifier(), 'action' => 'login', 'subject_type' => get_class($e->user),
                    'subject_id' => $e->user->getAuthIdentifier(), 'summary' => 'Đăng nhập hệ thống', 'ip' => request()->ip(),
                ]);
            } catch (Throwable $ex) {
                report($ex);
            }
        });
    }

    private function record(string $action, Model $model): void
    {
        try {
            $changes = null;
            if ($action === 'updated') {
                $changes = [];
                foreach ($model->getChanges() as $k => $new) {
                    if (in_array($k, self::SKIP, true)) {
                        continue;
                    }
                    $hidden = in_array($k, self::HIDDEN, true);
                    $changes[$k] = [
                        'old' => $hidden ? '***' : $this->short($model->getOriginal($k)),
                        'new' => $hidden ? '***' : $this->short($new),
                    ];
                }
                if (!$changes) {
                    return; // chỉ đổi updated_at
                }
            }

            $name = $model->name ?? $model->title ?? $model->full_name ?? $model->code ?? null;
            $label = self::LABELS[get_class($model)];

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => $action,
                'subject_type' => get_class($model),
                'subject_id'   => $model->getKey(),
                'trip_id'      => $model instanceof \App\Models\Trip ? $model->getKey() : ($model->trip_id ?? null),
                'summary'      => $label . ' #' . $model->getKey() . ($name ? ': ' . mb_substr((string) $name, 0, 120) : ''),
                'changes'      => $changes,
                'ip'           => app()->runningInConsole() ? null : request()->ip(),
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function short($v): mixed
    {
        if ($v instanceof \DateTimeInterface) {
            return $v->format('Y-m-d H:i');
        }
        if (is_array($v)) {
            $v = json_encode($v, JSON_UNESCAPED_UNICODE);
        }

        return is_string($v) ? mb_substr($v, 0, 200) : $v;
    }
}
