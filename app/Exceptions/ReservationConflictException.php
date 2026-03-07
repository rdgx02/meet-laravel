<?php

namespace App\Exceptions;

use DomainException;

class ReservationConflictException extends DomainException
{
    public static function forRoomAndTime(): self
    {
        return new self('Conflito: ja existe um agendamento nessa sala nesse horario.');
    }
}
