<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook — Reservas</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✂</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <nav class="bg-gray-900 border-b border-yellow-600 px-6 py-4 flex justify-between items-center">
        <span class="text-yellow-500 font-bold text-xl">BarberBook</span>
        <div class="flex gap-6 items-center">
            <a href="/admin/dashboard" class="text-gray-300 hover:text-yellow-400 text-sm">Dashboard</a>
            <a href="/admin/services" class="text-gray-300 hover:text-yellow-400 text-sm">Servicios</a>
            <a href="/admin/barbers" class="text-gray-300 hover:text-yellow-400 text-sm">Estilistas</a>
            <a href="/admin/reservations" class="text-yellow-400 text-sm">Reservas</a>
            <span class="text-gray-500 text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="/logout">
                @csrf
                <button class="text-sm text-red-400 hover:text-red-300">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold text-yellow-500 mb-6">Gestión de Reservas</h1>

        @if(session('success'))
            <div class="bg-green-900 text-green-300 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtros --}}
        <form method="GET" action="/admin/reservations" class="flex gap-4 mb-6">
            <select name="status"
                class="bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-yellow-500">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
            </select>
            <input type="date" name="date" value="{{ request('date') }}"
                class="bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-yellow-500">
            <button type="submit"
                class="bg-yellow-600 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-lg text-sm transition">
                Filtrar
            </button>
            <a href="/admin/reservations"
                class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
                Limpiar
            </a>
        </form>

        {{-- Tabla --}}
        <div class="bg-gray-900 border border-gray-700 rounded-xl overflow-hidden">
            <table class="w-full text-sm text-gray-300">
                <thead>
                    <tr class="border-b border-gray-700 text-gray-400 bg-gray-800">
                        <th class="text-left px-4 py-3">Cliente</th>
                        <th class="text-left px-4 py-3">Servicio</th>
                        <th class="text-left px-4 py-3">Estilista</th>
                        <th class="text-left px-4 py-3">Fecha</th>
                        <th class="text-left px-4 py-3">Hora</th>
                        <th class="text-left px-4 py-3">Estado</th>
                        <th class="text-left px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                        <td class="px-4 py-3">{{ $reservation->user->name }}</td>
                        <td class="px-4 py-3">{{ $reservation->service->name }}</td>
                        <td class="px-4 py-3">{{ $reservation->barber->name }}</td>
                        <td class="px-4 py-3">{{ $reservation->reservation_date }}</td>
                        <td class="px-4 py-3">{{ substr($reservation->reservation_time, 0, 5) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $reservation->status === 'confirmed' ? 'bg-green-800 text-green-300' : '' }}
                                {{ $reservation->status === 'pending' ? 'bg-yellow-800 text-yellow-300' : '' }}
                                {{ $reservation->status === 'cancelled' ? 'bg-red-800 text-red-300' : '' }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                {{-- Cambiar estado --}}
                                <form method="POST" action="/admin/reservations/{{ $reservation->id }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()"
                                        class="bg-gray-700 border border-gray-600 text-white rounded px-2 py-1 text-xs focus:outline-none">
                                        <option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="confirmed" {{ $reservation->status === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                                        <option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                </form>
                                {{-- Eliminar --}}
                                <form method="POST" action="/admin/reservations/{{ $reservation->id }}"
                                    onsubmit="return confirm('Eliminar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-800 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            No hay reservas que mostrar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
    </main>
</body>
</html>