<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketFeedback;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = TicketFeedback::with(['ticket', 'user']);
        if ($request->filled('agent_id')) {
            $query->whereHas('ticket', function ($q) use ($request) {
                $q->where('assigned_to', $request->agent_id);
            });
        }
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->to);
        }

        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(20);
        $agents = User::where('role', 'agent')->get();
        $avgRating = $query->avg('rating');
        $feedbackCount = $query->count();

        return view('admin.feedback.index', compact('feedbacks', 'agents', 'avgRating', 'feedbackCount'));
    }

    public function export(Request $request)
    {
        $query = TicketFeedback::with(['ticket', 'user']);
        if ($request->filled('agent_id')) {
            $query->whereHas('ticket', function ($q) use ($request) {
                $q->where('assigned_to', $request->agent_id);
            });
        }
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->to);
        }
        $feedbacks = $query->orderBy('created_at', 'desc')->get();

        $csv = "Ticket,Usuario,Calificación,Comentario,Fecha\n";
        foreach ($feedbacks as $f) {
            $csv .= "#{$f->ticket->id},{$f->user->username},{$f->rating},\"{$f->comment}\",{$f->created_at->format('d/m/Y H:i')}\n";
        }
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=feedback.csv');
    }
}
