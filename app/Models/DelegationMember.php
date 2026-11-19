<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelegationMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'delegation_id',
        'full_name',
        'gender',
        'date_of_birth',
        'nationality',
        'occupation',
        'passport_type',
        'passport_no',
        'passport_expiry',
        'visa_no',
        'passport_scan_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
    ];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }
}
