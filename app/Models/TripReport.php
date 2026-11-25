<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripReport extends Model
{
    protected $fillable = ['trip_id', 'submitted_by', 'summary', 'results', 'recommendations', 'submitted_at'];

    protected $casts = ['submitted_at' => 'datetime'];

    public function trip()      { return $this->belongsTo(Trip::class); }
    public function submitter() { return $this->belongsTo(User::class, 'submitted_by'); }
}
