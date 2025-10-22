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
        return ['database', 'mail', 'broadcast'];
    }
    public function toMail($notifiable)
    {
        $statusTranslations = [
            'nuevo' => 'Nuevo',
            'en progreso' => 'En Progreso',
            'resuelto' => 'Resuelto',
            'cerrado' => 'Cerrado'
        ];

        $prev = $statusTranslations[$this->previousStatus] ?? ucfirst($this->previousStatus);
        $next = $statusTranslations[$this->ticket->status] ?? ucfirst($this->ticket->status);

        return (new MailMessage)
            ->subject("Actualización del Ticket #{$this->ticket->id}")
            ->markdown('emails.tickets.status_changed', [
                'notifiable' => $notifiable,
                'ticket' => $this->ticket,
                'prev' => $prev,
                'next' => $next,
                'url' => url("/tickets/{$this->ticket->id}")
            ]);
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
