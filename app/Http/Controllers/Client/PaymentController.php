<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private MercadoPagoService $mercadoPago)
    {
    }

    public function checkout(Reservation $reservation)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($reservation->user_id !== $user->id) {
            abort(403);
        }

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Esta reserva ya no admite pagos.');
        }

        if ($reservation->isPaid()) {
            return back()->with('error', 'Esta reserva ya fue pagada.');
        }

        $amount = (float) config('services.mercadopago.deposit_amount');

        $preference = $this->mercadoPago->createPreference($reservation, $amount);

        Payment::updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'provider'      => 'mercadopago',
                'preference_id' => $preference['id'] ?? null,
                'status'        => 'pending',
                'amount'        => $amount,
                'currency'      => 'MXN',
            ]
        );

        $checkoutUrl = $preference['sandbox_init_point'] ?? $preference['init_point'];

        return redirect()->away($checkoutUrl);
    }
    public function webhook(Request $request)
    {
        $type = $request->input('type') ?? $request->query('topic');
        $paymentId = $request->input('data.id') ?? $request->query('id');

        if ($type !== 'payment' || empty($paymentId)) {
            return response()->json(['ok' => true]);
        }

        $paymentData = $this->mercadoPago->getPayment((string) $paymentId);

        $reservationId = $paymentData['external_reference'] ?? null;
        $reservation = $reservationId ? Reservation::find($reservationId) : null;

        if (!$reservation) {
            return response()->json(['ok' => true]);
        }

        $payment = Payment::firstOrNew(['reservation_id' => $reservation->id]);
        $payment->provider = 'mercadopago';
        $payment->preference_id = $paymentData['preference_id'] ?? $payment->preference_id;
        $payment->provider_payment_id = (string) $paymentId;
        $payment->status = $paymentData['status'] ?? 'pending';
        $payment->amount = $payment->amount ?: ($paymentData['transaction_amount'] ?? 0);
        $payment->currency = $paymentData['currency_id'] ?? 'MXN';
        $payment->raw_payload = $paymentData;
        $payment->save();

        if ($payment->status === 'approved' && $reservation->status !== 'confirmed') {
            $reservation->update(['status' => 'confirmed']);
        }

        return response()->json(['ok' => true]);
    }
    public function returnFromCheckout(Request $request)
    {
        $status = $request->query('collection_status') ?? $request->query('status');

        $messages = [
            'approved'   => ['success', '¡Pago recibido! Tu reserva quedará confirmada en unos segundos.'],
            'pending'    => ['success', 'Tu pago está en proceso. Te avisaremos cuando se confirme.'],
            'in_process' => ['success', 'Tu pago está en proceso. Te avisaremos cuando se confirme.'],
            'rejected'   => ['error', 'El pago fue rechazado. Puedes intentarlo de nuevo desde tus reservas.'],
        ];

        [$type, $message] = $messages[$status] ?? ['success', 'Volviste de Mercado Pago.'];

        return redirect()->route('client.my-reservations')->with($type, $message);
    }
}