<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';

    public const TYPES = [
        'staff'        => 'Cán bộ, giảng viên',
        'student_vn'   => 'Sinh viên Việt Nam',
        'student_intl' => 'Sinh viên quốc tế',
        'guest'        => 'Khách / đối tác',
    ];

    public const LEVELS = [
        'dai_hoc' => 'Đại học',
        'thac_si' => 'Thạc sĩ',
        'tien_si' => 'Tiến sĩ',
    ];

    protected $fillable = [
        'person_type', 'full_name', 'gender', 'date_of_birth', 'nationality', 'unit_id',
        'position', 'academic_title', 'student_code', 'student_level', 'organization',
        'email', 'phone', 'user_id', 'student_id', 'notes', 'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active'     => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function passports()
    {
        return $this->hasMany(Passport::class);
    }

    public function visas()
    {
        return $this->hasMany(Visa::class);
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'trip_members')->withPivot('role_in_trip');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->person_type] ?? $this->person_type;
    }
}
