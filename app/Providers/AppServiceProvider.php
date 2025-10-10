<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('role', CheckRole::class);

        // Registro de comandos de consola personalizados (si no hay Kernel dedicado)
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\SendTestEmail::class,
            ]);
        }
    }
}
