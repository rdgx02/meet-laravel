<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\ReservationConflictService;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('room')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('reservations.create', compact('rooms'));
    }

    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();

        // Delegamos a regra de conflito para a camada de serviço
        $conflictService = new ReservationConflictService();

        if ($conflictService->hasConflict($data)) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Conflito: já existe um agendamento nessa sala nesse horário.',
                ]);
        }

        Reservation::create($data);

        return redirect('/reservations');
    }
}