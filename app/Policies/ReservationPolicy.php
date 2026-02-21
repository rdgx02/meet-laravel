<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    private function isAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    private function isSecretary(User $user): bool
    {
        return $user->role === 'secretary';
    }

    private function canManageAgenda(User $user): bool
    {
        return $this->isAdmin($user) || $this->isSecretary($user);
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->canManageAgenda($user);
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $this->canManageAgenda($user);
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->canManageAgenda($user);
    }
}