<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Console\Commands\SendTestEmail;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void
    {
        Route::aliasMiddleware('role', CheckRole::class);
        if ($this->app->runningInConsole()) {
            $this->commands([
                SendTestEmail::class,
            ]);
        }
    }
}
