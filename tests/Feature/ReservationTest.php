<?php
namespace Tests\Feature;

use App\Models\Barber;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_reservation(): void
    {
        $client  = User::factory()->create(['role' => 'client']);
        $service = Service::factory()->create();
        $barber  = Barber::factory()->create();

        $response = $this->actingAs($client)->post('/client/booking', [
            'service_id'       => $service->id,
            'barber_id'        => $barber->id,
            'reservation_date' => now()->addDay()->format('Y-m-d'),
            'reservation_time' => '10:00',
            'notes'            => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'user_id'    => $client->id,
            'service_id' => $service->id,
            'barber_id'  => $barber->id,
            'status'     => 'pending',
        ]);
    }

    public function test_client_cannot_book_same_slot_twice(): void
    {
        $client  = User::factory()->create(['role' => 'client']);
        $service = Service::factory()->create();
        $barber  = Barber::factory()->create();

        Reservation::factory()->create([
            'barber_id'        => $barber->id,
            'reservation_date' => now()->addDay()->format('Y-m-d'),
            'reservation_time' => '10:00',
            'status'           => 'pending',
        ]);

        $response = $this->actingAs($client)->post('/client/booking', [
            'service_id'       => $service->id,
            'barber_id'        => $barber->id,
            'reservation_date' => now()->addDay()->format('Y-m-d'),
            'reservation_time' => '10:00',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_client_can_cancel_own_reservation(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $reservation = Reservation::factory()->create([
            'user_id' => $client->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($client)
            ->delete("/client/my-reservations/{$reservation->id}");

        $this->assertDatabaseHas('reservations', [
            'id'     => $reservation->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_client_cannot_cancel_another_clients_reservation(): void
    {
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);

        $reservation = Reservation::factory()->create([
            'user_id' => $client1->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($client2)
            ->delete("/client/my-reservations/{$reservation->id}");

        $response->assertStatus(403);
    }

    public function test_admin_can_update_reservation_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->put("/admin/reservations/{$reservation->id}", [
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('reservations', [
            'id'     => $reservation->id,
            'status' => 'confirmed',
        ]);
    }
}
