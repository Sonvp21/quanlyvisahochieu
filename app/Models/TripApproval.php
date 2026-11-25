<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripApproval extends Model
{
    protected $fillable = ['trip_id', 'step', 'approver_id', 'action', 'comment'];

    public function approver() { return $this->belongsTo(User::class, 'approver_id'); }
    public function trip()     { return $this->belongsTo(Trip::class); }
}
