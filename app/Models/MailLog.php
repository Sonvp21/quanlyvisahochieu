<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $fillable = ['trip_id', 'mail_rule_id', 'event', 'recipients', 'subject', 'status', 'error'];

    public function trip() { return $this->belongsTo(Trip::class); }
}
