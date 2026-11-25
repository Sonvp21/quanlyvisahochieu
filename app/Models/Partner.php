<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public const TYPES = [
        'university'   => 'Trường đại học, viện nghiên cứu',
        'company'      => 'Doanh nghiệp',
        'organization' => 'Tổ chức quốc tế, phi chính phủ',
        'government'   => 'Cơ quan nhà nước, đại sứ quán',
        'other'        => 'Khác',
    ];

    protected $fillable = [
        'name', 'short_name', 'partner_type', 'country', 'city', 'website',
        'contact_name', 'contact_email', 'contact_phone', 'notes', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function agreements() { return $this->hasMany(Agreement::class)->orderByDesc('signed_at'); }
    public function programs()   { return $this->hasMany(ExchangeProgram::class)->orderByDesc('id'); }
    public function trips()      { return $this->hasMany(Trip::class)->orderByDesc('depart_at'); }

    public function getTypeLabelAttribute(): string { return self::TYPES[$this->partner_type] ?? $this->partner_type; }
}
