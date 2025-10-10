<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Department;
use App\Models\Category;

class TicketController extends Controller
{
    // Mostrar solo los tickets del usuario autenticado
    public function index()
    {
        $query = Ticket::with('creator', 'assignedTo', 'department', 'category');

        // Filtrar según el rol del usuario
        if (Auth::user()->role === 'admin') {
            // Administradores ven todos los tickets
            $tickets = $query;
        } elseif (Auth::user()->role === 'agent') {
            // Agentes ven tickets asignados a ellos y los que han creado
            $tickets = $query->where(function($q) {
                $q->where('assigned_to', Auth::id())
                  ->orWhere('created_by', Auth::id());
            });
        } else {
            // Usuarios normales solo ven sus tickets creados
            $tickets = $query->where('created_by', Auth::id());
        }

        $tickets = $tickets->orderBy('created_at', 'desc')->get();
        return view('tickets.index', compact('tickets'));
    }

    // Mostrar formulario de creación de ticket
    public function create()
    {
        $departments = Department::all();
        $categories = Category::all();

        return view('tickets.create', compact('departments', 'categories'));
    }

    // Guardar un nuevo ticket
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'priority'      => 'required|in:baja,media,alta,urgente',
            'department_id' => 'nullable|exists:departments,id',
            'category_id'   => 'nullable|exists:categories,id',
            'assigned_to'   => 'nullable|exists:users,id',
            'attachments'   => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // 1) Crear ticket inicial sin attachments
        $ticket = Ticket::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'status'        => 'nuevo',
            'priority'      => $request->priority,
            'department_id' => $request->department_id,
            'category_id'   => $request->category_id,
            'assigned_to'   => $request->assigned_to,
            'created_by'    => Auth::id(),
            'attachments'   => null,
        ]);

        // Notificar solo a los administradores de un nuevo ticket
        $admins = \App\Models\User::where('role', 'admin')
                                 ->where('id', '!=', Auth::id())
                                 ->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewTicketCreated($ticket));
        }

        // Notificar a los agentes disponibles
        $agents = \App\Models\User::where('role', 'agent')->get();
        foreach ($agents as $agent) {
            $agent->notify(new \App\Notifications\NewTicketCreated($ticket));
        }

        // Notificar también al creador (confirmación del ticket creado)
        if ($ticket->creator()->exists()) {
            $ticket->creator()->first()->notify(new \App\Notifications\NewTicketCreated($ticket));
        }

        $attachmentPaths = [];

        // 2) Guardar archivos en carpeta específica del ticket
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (! $file->isValid()) {
                    Log::error("Archivo no válido: " . $file->getClientOriginalName() . " - Error: " . $file->getErrorMessage());
                    continue;
                }
                $path = $file->store("tickets/{$ticket->id}", 'public');
                $attachmentPaths[] = $path;
            }

            // 3) Actualizar el ticket con rutas de attachments
            $ticket->attachments = json_encode($attachmentPaths);
            $ticket->save();
        }

        Log::info('Ticket creado con ID: ' . $ticket->id);
        Log::info('Attachments guardados: ' . ($ticket->attachments ?? 'ninguno'));

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket creado correctamente.');
    }

    // Mostrar detalles de un ticket (solo si el usuario lo creó o es admin/agente)
    public function show($id)
    {
        $ticket = Ticket::with('creator', 'assignedTo', 'department', 'category')->findOrFail($id);

        // Admin y agentes pueden ver todos los tickets
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'agent') {
            // No necesita verificación
        }
        // Usuario normal solo puede ver sus propios tickets
        elseif ($ticket->created_by == Auth::id()) {
            // Permitido
        }
        else {
            abort(403, 'No tienes permiso para ver este ticket.');
        }

        return view('tickets.show', compact('ticket'));
    }

    // Mostrar formulario de edición (solo si el usuario lo creó o es admin/agente)
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Admin puede editar todos los tickets
        if (Auth::user()->role === 'admin') {
            // No necesita verificación
        }
        // Agente puede editar tickets asignados a él o que él creó
        elseif (Auth::user()->role === 'agent' && ($ticket->assigned_to === Auth::id() || $ticket->created_by === Auth::id())) {
            // Permitido
        }
        // Usuario normal solo puede editar sus propios tickets si están en estado "nuevo"
        elseif ($ticket->created_by === Auth::id() && $ticket->status === 'nuevo') {
            // Permitido
        }
        else {
            abort(403, 'No tienes permiso para editar este ticket.');
        }

        $departments = Department::all();
        $categories = Category::all();
        return view('tickets.edit', compact('ticket', 'departments', 'categories'));
    }

    // Actualizar un ticket
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'status'            => 'required|in:nuevo,en progreso,resuelto,cerrado',
            'priority'          => 'required|in:baja,media,alta,urgente',
            'department_id'     => 'nullable|exists:departments,id',
            'category_id'       => 'nullable|exists:categories,id',
            'assigned_to'       => 'nullable|exists:users,id',
            'attachments.*'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'resolution_notes'  => 'nullable|string',
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'string',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Admin puede actualizar todos los tickets
        if (Auth::user()->role === 'admin') {
            // No necesita verificación
        }
        // Agente puede actualizar tickets asignados a él o que él creó
        elseif (Auth::user()->role === 'agent' && ($ticket->assigned_to === Auth::id() || $ticket->created_by === Auth::id())) {
            // Permitido
        }
        // Usuario normal solo puede actualizar sus propios tickets si están en estado "nuevo"
        elseif ($ticket->created_by === Auth::id() && $ticket->status === 'nuevo') {
            // Permitido
        }
        else {
            abort(403, 'No tienes permiso para actualizar este ticket.');
        }

        // Obtener archivos actuales
        $currentAttachments = json_decode($ticket->attachments ?: '[]', true);

        // Procesar eliminaciones
        if ($request->has('remove_attachments')) {
            foreach ($request->remove_attachments as $path) {
                Storage::disk('public')->delete($path);
                if (($index = array_search($path, $currentAttachments)) !== false) {
                    unset($currentAttachments[$index]);
                }
            }
            $currentAttachments = array_values($currentAttachments);
        }

        // Añadir nuevos archivos en carpeta del ticket
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (! $file->isValid()) continue;
                $currentAttachments[] = $file->store("tickets/{$ticket->id}", 'public');
            }
        }

        // Guardar el estado anterior
        $previousStatus = $ticket->status;

        // Si cambia a resuelto y no tiene fecha, guardarla
        if ($request->status === 'resuelto' && ! $ticket->resolved_at) {
            $ticket->resolved_at = now();
        }

        $previousAssignedTo = $ticket->assigned_to;

        // Actualizar resto de campos
        $ticket->update([
            'title'            => $request->title,
            'description'      => $request->description,
            'status'           => $request->status,
            'priority'         => $request->priority,
            'department_id'    => $request->department_id,
            'category_id'      => $request->category_id,
            'assigned_to'      => $request->assigned_to,
            'resolution_notes' => $request->resolution_notes,
            'attachments'      => json_encode($currentAttachments),
        ]);

        // Si el estado cambió, notificar a los involucrados
        if ($previousStatus !== $request->status) {
            // Notificar al creador si no es quien hizo el cambio
            if ($ticket->creator()->exists() && $ticket->created_by !== Auth::id()) {
                $ticket->creator()->first()->notify(new \App\Notifications\TicketStatusChanged($ticket, $previousStatus));
            }

            // Notificar al agente asignado si existe y no es quien hizo el cambio
            if ($ticket->assignedTo()->exists() && $ticket->assigned_to !== Auth::id()) {
                $ticket->assignedTo()->first()->notify(new \App\Notifications\TicketStatusChanged($ticket, $previousStatus));
            }

            // Si el cambio lo hizo un usuario normal, notificar a los administradores
            if (Auth::user()->role === 'user') {
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\TicketStatusChanged($ticket, $previousStatus));
                }
            }
        }

        // Si se asignó a un nuevo agente
        if ($previousAssignedTo !== $request->assigned_to && $request->assigned_to) {
            $assignedUser = \App\Models\User::find($request->assigned_to);
            if ($assignedUser) {
                // Notificar al nuevo agente asignado
                $assignedUser->notify(new \App\Notifications\TicketAssigned($ticket, $assignedUser));

                // Notificar al creador si no es quien hizo la asignación
                if ($ticket->creator()->exists() && $ticket->created_by !== Auth::id()) {
                    $ticket->creator()->first()->notify(new \App\Notifications\TicketAssigned($ticket, $assignedUser));
                }
            }
        }

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket actualizado correctamente.');
    }

    // Eliminar un ticket
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Admin puede eliminar cualquier ticket
        if (Auth::user()->role === 'admin') {
            // Permitido
        }
        // Agente puede eliminar tickets que él creó o que tiene asignados
        elseif (Auth::user()->role === 'agent' && ($ticket->assigned_to === Auth::id() || $ticket->created_by === Auth::id())) {
            // Permitido
        }
        // Usuario normal solo puede eliminar sus propios tickets en estado "nuevo"
        elseif ($ticket->created_by === Auth::id() && $ticket->status === 'nuevo') {
            // Permitido
        }
        else {
            abort(403, 'No tienes permiso para eliminar este ticket.');
        }

        // Eliminar carpeta entera de attachments
        Storage::disk('public')->deleteDirectory("tickets/{$ticket->id}");

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket eliminado correctamente.');
    }

    // Asignar siguiente ticket disponible
    public function next()
    {
        $ticket = Ticket::whereNull('assigned_to')
            ->orderBy('created_at')
            ->first();

        if (! $ticket) {
            return back()->with('error', 'No hay tickets disponibles para asignar.');
        }

        $ticket->update([
            'assigned_to' => Auth::id(),
            'status'      => 'en progreso',
        ]);

        return redirect()
            ->route('agent.tickets.show', $ticket)
            ->with('success', 'Ticket asignado correctamente.');
    }

    // Asignar ticket a un agente específico
    public function assign(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Verificar si el usuario es administrador
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permiso para asignar tickets.');
        }

        $request->validate([
            'agent_id' => 'required|exists:users,id'
        ]);

        $ticket->update([
            'assigned_to' => $request->agent_id,
            'status' => 'en progreso'
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket asignado correctamente.');
    }
}
