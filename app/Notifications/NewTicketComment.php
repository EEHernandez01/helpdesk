<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use App\Models\TicketComment;

class NewTicketComment extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $comment;

    public function __construct(Ticket $ticket, TicketComment $comment)
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        $channels = ['database', 'mail', 'broadcast'];
        if (!empty($this->skipDatabase)) {
            return array_values(array_diff($channels, ['database']));
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Nuevo comentario en Ticket #{$this->ticket->id}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Se ha añadido un nuevo comentario al ticket: {$this->ticket->title}")
            ->line("Comentario de {$this->comment->user->name}:")
            ->line("\"{$this->comment->content}\"")
            ->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
            ->line('Si tienes alguna pregunta, no dudes en responder a este correo.');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'comment_by' => $this->comment->user->name,
            'comment' => $this->comment->content,
            'action' => 'commented'
        ];
    }
}
