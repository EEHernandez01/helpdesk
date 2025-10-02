<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $previousStatus;

    public function __construct(Ticket $ticket, $previousStatus)
    {
        $this->ticket = $ticket;
        $this->previousStatus = $previousStatus;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $statusTranslations = [
            'nuevo' => 'Nuevo',
            'en progreso' => 'En Progreso',
            'resuelto' => 'Resuelto',
            'cerrado' => 'Cerrado'
        ];

        return (new MailMessage)
            ->subject("Actualización del Ticket #{$this->ticket->id}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Tu ticket ha sido actualizado:")
            ->line("Título: {$this->ticket->title}")
            ->line("Estado anterior: {$statusTranslations[$this->previousStatus]}")
            ->line("Nuevo estado: {$statusTranslations[$this->ticket->status]}")
            ->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
            ->line('Si tienes alguna pregunta, no dudes en responder a este correo.');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->ticket->status
        ];
    }
}
