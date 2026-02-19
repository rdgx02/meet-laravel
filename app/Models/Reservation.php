<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'room_id',
        'user_id', // quem criou
        'date',
        'start_time',
        'end_time',
        'title',
        'requester',
        'contact',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors formatados
    |--------------------------------------------------------------------------
    */

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
