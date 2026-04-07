<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private string $openTime  = '09:00';
    private string $closeTime = '18:00';
    private int    $interval  = 45;

    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $barbers  = Barber::where('is_active', true)->orderBy('name')->get();

        return view('client.booking', compact('services', 'barbers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id'       => 'required|exists:services,id',
            'barber_id'        => 'required|exists:barbers,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'notes'            => 'nullable|string|max:500',
        ]);

        $conflict = Reservation::where('barber_id', $request->barber_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('reservation_time', $request->reservation_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->with('error', 'Ese horario ya no está disponible. Elige otro.');
        }

        /** @var User $user */
        $user = auth()->user();

        Reservation::create([
            'user_id'          => $user->id,
            'service_id'       => $request->service_id,
            'barber_id'        => $request->barber_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'status'           => 'pending',
            'notes'            => $request->notes,
        ]);

        return redirect()->route('client.my-reservations')
                         ->with('success', 'Reserva creada correctamente.');
    }

    public function myReservations()
    {
        /** @var User $user */
        $user = auth()->user();

        $reservations = Reservation::with(['service', 'barber'])
            ->where('user_id', $user->id)
            ->orderByDesc('reservation_date')
            ->orderByDesc('reservation_time')
            ->paginate(10);

        return view('client.my-reservations', compact('reservations'));
    }

    public function cancel(Reservation $reservation)
    {
        $user = auth()->user();
        assert($user instanceof User);

        if ($reservation->user_id !== $user->id) {
            abort(403);
        }

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Esta reserva no puede cancelarse.');
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada correctamente.');
    }

    public function availableSlots(Request $request)
    {
        $request->validate([
            'barber_id'        => 'required|exists:barbers,id',
            'reservation_date' => 'required|date',
        ]);

        $slots = $this->generateSlots(
            $request->barber_id,
            $request->reservation_date
        );

        return response()->json($slots);
    }

    private function generateSlots(int $barberId, string $date): array
    {
        $taken = Reservation::where('barber_id', $barberId)
            ->where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('reservation_time')
            ->map(fn($t) => substr($t, 0, 5))
            ->toArray();

        $slots  = [];
        $cursor = Carbon::createFromFormat('H:i', $this->openTime);
        $close  = Carbon::createFromFormat('H:i', $this->closeTime);

        while ($cursor < $close) {
            $time    = $cursor->format('H:i');
            $slots[] = [
                'value'     => $time,
                'label'     => $cursor->format('h:i A'),
                'available' => !in_array($time, $taken),
            ];
            $cursor->addMinutes($this->interval);
        }

        return $slots;
    }
}