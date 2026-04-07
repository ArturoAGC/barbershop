<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active'        => 'boolean',
        ]);

        Service::create([
            'name'             => $request->name,
            'description'      => $request->description,
            'price'            => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active'        => 'boolean',
        ]);

        $service->update([
            'name'             => $request->name,
            'description'      => $request->description,
            'price'            => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'is_active'        => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Servicio eliminado correctamente.');
    }
}