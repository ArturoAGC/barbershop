@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-yellow-500">Servicios</h1>
        <a href="{{ route('admin.services.create') }}"
           class="bg-yellow-600 hover:bg-yellow-500 text-gray-950 font-semibold text-sm px-4 py-2 rounded-lg transition">
            Nuevo servicio
        </a>
    </div>

    @include('admin.partials.flash')

    <div class="overflow-x-auto rounded-xl border border-gray-800">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs uppercase tracking-wider text-gray-500 bg-gray-900 border-b border-gray-800">
                <tr>
                    <th class="px-5 py-3">Nombre</th>
                    <th class="px-5 py-3">Precio</th>
                    <th class="px-5 py-3">Duración</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800 bg-gray-950">
                @forelse ($services as $service)
                <tr class="hover:bg-gray-900 transition">
                    <td class="px-5 py-4 font-medium text-white">{{ $service->name }}</td>
                    <td class="px-5 py-4">${{ number_format($service->price, 2) }}</td>
                    <td class="px-5 py-4">{{ $service->duration_minutes }} min</td>
                    <td class="px-5 py-4">
                        @if ($service->is_active)
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-green-900/50 text-green-400 border border-green-700">Activo</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-800 text-gray-500 border border-gray-700">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right space-x-2">
                        <a href="{{ route('admin.services.edit', $service) }}"
                           class="text-yellow-500 hover:text-yellow-400 text-xs font-medium transition">Editar</a>

                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Eliminar este servicio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-500 hover:text-red-400 text-xs font-medium transition">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-600">No hay servicios registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $services->links() }}
    </div>

</div>
@endsection