<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use App\Http\Middleware\CheckRole;
use App\Console\Commands\SendTestEmail;
use App\Mail\Transport\GraphTransport;
use App\Services\GraphMailer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void
    {
        Route::aliasMiddleware('role', CheckRole::class);
        // Registrar el transport 'graph' para usar Microsoft Graph como mailer
        Mail::extend('graph', function ($app, $config = []) {
            // Permitir que GraphMailer construya su propio Client con manejo de SSL/CA
            $graphMailer = new GraphMailer();
            return new GraphTransport($graphMailer);
        });
        if ($this->app->runningInConsole()) {
            $this->commands([
                SendTestEmail::class,
            ]);
        }
    }
}
