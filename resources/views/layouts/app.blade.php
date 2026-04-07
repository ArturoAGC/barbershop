<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BarberBook') — BarberBook</title>

    {{-- Favicon SVG inline --}}
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✂</text></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-300 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-gray-900 border-b border-yellow-600">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">

            <a href="{{ auth()->check() && auth()->user()->isAdmin() ? '/admin/dashboard' : '/client/dashboard' }}"
               class="text-yellow-500 font-bold text-lg tracking-tight">
                BarberBook
            </a>

            <div class="flex items-center gap-5 text-sm">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.services.index') }}"
                           class="text-gray-400 hover:text-yellow-500 transition">Servicios</a>
                        <a href="{{ route('admin.barbers.index') }}"
                           class="text-gray-400 hover:text-yellow-500 transition">Estilistas</a>
                        <a href="{{ route('admin.reservations.index') }}"
                           class="text-gray-400 hover:text-yellow-500 transition">Reservaciones</a>
                    @else
                        <a href="{{ url('/client/booking') }}"
                           class="text-gray-400 hover:text-yellow-500 transition">Reservar</a>
                        <a href="{{ url('/client/my-reservations') }}"
                           class="text-gray-400 hover:text-yellow-500 transition">Mis reservas</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-gray-500 hover:text-red-400 transition">
                            Salir
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </nav>

    {{-- Contenido principal --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 border-t border-gray-800 text-center text-xs text-gray-600 py-4">
        BarberBook &copy; {{ date('Y') }}
    </footer>

</body>
</html>