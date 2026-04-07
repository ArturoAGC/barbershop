<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook — Nueva reserva</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✂</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <nav class="bg-gray-900 border-b border-yellow-600 px-6 py-4 flex justify-between items-center">
        <span class="text-yellow-500 font-bold text-xl">BarberBook</span>
        <div class="flex gap-6 items-center">
            <a href="/client/dashboard" class="text-gray-300 hover:text-yellow-400 text-sm">Inicio</a>
            <a href="/client/my-reservations" class="text-gray-300 hover:text-yellow-400 text-sm">Mis reservas</a>
            <span class="text-gray-500 text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="/logout">
                @csrf
                <button class="text-sm text-red-400 hover:text-red-300">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8 max-w-2xl">
        <h1 class="text-2xl font-bold text-yellow-500 mb-8">Nueva reserva</h1>

        @if(session('error'))
            <div class="bg-red-900 text-red-300 px-4 py-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/client/booking" class="space-y-6">
            @csrf

            {{-- Servicio --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Servicio</label>
                <select name="service_id" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500">
                    <option value="">Selecciona un servicio</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} — ${{ number_format($service->price, 2) }} ({{ $service->duration_minutes }} min)
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estilista --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Estilista</label>
                <select name="barber_id" id="barber_id" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500">
                    <option value="">Selecciona un estilista</option>
                    @foreach($barbers as $barber)
                        <option value="{{ $barber->id }}" {{ old('barber_id') == $barber->id ? 'selected' : '' }}>
                            {{ $barber->name }} — {{ $barber->specialty }}
                        </option>
                    @endforeach
                </select>
                @error('barber_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Fecha</label>
                <input type="date" name="reservation_date" id="reservation_date"
                    value="{{ old('reservation_date') }}"
                    min="{{ date('Y-m-d') }}"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    required>
                @error('reservation_date')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Horario --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Horario disponible</label>
                <select name="reservation_time" id="reservation_time" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500">
                    <option value="">Selecciona estilista y fecha primero</option>
                </select>
                @error('reservation_time')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notas --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Notas adicionales (opcional)</label>
                <textarea name="notes" rows="3"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500"
                    placeholder="Alguna indicación especial...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-3 rounded-lg transition">
                Confirmar reserva
            </button>
        </form>
    </main>

    <script>
        const barberSelect = document.getElementById('barber_id');
        const dateInput = document.getElementById('reservation_date');
        const timeSelect = document.getElementById('reservation_time');

        function loadSlots() {
            const barberId = barberSelect.value;
            const date = dateInput.value;

            if (!barberId || !date) return;

            timeSelect.innerHTML = '<option value="">Cargando horarios...</option>';

            fetch(`/client/available-slots?barber_id=${barberId}&reservation_date=${date}`)
                .then(res => res.json())
                .then(slots => {
                    timeSelect.innerHTML = '<option value="">Selecciona un horario</option>';
                    slots.forEach(slot => {
                        if (slot.available) {
                            const opt = document.createElement('option');
                            opt.value = slot.value;
                            opt.textContent = slot.label;
                            timeSelect.appendChild(opt);
                        }
                    });
                    if (timeSelect.options.length === 1) {
                        timeSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                    }
                });
        }

        barberSelect.addEventListener('change', loadSlots);
        dateInput.addEventListener('change', loadSlots);
    </script>
</body>
</html>