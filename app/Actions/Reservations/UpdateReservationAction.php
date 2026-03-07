<?php

namespace App\Actions\Reservations;

use App\Exceptions\ReservationConflictException;
use App\Models\Reservation;
use App\Services\ReservationConflictService;
use Illuminate\Support\Facades\DB;

class UpdateReservationAction
{
    public function __construct(
        private readonly ReservationConflictService $conflictService
    ) {}

    public function execute(Reservation $reservation, array $data): Reservation
    {
        return DB::transaction(function () use ($reservation, $data): Reservation {
            if ($this->conflictService->hasConflict($data, $reservation->id, true)) {
                throw ReservationConflictException::forRoomAndTime();
            }

            $reservation->update($data);

            return $reservation->refresh();
        });
    }
}
