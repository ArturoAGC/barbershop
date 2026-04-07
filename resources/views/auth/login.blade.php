<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook — Iniciar sesión</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✂</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-gray-900 rounded-xl border border-yellow-600 p-8">
        
        <div class="text-center mb-8">
            <h1 class="text-yellow-500 text-3xl font-bold">BarberBook</h1>
            <p class="text-gray-400 mt-2">Inicia sesión en tu cuenta</p>
        </div>

        @if($errors->any())
            <div class="bg-red-900 text-red-300 px-4 py-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    placeholder="correo@ejemplo.com" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-400 text-sm mb-2">Contraseña</label>
                <input type="password" name="password"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    placeholder="••••••••" required>
            </div>
            <button type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-3 rounded-lg transition">
                Iniciar sesión
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            ¿No tienes cuenta? 
            <a href="/register" class="text-yellow-400 hover:text-yellow-300">Regístrate aquí</a>
        </p>
    </div>
</body>
</html>