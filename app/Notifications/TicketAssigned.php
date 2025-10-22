<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use App\Models\User;

class TicketAssigned extends Notification implements ShouldQueue
{
    use Queueable;
    protected $ticket;
    protected $assignedTo;
    public function __construct(Ticket $ticket, User $assignedTo)
    {
        $this->ticket = $ticket;
        $this->assignedTo = $assignedTo;
    }
    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->id} asignado")
            ->greeting("Hola {$notifiable->name},")
            ->line($this->getMessageLine($notifiable))
            ->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
            ->line('Si tienes alguna pregunta, no dudes en responder a este correo.');
    }
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'assigned_to' => $this->assignedTo->name,
            'action' => 'assigned'
        ];
    }
    protected function getMessageLine($notifiable)
    {
        if ($notifiable->id === $this->assignedTo->id) {
            return "Se te ha asignado el ticket: {$this->ticket->title}";
        } else {
            return "El ticket \"{$this->ticket->title}\" ha sido asignado a {$this->assignedTo->name}";
        }
    }
}
