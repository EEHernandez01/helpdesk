<x-dropdown align="right" width="96">
    <x-slot name="trigger">
        <button class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
            <i class="fas fa-bell text-xl"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="p-2">
            <h3 class="text-lg font-semibold text-gray-700 px-4 py-2">Notificaciones</h3>

            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse(auth()->user()->notifications as $notification)
                    <div class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50">
                        <div class="flex justify-between">
                            <a href="{{ url('/tickets/' . $notification->data['ticket_id']) }}" class="flex-grow">
                                <p class="text-sm text-gray-800">
                                    @if($notification->type === 'App\Notifications\TicketStatusChanged')
                                        El ticket #{{ $notification->data['ticket_id'] }} "{{ $notification->data['title'] }}"
                                        ha cambiado de estado: {{ $notification->data['previous_status'] }} → {{ $notification->data['new_status'] }}
                                    @elseif($notification->type === 'App\Notifications\TicketAssigned')
                                        El ticket #{{ $notification->data['ticket_id'] }} "{{ $notification->data['title'] }}"
                                        ha sido asignado a {{ $notification->data['assigned_to'] }}
                                    @elseif($notification->type === 'App\Notifications\NewTicketComment')
                                        Nuevo comentario en el ticket #{{ $notification->data['ticket_id'] }} por {{ $notification->data['comment_by'] }}
                                    @elseif($notification->type === 'App\Notifications\NewTicketCreated')
                                        Nuevo ticket #{{ $notification->data['ticket_id'] }} creado: "{{ $notification->data['title'] }}"
                                        {!! auth()->id() === $notification->data['created_by'] ? '' : ' por ' . $notification->data['created_by'] !!}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </a>
                            <div class="flex items-start space-x-2 ml-2">
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
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        <p>No hay notificaciones</p>
                    </div>
                @endforelse
            </div>

            @if(auth()->user()->notifications->count() > 0)
                <div class="border-t border-gray-200 px-4 py-2 flex justify-between">
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                            Marcar todas como leídas
                        </button>
                    </form>
                    <form action="{{ route('notifications.delete-all') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Eliminar todas
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>
</x-dropdown>
