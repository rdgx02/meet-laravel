<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\ReservationConflictService;

class ReservationController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Reservation::class);

        $perPage = (int) request()->get('per_page', 10);
        $roomId  = request()->get('room_id');
        $q       = trim((string) request()->get('q', ''));

        // Salas para o select do filtro
        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Query base (carrega room, criador e editor)
        $query = Reservation::with(['room', 'user', 'editor'])
            ->orderBy('date')
            ->orderBy('start_time');

        // Filtro por sala (se vier room_id)
        if (!empty($roomId)) {
            $query->where('room_id', $roomId);
        }

        // Filtro: somente futuras (inclui hoje inteiro; some só quando virar o dia)
        if (request()->boolean('only_future')) {
            $query->whereDate('date', '>=', now()->toDateString());
        }

        // Busca (Título, Solicitante, Sala)
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('requester', 'like', "%{$q}%")
                    ->orWhereHas('room', function ($room) use ($q) {
                        $room->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $reservations = $query
            ->paginate($perPage)
            ->withQueryString();

        return view('reservations.index', compact('reservations', 'rooms'));
    }

    public function create()
    {
        $this->authorize('create', Reservation::class);

        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('reservations.create', compact('rooms'));
    }

    public function store(StoreReservationRequest $request)
    {
        $this->authorize('create', Reservation::class);

        $data = $request->validated();

        // Rastreabilidade: quem criou
        $data['user_id'] = auth()->id();

        $conflictService = new ReservationConflictService();

        if ($conflictService->hasConflict($data)) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Conflito: já existe um agendamento nessa sala nesse horário.',
                ]);
        }

        Reservation::create($data);

        return redirect()->route('reservations.index')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        $reservation->load(['room', 'user', 'editor']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('reservations.edit', compact('reservation', 'rooms'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $data = $request->validated();

        $conflictService = new ReservationConflictService();

        if ($conflictService->hasConflict($data, $reservation->id)) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Conflito: já existe um agendamento nessa sala nesse horário.',
                ]);
        }

        // ✅ Rastreabilidade: quem editou por último
        $data['updated_by'] = auth()->id();

        $reservation->update($data);

        return redirect()->route('reservations.index')
            ->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Agendamento excluído com sucesso!');
    }
}