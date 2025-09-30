<?php

namespace App\Http\Controllers;

use App\Models\HelpEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HelpEntryController extends Controller
{
    use AuthorizesRequests;

    // Mostrar centro de ayuda público
    public function index()
    {
        $entries = HelpEntry::where('status', 'aprobado')->latest()->get();
        return view('help.index', compact('entries'));
    }

    // Mostrar vista individual de artículo
    public function show(HelpEntry $entry)
    {
        if ($entry->status !== 'aprobado') {
            abort(404);
        }
        return view('help.show', compact('entry'));
    }

    // Formulario para crear nueva entrada
    public function create()
    {
        $this->authorize('create', HelpEntry::class);
        return view('help.create');
    }

    // Guardar nueva entrada
    public function store(Request $request)
    {
        $this->authorize('create', HelpEntry::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        HelpEntry::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'author_id' => Auth::id(),
            'status' => 'pendiente',
        ]);

        return redirect()->route('help.index')->with('success', 'Entrada enviada para revisión.');
    }

    // Panel de aprobación para administradores
    public function review()
    {
        $this->authorize('review', HelpEntry::class);
        $entries = HelpEntry::where('status', 'pendiente')->get();
        return view('help.review', compact('entries'));
    }

    // Aprobar o rechazar entrada
    public function updateStatus(Request $request, HelpEntry $entry)
    {
        $this->authorize('review', HelpEntry::class);

        $request->validate([
            'status' => 'required|in:aprobado,rechazado',
        ]);

        $entry->status = $request->status;
        $entry->save();

        return back()->with('success', 'Estado actualizado.');
    }
}
