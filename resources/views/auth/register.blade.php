<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook — Registro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-gray-900 rounded-xl border border-yellow-600 p-8">

        <div class="text-center mb-8">
            <h1 class="text-yellow-500 text-3xl font-bold">BarberBook</h1>
            <p class="text-gray-400 mt-2">Crea tu cuenta</p>
        </div>

        @if($errors->any())
            <div class="bg-red-900 text-red-300 px-4 py-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Nombre completo</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    placeholder="Tu nombre" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    placeholder="correo@ejemplo.com" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Contraseña</label>
                <input type="password" name="password"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    placeholder="Mínimo 8 caracteres" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-400 text-sm mb-2">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    placeholder="Repite tu contraseña" required>
            </div>
            <button type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-3 rounded-lg transition">
                Crear cuenta
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            ¿Ya tienes cuenta?
            <a href="/login" class="text-yellow-400 hover:text-yellow-300">Inicia sesión</a>
        </p>
    </div>
</body>
</html>