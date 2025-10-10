<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'mail:test {to : Email de destino} {--subject=Prueba SMTP Office 365 : Asunto del correo}';

    protected $description = 'Envía un correo de prueba usando la configuración SMTP actual';

    public function handle()
    {
        $to = $this->argument('to');
        $subject = $this->option('subject');

        try {
            Mail::raw('Este es un correo de prueba enviado por Laravel usando SMTP Office 365.', function ($m) use ($to, $subject) {
                $m->to($to)->subject($subject);
            });
            $this->info("Correo de prueba enviado a {$to}");
        } catch (\Throwable $e) {
            $this->error('Error enviando correo: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
