<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Visa $m) {
            if (!$m->person_id && $m->student_id) {
                $m->person_id = Person::where('student_id', $m->student_id)->value('id');
            }
        });
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    protected $fillable = [
        'student_id',
        'person_id',
        'visa_type',
        'country',          // ← THÊM MỚI
        'visa_number',
        'issue_date',
        'expiry_date',
        'entry_type',       // ← THÊM MỚI
        'status',           // ← THÊM MỚI
        'image',
        'last_updated_by',  // ← THÊM MỚI
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
