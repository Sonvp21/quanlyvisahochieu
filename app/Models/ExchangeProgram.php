<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeProgram extends Model
{
    public const TYPES = [
        'semester'    => 'Trao đổi học kỳ',
        'short_term'  => 'Ngắn hạn (dưới 3 tháng)',
        'summer'      => 'Trường hè',
        'dual_degree' => 'Song bằng, liên kết đào tạo',
        'internship'  => 'Thực tập quốc tế',
    ];

    protected $fillable = [
        'name', 'partner_id', 'agreement_id', 'program_type', 'level', 'country', 'apply_deadline',
        'start_date', 'end_date', 'slots', 'benefits', 'requirements', 'status', 'created_by',
    ];

    protected $casts = ['apply_deadline' => 'date', 'start_date' => 'date', 'end_date' => 'date', 'slots' => 'integer'];

    public function partner()      { return $this->belongsTo(Partner::class); }
    public function agreement()    { return $this->belongsTo(Agreement::class); }
    public function applications() { return $this->hasMany(ExchangeApplication::class)->orderBy('id'); }

    public function getTypeLabelAttribute(): string { return self::TYPES[$this->program_type] ?? $this->program_type; }
    public function getLevelLabelAttribute(): string { return Person::LEVELS[$this->level] ?? ($this->level ?: '—'); }
}
