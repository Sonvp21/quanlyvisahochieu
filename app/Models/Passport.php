<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    protected $fillable = [
        'student_id',
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
