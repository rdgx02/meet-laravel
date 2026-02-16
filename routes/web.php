<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Rotas principais do sistema Meet LADETEC
|--------------------------------------------------------------------------
*/

// Página inicial
Route::get('/', function () {
    return view('home');
})->name('home');

// Salas
Route::get('/rooms', [RoomController::class, 'index'])
    ->name('rooms.index');

// Agendamentos (CRUD completo)
Route::resource('reservations', ReservationController::class);