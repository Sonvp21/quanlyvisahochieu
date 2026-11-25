<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripFile extends Model
{
    protected $fillable = ['trip_id', 'kind', 'original_name', 'path', 'uploaded_by'];

    public function trip()     { return $this->belongsTo(Trip::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }

    public function getKindLabelAttribute(): string
    {
        return Trip::FILE_KINDS[$this->kind] ?? $this->kind;
    }
}
