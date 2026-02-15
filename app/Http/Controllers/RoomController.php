<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('name')->get();

        return view('rooms.index', compact('rooms'));
    }
}