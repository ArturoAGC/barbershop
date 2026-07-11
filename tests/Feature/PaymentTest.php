<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.mercadopago.access_token' => 'TEST-fake-token']);
        config(['services.mercadopago.deposit_amount' => 200]);
    }

    public function test_client_can_start_checkout_for_own_reservation(): void
    {
        Http::fake([
            'api.mercadopago.com/checkout/preferences' => Http::response([
                'id'                 => 'PREF-123',
                'init_point'         => 'https://www.mercadopago.com/checkout/init',
                'sandbox_init_point' => 'https://sandbox.mercadopago.com/checkout/init',
            ], 201),
        ]);

        $client = User::factory()->create(['role' => 'client']);
        $reservation = Reservation::factory()->create([
            'user_id' => $client->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($client)
            ->post("/client/reservations/{$reservation->id}/pay");

        $response->assertRedirect('https://sandbox.mercadopago.com/checkout/init');

        $this->assertDatabaseHas('payments', [
            'reservation_id' => $reservation->id,
            'preference_id'  => 'PREF-123',
            'status'         => 'pending',
            'amount'         => 200,
        ]);
    }

    public function test_webhook_approves_payment_and_confirms_reservation(): void
    {
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        Http::fake([
            'api.mercadopago.com/v1/payments/*' => Http::response([
                'id'                 => 555555,
                'status'             => 'approved',
                'transaction_amount' => 200,
                'currency_id'        => 'MXN',
                'preference_id'      => 'PREF-123',
                'external_reference' => (string) $reservation->id,
            ], 200),
        ]);

        $response = $this->postJson('/webhooks/mercadopago', [
            'type' => 'payment',
            'data' => ['id' => 555555],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('payments', [
            'reservation_id'      => $reservation->id,
            'status'              => 'approved',
            'provider_payment_id' => '555555',
        ]);

        $this->assertDatabaseHas('reservations', [
            'id'     => $reservation->id,
            'status' => 'confirmed',
        ]);
    }
    public function test_client_cannot_pay_for_another_clients_reservation(): void
    {
        $owner = User::factory()->create(['role' => 'client']);
        $other = User::factory()->create(['role' => 'client']);

        $reservation = Reservation::factory()->create([
            'user_id' => $owner->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($other)
            ->post("/client/reservations/{$reservation->id}/pay");

        $response->assertStatus(403);
        $this->assertDatabaseCount('payments', 0);
    }
    public function test_webhook_ignores_non_payment_notifications(): void
    {
        $response = $this->postJson('/webhooks/mercadopago', [
            'type' => 'merchant_order',
            'data' => ['id' => 999],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('payments', 0);
    }
}