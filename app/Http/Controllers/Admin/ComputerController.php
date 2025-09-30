<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computer;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $query = Computer::query();
        // Filtros básicos
        if ($request->filled('search')) {
            $query->where('computer_name', 'like', '%'.$request->search.'%')
                  ->orWhere('serial_number', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('model')) {
            $query->where('model', 'like', '%'.$request->model.'%');
        }
        $computers = $query->with(['user', 'department'])->paginate(20);
        return view('admin.computers.index', compact('computers'));
    }

    public function create()
    {
        return view('admin.computers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'computer_name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'operating_system' => 'nullable|string|max:255',
        ]);
        Computer::create($request->all());
        return redirect()->route('admin.computers.index')->with('success', 'Equipo creado correctamente.');
    }

    public function show(Computer $computer)
    {
        return view('admin.computers.show', compact('computer'));
    }

    public function edit(Computer $computer)
    {
        return view('admin.computers.edit', compact('computer'));
    }

    public function update(Request $request, Computer $computer)
    {
        $request->validate([
            'computer_name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'operating_system' => 'nullable|string|max:255',
        ]);
        $computer->update($request->all());
        return redirect()->route('admin.computers.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Computer $computer)
    {
        $computer->delete();
        return redirect()->route('admin.computers.index')->with('success', 'Equipo eliminado correctamente.');
    }
}
