@component('mail::message')
# Actualización del Ticket #{{ $ticket->id }}

Hola {{ $notifiable->name }},

El ticket “{{ $ticket->title }}” cambió de estado:

@component('mail::panel')
<strong>De:</strong> {{ $prev }} &nbsp; → &nbsp; <strong>A:</strong> {{ $next }}
@endcomponent

@component('mail::button', ['url' => $url])
Ver Ticket
@endcomponent

Si tienes dudas, responde a este correo y con gusto te ayudamos.

Gracias,
{{ config('app.name') }}
@endcomponent
