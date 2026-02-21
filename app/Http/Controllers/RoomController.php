<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        // 🔐 Autorização via Policy
        $this->authorize('viewAny', Room::class);

        $rooms = Room::orderBy('name')->get();

        return view('rooms.index', compact('rooms'));
    }
}