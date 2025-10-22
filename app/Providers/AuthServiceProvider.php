<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\HelpEntry;
use App\Policies\HelpEntryPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        HelpEntry::class => HelpEntryPolicy::class,
    ];
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
