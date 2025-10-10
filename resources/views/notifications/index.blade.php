@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Mis notificaciones</h1>

    <div class="bg-white shadow rounded divide-y">
        @forelse($notifications as $notification)
            @php($data = $notification->data ?? [])
            <div class="p-4 flex items-start {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                <div class="flex-1">
                    <a class="text-sm text-gray-800 hover:underline" href="{{ route('notifications.go', $notification->id) }}">
                        @if($notification->type === 'App\\Notifications\\TicketStatusChanged')
                            El ticket #{{ $data['ticket_id'] ?? '?' }} "{{ $data['title'] ?? '' }}"
                            cambió de {{ $data['previous_status'] ?? '?' }} a {{ $data['new_status'] ?? '?' }}
                        @elseif($notification->type === 'App\\Notifications\\TicketAssigned')
                            El ticket #{{ $data['ticket_id'] ?? '?' }} "{{ $data['title'] ?? '' }}"
                            fue asignado a {{ $data['assigned_to'] ?? 'desconocido' }}
                        @elseif($notification->type === 'App\\Notifications\\NewTicketComment')
                            Nuevo comentario en el ticket #{{ $data['ticket_id'] ?? '?' }} por {{ $data['comment_by'] ?? 'alguien' }}
                        @elseif($notification->type === 'App\\Notifications\\NewTicketCreated')
                            Nuevo ticket #{{ $data['ticket_id'] ?? '?' }}: "{{ $data['title'] ?? '' }}"
                        @else
                            Notificación
                        @endif
                    </a>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="flex items-center space-x-2 ml-2">
                    @unless($notification->read_at)
                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800" title="Marcar como leída">
                                <i class="fas fa-check text-sm"></i>
                            </button>
                        </form>
                    @endunless
                    <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar notificación">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">No tienes notificaciones</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
