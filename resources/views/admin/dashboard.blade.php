<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook — Panel Admin</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✂</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <nav class="bg-gray-900 border-b border-yellow-600 px-6 py-4 flex justify-between items-center">
        <span class="text-yellow-500 font-bold text-xl">BarberBook</span>
        <div class="flex gap-6 items-center">
            <a href="/admin/services" class="text-gray-300 hover:text-yellow-400 text-sm">Servicios</a>
            <a href="/admin/barbers" class="text-gray-300 hover:text-yellow-400 text-sm">Estilistas</a>
            <a href="/admin/reservations" class="text-gray-300 hover:text-yellow-400 text-sm">Reservas</a>
            <span class="text-gray-500 text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="/logout">
                @csrf
                <button class="text-sm text-red-400 hover:text-red-300">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold text-yellow-500 mb-8">Panel de Administración</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
                <p class="text-gray-400 text-sm">Total Servicios</p>
                <p class="text-4xl font-bold text-yellow-500 mt-2">{{ \App\Models\Service::count() }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
                <p class="text-gray-400 text-sm">Total Estilistas</p>
                <p class="text-4xl font-bold text-yellow-500 mt-2">{{ \App\Models\Barber::count() }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
                <p class="text-gray-400 text-sm">Total Reservas</p>
                <p class="text-4xl font-bold text-yellow-500 mt-2">{{ \App\Models\Reservation::count() }}</p>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Reservas recientes</h2>
            @if(\App\Models\Reservation::count() > 0)
                <table class="w-full text-sm text-gray-300">
                    <thead>
                        <tr class="border-b border-gray-700 text-gray-400">
                            <th class="text-left py-2">Cliente</th>
                            <th class="text-left py-2">Servicio</th>
                            <th class="text-left py-2">Estilista</th>
                            <th class="text-left py-2">Fecha</th>
                            <th class="text-left py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Reservation::with(['user','service','barber'])->latest()->take(5)->get() as $r)
                        <tr class="border-b border-gray-800">
                            <td class="py-3">{{ $r->user->name }}</td>
                            <td class="py-3">{{ $r->service->name }}</td>
                            <td class="py-3">{{ $r->barber->name }}</td>
                            <td class="py-3">{{ $r->reservation_date }} {{ $r->reservation_time }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $r->status === 'confirmed' ? 'bg-green-800 text-green-300' : '' }}
                                    {{ $r->status === 'pending' ? 'bg-yellow-800 text-yellow-300' : '' }}
                                    {{ $r->status === 'cancelled' ? 'bg-red-800 text-red-300' : '' }}">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">No hay reservas aún.</p>
            @endif
        </div>
    </main>
</body>
</html>