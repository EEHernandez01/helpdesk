<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HelpEntry;

class HelpEntryPolicy
{
    // Solo agentes y administradores pueden crear
    public function create(User $user)
    {
        return $user->hasRole('agent') || $user->hasRole('admin');
    }

    // Solo administradores pueden aprobar/rechazar
    public function review(User $user)
    {
        return $user->hasRole('admin');
    }
}
