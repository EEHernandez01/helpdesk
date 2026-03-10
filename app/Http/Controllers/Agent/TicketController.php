<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketAction;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $assignedTickets = Ticket::where('assigned_to', $user->id)
            ->with('creator', 'department', 'category', 'comments')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.tickets.index', compact('assignedTickets'));
    }

    public function show(Ticket $ticket)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role != 'agent' && $ticket->assigned_to != $user->id) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }
        $ticket->load('creator', 'assignedTo', 'department', 'category', 'comments.user');
        $actions = TicketAction::where('ticket_id', $ticket->id)->orderBy('created_at', 'desc')->get();
        return view('agent.tickets.show', compact('ticket', 'actions'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ((int)$ticket->assigned_to !== (int)Auth::id()) {
            abort(403, 'Solo puedes actualizar tickets que te han sido asignados.');
        }

        $request->validate([
            'status' => 'required|in:abierto,en progreso,resuelto,cerrado',
            'resolution_notes' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'resolved_at' => $request->status === 'resuelto' ? now() : null,
        ]);

        TicketAction::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action_type' => 'estado actualizado',
            'description' => 'Estado cambiado a ' . $request->status,
        ]);

        return redirect()
            ->route('agent.tickets.show', $ticket)
            ->with('success', 'Ticket actualizado correctamente.');
    }

    public function available()
    {
        $availableTickets = Ticket::whereNull('assigned_to')
            ->where('status', '!=', 'cerrado')
            ->with('creator', 'department', 'category', 'comments')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('agent.tickets.available', compact('availableTickets'));
    }

    public function assign(Ticket $ticket)
    {
        if ($ticket->assigned_to) {
            return back()->with('error', 'Este ticket ya está asignado a otro agente.');
        }

        try {
            \DB::beginTransaction();

            $ticket->update([
                'assigned_to' => Auth::id(),
                'status' => 'en progreso',
            ]);

            TicketAction::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action_type' => 'asignado',
                'description' => 'Ticket asignado al agente',
            ]);

            \DB::commit();

            return redirect()
                ->route('agent.tickets.show', $ticket)
                ->with('success', 'Ticket asignado correctamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al asignar ticket: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al asignar el ticket. Intenta de nuevo.');
        }
    }

    public function next()
    {
        try {
            \DB::beginTransaction();

            $ticket = Ticket::whereNull('assigned_to')
                ->where('status', '!=', 'cerrado')
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$ticket) {
                \DB::rollBack();
                return back()->with('error', 'No hay tickets disponibles para asignar.');
            }

            $ticket->update([
                'assigned_to' => Auth::id(),
                'status' => 'en progreso',
            ]);

            TicketAction::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action_type' => 'asignado',
                'description' => 'Ticket asignado automáticamente al agente',
            ]);

            \DB::commit();

            return redirect()
                ->route('agent.tickets.show', $ticket)
                ->with('success', 'Ticket asignado automáticamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al asignar siguiente ticket: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al asignar el ticket. Intenta de nuevo.');
        }
    }

    public function release(Ticket $ticket)
    {
        if ($ticket->assigned_to != Auth::id()) {
            abort(403, 'No tienes acceso a este ticket.');
        }

        try {
            \DB::beginTransaction();

            $ticket->update([
                'assigned_to' => null,
                'status' => 'abierto',
            ]);

            TicketAction::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action_type' => 'liberado',
                'description' => 'Ticket liberado por el agente',
            ]);

            \DB::commit();

            return redirect()
                ->route('agent.tickets.index')
                ->with('success', 'Ticket liberado correctamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al liberar ticket: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al liberar el ticket. Intenta de nuevo.');
        }
    }

    public function addToPending(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|string',
        ]);

        $ticketIds = json_decode($request->ticket_ids, true);

        if (!is_array($ticketIds)) {
            return back()->with('error', 'Formato de datos inválido.');
        }

        try {
            \DB::beginTransaction();

            $tickets = Ticket::whereIn('id', $ticketIds)
                ->whereNull('assigned_to')
                ->where('status', '!=', 'cerrado')
                ->get();

            if ($tickets->isEmpty()) {
                \DB::rollBack();
                return back()->with('error', 'No se encontraron tickets válidos para agregar a pendientes.');
            }

            foreach ($tickets as $ticket) {
                $ticket->update([
                    'assigned_to' => Auth::id(),
                    'status' => 'abierto',
                ]);

                TicketAction::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'action_type' => 'agregado a pendientes',
                    'description' => 'Ticket agregado a pendientes por asignación en lote',
                ]);
            }

            \DB::commit();

            $count = $tickets->count();
            return redirect()
                ->route('agent.tickets.index')
                ->with('success', "{$count} tickets agregados a tus pendientes correctamente.");
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al agregar tickets a pendientes: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al procesar los tickets. Intenta de nuevo.');
        }
    }

    public function assignMultiple(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|string',
        ]);

        $ticketIds = json_decode($request->ticket_ids, true);

        if (!is_array($ticketIds)) {
            return back()->with('error', 'Formato de datos inválido.');
        }

        try {
            \DB::beginTransaction();

            $tickets = Ticket::whereIn('id', $ticketIds)
                ->whereNull('assigned_to')
                ->where('status', '!=', 'cerrado')
                ->get();

            if ($tickets->isEmpty()) {
                \DB::rollBack();
                return back()->with('error', 'No se encontraron tickets válidos para asignar.');
            }

            foreach ($tickets as $ticket) {
                $ticket->update([
                    'assigned_to' => Auth::id(),
                    'status' => 'en progreso',
                ]);

                TicketAction::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'action_type' => 'asignado',
                    'description' => 'Ticket asignado al agente por asignación en lote',
                ]);
            }

            \DB::commit();

            $count = $tickets->count();
            return redirect()
                ->route('agent.tickets.index')
                ->with('success', "{$count} tickets asignados correctamente.");
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al asignar tickets en lote: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al procesar los tickets. Intenta de nuevo.');
        }
    }
}
