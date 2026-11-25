<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    public const TYPES = ['mou' => 'MOU (ghi nhớ)', 'moa' => 'MOA (thỏa thuận)', 'contract' => 'Hợp đồng', 'other' => 'Khác'];

    /** Số ngày cảnh báo trước khi hết hạn */
    public const WARN_DAYS = 90;

    protected $fillable = [
        'partner_id', 'unit_id', 'agreement_type', 'title', 'reference_no', 'signed_at', 'expires_at',
        'is_terminated', 'scope', 'file_path', 'file_name',
    ];

    protected $casts = ['signed_at' => 'date', 'expires_at' => 'date', 'is_terminated' => 'boolean'];

    public function partner() { return $this->belongsTo(Partner::class); }
    public function unit()    { return $this->belongsTo(Unit::class); }

    /** active | expiring | expired | terminated */
    public function getStateAttribute(): string
    {
        if ($this->is_terminated) {
            return 'terminated';
        }
        if (!$this->expires_at) {
            return 'active';
        }
        if ($this->expires_at->isPast()) {
            return 'expired';
        }

        return now()->diffInDays($this->expires_at, false) <= self::WARN_DAYS ? 'expiring' : 'active';
    }

    public function getStateLabelAttribute(): array
    {
        return match ($this->state) {
            'terminated' => ['Đã chấm dứt', 'bg-slate-100 text-slate-500'],
            'expired'    => ['Hết hạn', 'bg-red-50 text-red-700 ring-1 ring-red-200'],
            'expiring'   => ['Sắp hết hạn', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
            default      => $this->expires_at ? ['Còn hiệu lực', 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'] : ['Không thời hạn', 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'],
        };
    }

    public function scopeExpiringSoon(Builder $q): Builder
    {
        return $q->where('is_terminated', false)->whereNotNull('expires_at')
            ->whereDate('expires_at', '<=', now()->addDays(self::WARN_DAYS));
    }
}
