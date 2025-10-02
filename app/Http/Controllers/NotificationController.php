<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    public function markAsRead($id)
    {
        Auth::user()->notifications()->findOrFail($id)->markAsRead();
        return back()->with('success', 'Notificación marcada como leída');
    }

    public function delete($id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notificación eliminada');
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'Todas las notificaciones eliminadas');
    }
}
