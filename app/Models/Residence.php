<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    protected $fillable = [
        'student_id',
        'facility_name',
        'address',
        'ward',
        'arrival_date',
        'expected_departure_date',
        'certificate_no',
        'category',
        'notes',
        'last_updated_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}