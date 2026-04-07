@extends('layouts.app')

@section('title', 'Nuevo servicio')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">

    <div class="mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-sm text-gray-500 hover:text-yellow-500 transition">&larr; Volver</a>
        <h1 class="mt-2 text-2xl font-bold text-yellow-500">Nuevo servicio</h1>
    </div>

    @include('admin.partials.flash')

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm text-gray-400 mb-1" for="name">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-600 transition"
                   placeholder="Corte clásico">
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1" for="description">Descripción</label>
            <textarea name="description" id="description" rows="3"
                      class="w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-600 transition"
                      placeholder="Descripción breve del servicio">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1" for="price">Precio ($)</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                       class="w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-600 transition"
                       placeholder="80.00">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1" for="duration_minutes">Duración (min)</label>
                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}" min="1"
                       class="w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-600 transition"
                       placeholder="30">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', true) ? 'checked' : '' }}
                   class="w-4 h-4 accent-yellow-500 rounded">
            <label for="is_active" class="text-sm text-gray-400">Servicio activo</label>
        </div>

        <button type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-500 text-gray-950 font-semibold py-2.5 rounded-lg text-sm transition">
            Guardar servicio
        </button>
    </form>

</div>
@endsection