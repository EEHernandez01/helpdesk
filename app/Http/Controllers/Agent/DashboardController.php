<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $assignedTickets = Ticket::where('assigned_to', $user->id)
            ->with('creator', 'department', 'category', 'comments')
            ->latest()
            ->get();

            $soonDueTickets = $assignedTickets->filter(function($ticket) {
                return isset($ticket->due_date) && $ticket->status !== 'cerrado' && now()->diffInHours($ticket->due_date, false) <= 24 && now()->lt($ticket->due_date);
            });

            $staleTickets = $assignedTickets->filter(function($ticket) {
                return $ticket->status !== 'cerrado' && $ticket->updated_at->diffInHours(now()) > 48;
            });

        $availableTickets = Ticket::whereNull('assigned_to')
            ->where('status', '!=', 'cerrado')
            ->with('creator', 'department', 'category', 'comments')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        $assignedCount = $assignedTickets->where('status', 'abierto')->count();
        $inProgressCount = $assignedTickets->where('status', 'en progreso')->count();
        $closedCount = $assignedTickets->where('status', 'cerrado')->count();
        $avgResponse = '--';

        $urgentCount = $assignedTickets->where('priority', 'alta')->where('status', 'abierto')->count();

        return view('agent.dashboard', compact(
            'assignedTickets',
            'availableTickets',
            'assignedCount',
            'inProgressCount',
            'closedCount',
            'avgResponse',
            'urgentCount'
                ,'soonDueTickets'
                ,'staleTickets'
        ));
    }
}
