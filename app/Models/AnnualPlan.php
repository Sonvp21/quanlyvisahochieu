<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualPlan extends Model
{
    public const STATUSES = ['planned' => 'Dự kiến', 'postponed' => 'Hoãn', 'cancelled' => 'Hủy'];

    protected $fillable = [
        'year', 'direction', 'title', 'unit_id', 'partner_id', 'country', 'planned_month',
        'planned_members', 'budget', 'status', 'trip_id', 'notes', 'created_by',
    ];

    protected $casts = ['year' => 'integer', 'planned_month' => 'integer', 'budget' => 'integer'];

    public function unit()    { return $this->belongsTo(Unit::class); }
    public function partner() { return $this->belongsTo(Partner::class); }
    public function trip()    { return $this->belongsTo(Trip::class); }

    /** Đã thực hiện khi hồ sơ gắn kèm đã được duyệt */
    public function getIsExecutedAttribute(): bool
    {
        return $this->trip && in_array($this->trip->status, ['approved', 'completed'], true);
    }

    /** @return array{0:string,1:string} [nhãn, class] */
    public function getStateAttribute(): array
    {
        if ($this->trip) {
            return $this->is_executed
                ? ['Đã thực hiện', 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200']
                : ['Đang làm hồ sơ: ' . $this->trip->status_label, 'bg-blue-50 text-blue-700 ring-1 ring-blue-200'];
        }

        return match ($this->status) {
            'cancelled' => ['Hủy', 'bg-slate-100 text-slate-500'],
            'postponed' => ['Hoãn', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
            default     => ['Dự kiến', 'bg-slate-100 text-slate-600'],
        };
    }
}
