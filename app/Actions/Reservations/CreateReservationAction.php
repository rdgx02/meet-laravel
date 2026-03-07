<?php

namespace App\Actions\Reservations;

use App\Exceptions\ReservationConflictException;
use App\Models\Reservation;
use App\Services\ReservationConflictService;

class CreateReservationAction
{
    public function __construct(
        private readonly ReservationConflictService $conflictService
    ) {}

    public function execute(array $data, int $creatorId): Reservation
    {
        $payload = $data;
        $payload['user_id'] = $creatorId;

        if ($this->conflictService->hasConflict($payload)) {
            throw ReservationConflictException::forRoomAndTime();
        }

        return Reservation::create($payload);
    }
}
