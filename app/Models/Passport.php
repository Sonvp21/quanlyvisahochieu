<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Passport $m) {
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
        'passport_number',
        'country_of_issue',      // ← THÊM MỚI
        'place_of_issue',
        'issue_date',
        'expiry_date',
        'image',
        'last_updated_by',       // ← THÊM MỚI
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
