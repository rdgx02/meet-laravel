<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    protected $fillable = [
        'room_id',
        'date',
        'start_time',
        'end_time',
        'title',
        'requester',
        'contact',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // ===== Accessors formatados =====

    public function getDateBrAttribute(): string
    {
        return Carbon::parse($this->date)->format('d/m/Y');
    }

    public function getStartTimeBrAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    public function getEndTimeBrAttribute(): string
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }
}