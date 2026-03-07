<?php

namespace App\Actions\Reservations;

use App\Exceptions\ReservationConflictException;
use App\Models\Reservation;
use App\Services\ReservationConflictService;
use Illuminate\Support\Facades\DB;

class CreateReservationAction
{
    public function __construct(
        private readonly ReservationConflictService $conflictService
    ) {}

    public function execute(array $data, int $creatorId): Reservation
    {
        $payload = $data;
        $payload['user_id'] = $creatorId;

        return DB::transaction(function () use ($payload): Reservation {
            if ($this->conflictService->hasConflict($payload, lockForUpdate: true)) {
                throw ReservationConflictException::forRoomAndTime();
            }

            return Reservation::create($payload);
        });
    }
}
