<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    protected $fillable = [
        'student_id',
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
