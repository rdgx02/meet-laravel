<?php

namespace App\Http\Requests;

use App\Models\Reservation;

class UpdateReservationRequest extends ReservationRequest
{
    public function authorize(): bool
    {
        $reservation = $this->route('reservation');

        return $reservation instanceof Reservation
            && ($this->user()?->can('update', $reservation) ?? false);
    }
}
