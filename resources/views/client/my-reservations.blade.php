@extends('layouts.app')

@section('title', 'Mis reservas')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold text-yellow-500 mb-6">Mis reservas</h1>

    @include('admin.partials.flash')

    <div class="overflow-x-auto rounded-xl border border-gray-800">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs uppercase tracking-wider text-gray-500 bg-gray-900 border-b border-gray-800">
                <tr>
                    <th class="px-5 py-3">Servicio</th>
                    <th class="px-5 py-3">Estilista</th>
                    <th class="px-5 py-3">Fecha</th>
                    <th class="px-5 py-3">Hora</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800 bg-gray-950">
                @forelse ($reservations as $reservation)
                <tr class="hover:bg-gray-900 transition">
                    <td class="px-5 py-4 font-medium text-white">{{ $reservation->service->name }}</td>
                    <td class="px-5 py-4 text-gray-400">{{ $reservation->barber->name }}</td>
                    <td class="px-5 py-4">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</td>
                    <td class="px-5 py-4">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</td>
                    <td class="px-5 py-4">
                        @if ($reservation->status === 'pending')
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-900/40 text-yellow-400 border border-yellow-700">Pendiente</span>
                        @elseif ($reservation->status === 'confirmed')
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-green-900/50 text-green-400 border border-green-700">Confirmada</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-800 text-gray-500 border border-gray-700">Cancelada</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        @if (in_array($reservation->status, ['pending', 'confirmed']))
                            <form action="{{ route('client.reservations.cancel', $reservation) }}" method="POST" class="inline"
                                  onsubmit="return confirm('¿Cancelar esta reserva?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-400 text-xs font-medium transition">
                                    Cancelar
                                </button>
                            </form>
                        @else
                            <span class="text-gray-700 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-600">
                        No tienes reservas aún.
                        <a href="{{ route('client.booking') }}" class="text-yellow-500 hover:text-yellow-400 ml-1">Haz tu primera reserva</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reservations->links() }}
    </div>

</div>
@endsection