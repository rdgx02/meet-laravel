<?php

namespace App\Services;

use App\Models\Reservation;

class ReservationConflictService
{
    /**
     * Retorna true se existir conflito de horário para a sala/data informadas.
     */
    public function hasConflict(array $data): bool
    {
        return Reservation::where('room_id', $data['room_id'])
            ->where('date', $data['date'])
            ->where(function ($q) use ($data) {
                // sobreposição: novo_inicio < fim_existente AND novo_fim > inicio_existente
                $q->where('start_time', '<', $data['end_time'])
                  ->where('end_time',   '>', $data['start_time']);
            })
            ->exists();
    }
}