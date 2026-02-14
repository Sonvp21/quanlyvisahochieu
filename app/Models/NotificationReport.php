<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationReport extends Model
{
    protected $fillable = [
        'passport_count',
        'visa_count',
        'total_count',
        'details',
        'duration_seconds',
        'run_at',
    ];

    protected $casts = [
        'run_at' => 'datetime',
        'details' => 'array',
    ];

    public function getDurationFormatAttribute()
    {
        $seconds = $this->duration_seconds;

        if ($seconds < 60) {
            return "{$seconds} giây";
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        return "{$minutes} phút {$remainingSeconds} giây";
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('run_at', 'desc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('run_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('run_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('run_at', now()->month)
                     ->whereYear('run_at', now()->year);
    }
}
