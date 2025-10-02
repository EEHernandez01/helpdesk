<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class NewTicketCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject("Nuevo Ticket #{$this->ticket->id} creado")
            ->greeting("Hola {$notifiable->name},");

        // Si el notifiable es el creador
        if ($notifiable->id === $this->ticket->created_by) {
            $message->line("Tu ticket ha sido creado exitosamente:")
                   ->line("Título: {$this->ticket->title}")
                   ->line("Prioridad: {$this->ticket->priority}");
        } else {
            // Si es un administrador o agente
            $message->line("Se ha creado un nuevo ticket:")
                   ->line("Título: {$this->ticket->title}")
                   ->line("Creado por: {$this->ticket->creator->name}")
                   ->line("Prioridad: {$this->ticket->priority}");
        }

        return $message->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
                      ->line('Si tienes alguna pregunta, no dudes en responder a este correo.');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'created_by' => $this->ticket->creator->name,
            'priority' => $this->ticket->priority,
            'action' => 'created'
        ];
    }
}
