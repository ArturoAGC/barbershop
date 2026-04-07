<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::orderBy('name')->paginate(10);
        return view('admin.barbers.index', compact('barbers'));
    }

    public function create()
    {
        return view('admin.barbers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'bio'       => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Barber::create([
            'name'      => $request->name,
            'specialty' => $request->specialty,
            'bio'       => $request->bio,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.barbers.index')
                         ->with('success', 'Estilista creado correctamente.');
    }

    public function edit(Barber $barber)
    {
        return view('admin.barbers.edit', compact('barber'));
    }

    public function update(Request $request, Barber $barber)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'bio'       => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $barber->update([
            'name'      => $request->name,
            'specialty' => $request->specialty,
            'bio'       => $request->bio,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.barbers.index')
                         ->with('success', 'Estilista actualizado correctamente.');
    }

    public function destroy(Barber $barber)
    {
        $barber->delete();

        return redirect()->route('admin.barbers.index')
                         ->with('success', 'Estilista eliminado correctamente.');
    }
}