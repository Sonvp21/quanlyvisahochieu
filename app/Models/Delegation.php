<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_no',
        'host_unit',
        'purpose',
        'work_date_from',
        'work_date_to',
        'work_location',
        'accommodation_name',
        'accommodation_address',
        'recipient_units',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'work_date_from' => 'date',
        'work_date_to' => 'date',
    ];

    public function members()
    {
        return $this->hasMany(DelegationMember::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }
}
