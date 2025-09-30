<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $query = Computer::query();

        // Filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('computer_name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('processor')) {
            $query->where('processor', 'like', "%{$request->processor}%");
        }

        // Búsqueda por empresa
        if ($request->filled('company')) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->company}%");
            });
        }

        // Búsqueda por departamento
        if ($request->filled('department')) {
            $query->whereHas('department', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->department}%");
            });
        }

        $computers = $query->with(['company', 'department', 'user'])
                          ->orderBy('computer_name')
                          ->paginate(10)
                          ->withQueryString();

        return view('agent.computers.index', compact('computers'));
    }

    public function show(Computer $computer)
    {
        return view('agent.computers.show', compact('computer'));
    }

    public function edit(Computer $computer)
    {
        return view('agent.computers.edit', compact('computer'));
    }

    public function update(Request $request, Computer $computer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            // otros campos...
        ]);

        $computer->update($data);

        return redirect()->route('agent.computers.index')->with('success', 'Computadora actualizada.');
    }
}
