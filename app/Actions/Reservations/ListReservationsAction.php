<?php

namespace App\Actions\Reservations;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListReservationsAction
{
    private const DEFAULT_PER_PAGE = 10;

    private const ALLOWED_PER_PAGE = [10, 20, 50, 100];

    public function execute(array $filters): LengthAwarePaginator
    {
        $perPage = $this->resolvePerPage($filters['per_page'] ?? self::DEFAULT_PER_PAGE);
        $roomId = $filters['room_id'] ?? null;
        $q = trim((string) ($filters['q'] ?? ''));
        $onlyFuture = filter_var($filters['only_future'] ?? false, FILTER_VALIDATE_BOOL);

        $query = Reservation::query()
            ->with(['room', 'user', 'editor'])
            ->orderBy('date')
            ->orderBy('start_time');

        if (! empty($roomId)) {
            $query->where('room_id', $roomId);
        }

        if ($onlyFuture) {
            $query->whereDate('date', '>=', now()->toDateString());
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('requester', 'like', "%{$q}%")
                    ->orWhereHas('room', function ($room) use ($q) {
                        $room->where('name', 'like', "%{$q}%");
                    });
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    private function resolvePerPage(mixed $value): int
    {
        $perPage = (int) $value;

        if (! in_array($perPage, self::ALLOWED_PER_PAGE, true)) {
            return self::DEFAULT_PER_PAGE;
        }

        return $perPage;
    }
}
