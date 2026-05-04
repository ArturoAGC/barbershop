<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

use App\Mail\ReservaConfirmada;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'service', 'barber'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('reservation_date', $request->date);
        }

        $reservations = $query->paginate(10);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $reservation->update(['status' => $request->status]);

        if ($request->status === 'confirmed') {
            $reservation->load('user');
            
            Log::info('Intentando enviar email a: ' . $reservation->user->email);
            
            Mail::to($reservation->user->email)
                ->send(new ReservaConfirmada($reservation));
                
            Log::info('Email enviado correctamente');
        }

        return back()->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', 'Reserva eliminada correctamente.');
    }
}