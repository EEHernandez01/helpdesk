<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HelpEntry;

class HelpEntryPolicy
{
    public function create(User $user)
    {
        return $user->hasRole('agent') || $user->hasRole('admin');
    }
    public function review(User $user)
    {
        return $user->hasRole('admin');
    }
}
