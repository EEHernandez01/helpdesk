<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth::user();

        if ($user && $user->role === 'admin') {
            $tickets = Ticket::orderBy('created_at', 'desc')->get();
            return redirect()->route('admin.dashboard');
        }

        if ($user && $user->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }
        if ($user && $user->role === 'employee') {
            $tickets = Ticket::where('created_by', Auth::id())->orderBy('created_at', 'desc')->get();
            return view('dashboard', compact('tickets'));
        }
    }
}
