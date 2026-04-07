<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook — Mi cuenta</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✂</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <nav class="bg-gray-900 border-b border-yellow-600 px-6 py-4 flex justify-between items-center">
        <span class="text-yellow-500 font-bold text-xl">BarberBook</span>
        <div class="flex gap-6 items-center">
            <a href="/client/booking" class="text-gray-300 hover:text-yellow-400 text-sm">Nueva reserva</a>
            <a href="/client/my-reservations" class="text-gray-300 hover:text-yellow-400 text-sm">Mis reservas</a>
            <span class="text-gray-500 text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="/logout">
                @csrf
                <button class="text-sm text-red-400 hover:text-red-300">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold text-yellow-500 mb-2">
            Bienvenido, {{ Auth::user()->name }}
        </h1>
        <p class="text-gray-400 mb-8">Que deseas hacer hoy?</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="/client/booking"
                class="bg-gray-900 border border-yellow-600 hover:border-yellow-400 rounded-xl p-6 transition">
                <h2 class="text-white font-semibold text-lg">Hacer una reserva</h2>
                <p class="text-gray-400 text-sm mt-1">Agenda tu cita con tu estilista favorito</p>
            </a>
            <a href="/client/my-reservations"
                class="bg-gray-900 border border-gray-700 hover:border-yellow-400 rounded-xl p-6 transition">
                <h2 class="text-white font-semibold text-lg">Mis reservas</h2>
                <p class="text-gray-400 text-sm mt-1">Consulta y gestiona tus citas activas</p>
            </a>
        </div>
    </main>
</body>
</html>