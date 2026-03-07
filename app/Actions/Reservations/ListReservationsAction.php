<?php

namespace App\Actions\Reservations;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListReservationsAction
{
    private const DEFAULT_PER_PAGE = 10;

    private const ALLOWED_PER_PAGE = [10, 20, 50, 100];

    public function execute(array $filters, string $scope = 'upcoming'): LengthAwarePaginator
    {
        $perPage = $this->resolvePerPage($filters['per_page'] ?? self::DEFAULT_PER_PAGE);
        $roomId = $filters['room_id'] ?? null;
        $q = trim((string) ($filters['q'] ?? ''));
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;

        $query = Reservation::query()
            ->with(['room', 'user', 'editor']);

        if ($scope === 'history') {
            $query->whereDate('date', '<', now()->toDateString())
                ->orderByDesc('date')
                ->orderByDesc('start_time');
        } else {
            $query->whereDate('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->orderBy('start_time');
        }

        if (! empty($roomId)) {
            $query->where('room_id', $roomId);
        }

        if (! empty($dateFrom)) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if (! empty($dateTo)) {
            $query->whereDate('date', '<=', $dateTo);
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
