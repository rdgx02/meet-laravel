<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretary_can_create_reservation(): void
    {
        $user = User::factory()->create(['role' => UserRole::Secretary]);
        $room = Room::create(['name' => 'Lab 203', 'is_active' => true]);
        $date = now()->addDay()->toDateString();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'room_id' => $room->id,
            'date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'title' => 'Reuniao de planejamento',
            'requester' => 'Equipe Produto',
            'contact' => 'produto@example.com',
        ]);

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'user_id' => $user->id,
            'date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);
    }

    public function test_regular_user_cannot_create_reservation(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $room = Room::create(['name' => 'Lab 305', 'is_active' => true]);

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'room_id' => $room->id,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '13:00',
            'end_time' => '14:00',
            'title' => 'Tentativa sem permissao',
            'requester' => 'Usuario comum',
            'contact' => null,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_conflicting_reservation_returns_validation_error(): void
    {
        $user = User::factory()->create(['role' => UserRole::Secretary]);
        $room = Room::create(['name' => 'Sala 207', 'is_active' => true]);
        $date = now()->addDay()->toDateString();

        Reservation::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'title' => 'Reserva existente',
            'requester' => 'Secretaria',
            'contact' => null,
        ]);

        $response = $this->actingAs($user)
            ->from(route('reservations.create'))
            ->post(route('reservations.store'), [
                'room_id' => $room->id,
                'date' => $date,
                'start_time' => '10:30',
                'end_time' => '11:30',
                'title' => 'Reserva em conflito',
                'requester' => 'Secretaria',
                'contact' => null,
            ]);

        $response->assertRedirect(route('reservations.create'));
        $response->assertSessionHasErrors('start_time');
        $this->assertDatabaseCount('reservations', 1);
    }
}
