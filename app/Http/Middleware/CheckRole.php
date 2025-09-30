<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Asegurarse de que el usuario está autenticado
        if (!Auth::check()) {
            abort(403, 'Acceso no autorizado');
        }

        $user = Auth::user();

        // Verificar el rol directamente usando el atributo 'role', soportando múltiples roles
        if (is_array($roles) && in_array($user->role, $roles)) {
            return $next($request);
        }
        if ($user->role == $roles) {
            return $next($request);
        }

        // Si no coincide ningún rol, abortamos
        abort(403, 'Acceso no autorizado');
    }
}
