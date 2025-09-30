<?php

namespace App\Http\Controllers;

use App\Models\HelpSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HelpSectionController extends Controller
{
    // Mostrar formulario de edición de sección
    public function edit($type)
    {
        if (!Gate::any(['admin', 'agent'])) {
            abort(403);
        }
        $section = HelpSection::where('type', $type)->first();
        return view('help.sections.edit', compact('section', 'type'));
    }

    // Actualizar sección
    public function update(Request $request, $type)
    {
        if (!Gate::any(['admin', 'agent'])) {
            abort(403);
        }
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);
        $section = HelpSection::where('type', $type)->first();
        if (!$section) {
            $section = HelpSection::create([
                'type' => $type,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);
        } else {
            $section->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);
        }
        return redirect()->route('help.index')->with('success', 'Sección actualizada correctamente.');
    }
}
