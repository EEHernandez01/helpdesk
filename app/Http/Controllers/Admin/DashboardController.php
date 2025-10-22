<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketFeedback;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets  = Ticket::count();

        $statusCounts = Ticket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        $nuevoTickets      = $statusCounts['nuevo'] ?? 0;
        $enProgresoTickets = $statusCounts['en progreso'] ?? 0;
        $resueltoTickets   = $statusCounts['resuelto'] ?? 0;
        $cerradoTickets    = $statusCounts['cerrado'] ?? 0;

        $onlineAgents = User::where('role', 'agent')
            ->where('status', 'active')
            ->count();

        $recentTickets = Ticket::with('assignedTo')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $recentFeedback = TicketFeedback::with(['ticket', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $avgRating = TicketFeedback::avg('rating');
        $feedbackCount = TicketFeedback::count();

        return view('admin.dashboard', compact(
            'totalTickets',
            'nuevoTickets',
            'enProgresoTickets',
            'resueltoTickets',
            'cerradoTickets',
            'onlineAgents',
            'recentTickets',
            'recentFeedback',
            'avgRating',
            'feedbackCount'
        ));
    }
}
