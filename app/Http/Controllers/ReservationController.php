<?php

namespace App\Http\Controllers;

use App\Actions\Reservations\CreateReservationAction;
use App\Actions\Reservations\ListReservationsAction;
use App\Actions\Reservations\UpdateReservationAction;
use App\Exceptions\ReservationConflictException;
use App\Http\Requests\ListReservationsRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Room;

class ReservationController extends Controller
{
    public function index(ListReservationsRequest $request, ListReservationsAction $listReservations)
    {
        $rooms = Room::active()
            ->orderBy('name')
            ->get();

        $reservations = $listReservations->execute($request->validated());

        return view('reservations.index', compact('reservations', 'rooms'));
    }

    public function create()
    {
        $this->authorize('create', Reservation::class);

        $rooms = Room::active()
            ->orderBy('name')
            ->get();

        return view('reservations.create', compact('rooms'));
    }

    public function store(StoreReservationRequest $request, CreateReservationAction $createReservation)
    {
        try {
            $createReservation->execute(
                $request->validated(),
                (int) $request->user()->id
            );
        } catch (ReservationConflictException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => $exception->getMessage(),
                ]);
        }

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

        $rooms = Room::active()
            ->orderBy('name')
            ->get();

        return view('reservations.edit', compact('reservation', 'rooms'));
    }

    public function update(
        UpdateReservationRequest $request,
        Reservation $reservation,
        UpdateReservationAction $updateReservation
    ) {
        try {
            $updateReservation->execute($reservation, $request->validated());
        } catch (ReservationConflictException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => $exception->getMessage(),
                ]);
        }

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
