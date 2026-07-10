<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MercadoPagoService
{
    private string $baseUrl = 'https://api.mercadopago.com';

    private function accessToken(): string
    {
        $token = config('services.mercadopago.access_token');

        if (empty($token)) {
            throw new RuntimeException('Falta configurar MERCADOPAGO_ACCESS_TOKEN en el archivo .env');
        }

        return $token;
    }

    public function createPreference(Reservation $reservation, float $amount): array
    {
        $reservation->loadMissing(['service', 'barber', 'user']);

        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->post("{$this->baseUrl}/checkout/preferences", [
                'items' => [[
                    'title'       => 'Anticipo — ' . $reservation->service->name,
                    'quantity'    => 1,
                    'currency_id' => 'MXN',
                    'unit_price'  => $amount,
                ]],
                'payer' => [
                    'name'  => $reservation->user->name,
                    'email' => $reservation->user->email,
                ],
                'external_reference' => (string) $reservation->id,
                'back_urls' => [
                    'success' => route('client.payments.return'),
                    'pending' => route('client.payments.return'),
                    'failure' => route('client.payments.return'),
                ],
                'auto_return'      => 'approved',
                'notification_url' => route('webhooks.mercadopago'),
            ]);

        if ($response->failed()) {
            Log::error('Mercado Pago: error al crear preferencia', [
                'reservation_id' => $reservation->id,
                'body'           => $response->body(),
            ]);
            throw new RuntimeException('No se pudo iniciar el pago con Mercado Pago.');
        }

        return $response->json();
    }
    public function getPayment(string $paymentId): array
    {
        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->get("{$this->baseUrl}/v1/payments/{$paymentId}");

        if ($response->failed()) {
            Log::error('Mercado Pago: error al consultar pago', [
                'payment_id' => $paymentId,
                'body'       => $response->body(),
            ]);
            throw new RuntimeException('No se pudo consultar el pago en Mercado Pago.');
        }

        return $response->json();
    }
}