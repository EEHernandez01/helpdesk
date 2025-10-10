<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // Marca como leída y redirige al recurso asociado (ticket)
    public function go($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $ticketId = $notification->data['ticket_id'] ?? null;
        if ($ticketId) {
            return redirect(url('/tickets/' . $ticketId));
        }

        return redirect()->route('dashboard');
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    public function markAsRead($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notifications()->findOrFail($id)->markAsRead();
        return back()->with('success', 'Notificación marcada como leída');
    }

    public function delete($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notificación eliminada');
    }

    public function deleteAll()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notifications()->delete();
        return back()->with('success', 'Todas las notificaciones eliminadas');
    }
}
