<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeApplication extends Model
{
    public const STATUSES = [
        'applied'     => ['Đã nộp hồ sơ', 'bg-slate-100 text-slate-600'],
        'shortlisted' => ['Vào danh sách rút gọn', 'bg-blue-50 text-blue-700 ring-1 ring-blue-200'],
        'approved'    => ['Được chọn', 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'],
        'rejected'    => ['Không đạt', 'bg-red-50 text-red-700 ring-1 ring-red-200'],
        'ongoing'     => ['Đang trao đổi', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
        'completed'   => ['Hoàn thành', 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200'],
        'withdrawn'   => ['Rút hồ sơ', 'bg-slate-100 text-slate-500'],
    ];

    protected $fillable = ['exchange_program_id', 'person_id', 'status', 'gpa', 'language_cert', 'note', 'decided_by', 'decided_at'];

    protected $casts = ['decided_at' => 'datetime', 'gpa' => 'decimal:2'];

    public function program() { return $this->belongsTo(ExchangeProgram::class, 'exchange_program_id'); }
    public function person()  { return $this->belongsTo(Person::class); }
    public function trip()    { return $this->hasOne(Trip::class, 'exchange_application_id'); }

    public function getStatusLabelAttribute(): string { return self::STATUSES[$this->status][0] ?? $this->status; }
    public function getStatusClassAttribute(): string { return self::STATUSES[$this->status][1] ?? 'bg-slate-100 text-slate-600'; }
}
