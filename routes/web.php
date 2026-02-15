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

// Agendamentos
Route::get('/reservations', [ReservationController::class, 'index'])
    ->name('reservations.index');

Route::get('/reservations/create', [ReservationController::class, 'create'])
    ->name('reservations.create');

Route::post('/reservations', [ReservationController::class, 'store'])
    ->name('reservations.store');