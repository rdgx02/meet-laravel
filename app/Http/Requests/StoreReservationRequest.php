<?php

namespace App\Http\Requests;

use App\Models\Reservation;

class StoreReservationRequest extends ReservationRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Reservation::class) ?? false;
    }
}
