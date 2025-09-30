<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        // Verificar que el usuario solo pueda comentar en tickets que creó, a menos que sea admin o agente
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'agent' && $ticket->getAttribute('created_by') !== Auth::id()) {
            abort(403, 'No tienes permiso para comentar en este ticket.');
        }

        $request->validate([
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

            // Registrar acción en el historial
            \App\Models\TicketAction::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action_type' => 'comentario',
                'description' => 'Comentario agregado: ' . \Illuminate\Support\Str::limit($request->content, 50),
            ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-comments', 'public');
                $attachments[] = $path;
            }
        }

        // Crear el comentario asegurando que ticket_id esté presente
        $comment = new TicketComment();
        $comment->ticket_id = $ticket->id;
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->attachments = !empty($attachments) ? json_encode($attachments) : null;
        $comment->is_internal = false;
        $comment->save();

        // Actualizar la fecha de actualización del ticket
        $ticket->update(['updated_at' => now()]);

        return redirect()->back()->with('success', 'Comentario agregado correctamente.');
    }
}
