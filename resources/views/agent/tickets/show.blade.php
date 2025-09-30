<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-6xl mx-auto px-4 space-y-6">
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('agent.tickets.index') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Ticket #{{ $ticket->id }}
                        </h1>
                    </div>
                    <p class="text-gray-600">{{ $ticket->title }}</p>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('agent.tickets.release', $ticket) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-red-500 text-white hover:bg-red-600 transition shadow-lg"
                            onclick="return confirm('¿Estás seguro de que quieres liberar este ticket?')">
                            <i class="fas fa-unlock"></i> Liberar Ticket
                        </button>
                    </form>
                    <a href="{{ route('agent.tickets.index') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gray-500 text-white hover:bg-gray-600 transition shadow-lg">
                        <i class="fas fa-list"></i> Mis Tickets
                    </a>
                </div>
            </div>

            <!-- Información del ticket -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Detalles principales -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Descripción -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-file-alt text-blue-500"></i>
                            Descripción
                        </h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-comments text-green-500"></i>
                            Comentarios ({{ $ticket->comments->count() }})
                        </h3>

                        @if($ticket->comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($ticket->comments as $comment)
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr($comment->user->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-gray-700">{{ $comment->user->username ?? 'Usuario' }}</span>
                                    <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700">{{ $comment->content }}</p>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 italic">No hay comentarios aún.</p>
                        @endif
                        <!-- Feedback al cerrar el ticket -->
                        @if($ticket->status === 'cerrado' && Auth::id() === $ticket->creator->id)
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mt-6">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-star text-yellow-500"></i>
                                Califica la atención recibida
                            </h3>
                            <form action="{{ route('tickets.feedback.store', $ticket) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Calificación</label>
                                    <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                        <option value="">Selecciona...</option>
                                        @for($i=1; $i<=5; $i++)
                                            <option value="{{ $i }}">{{ $i }} estrella{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Comentario</label>
                                    <textarea name="comment" rows="3" placeholder="¿Cómo fue tu experiencia?" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                                </div>
                                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-semibold">
                                    <i class="fas fa-paper-plane mr-2"></i>Enviar Feedback
                                </button>
                            </form>
                        </div>
                        @endif

                        <!-- Formulario para nuevo comentario -->
                        <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST" class="mt-6">
                            @csrf
                            <div class="space-y-3">
                                <textarea name="content" rows="3" placeholder="Escribe un comentario..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                    <i class="fas fa-paper-plane mr-2"></i>Agregar Comentario
                                </button>
                            </div>
                        </form>

                        <!-- Historial de acciones -->
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mt-6">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-history text-purple-500"></i>
                                Historial de acciones
                            </h3>
                            @if(isset($actions) && $actions->count())
                            <ul class="space-y-2">
                                @foreach($actions as $action)
                                <li class="border-l-4 border-purple-400 pl-4 py-2">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-gray-700">{{ $action->user->username ?? 'Usuario' }}</span>
                                        <span class="text-xs text-gray-500">{{ $action->created_at->diffForHumans() }}</span>
                                    </div>
                                    <span class="text-gray-600">{{ ucfirst($action->action_type) }}:</span>
                                    <span class="text-gray-800">{{ $action->description }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-gray-500 italic">No hay acciones registradas aún.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="space-y-6">
                    <!-- Estado y prioridad -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-cog text-purple-500"></i>
                            Estado del Ticket
                        </h3>

                        <form action="{{ route('agent.tickets.update', $ticket) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="abierto" {{ $ticket->status === 'abierto' ? 'selected' : '' }}>Abierto</option>
                                    <option value="en progreso" {{ $ticket->status === 'en progreso' ? 'selected' : '' }}>En Progreso</option>
                                    <option value="resuelto" {{ $ticket->status === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                    <option value="cerrado" {{ $ticket->status === 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notas de Resolución</label>
                                <textarea name="resolution_notes" rows="4" placeholder="Agrega notas sobre la resolución..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ $ticket->resolution_notes }}</textarea>
                            </div>

                            <button type="submit" class="w-full px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                                <i class="fas fa-save mr-2"></i>Actualizar Estado
                            </button>
                        </form>
                    </div>

                    <!-- Información del ticket -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Información
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Prioridad:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($ticket->priority === 'alta') bg-red-100 text-red-800
                                    @elseif($ticket->priority === 'media') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Departamento:</span>
                                <span class="text-gray-800">{{ $ticket->department->name ?? 'Sin departamento' }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Categoría:</span>
                                <span class="text-gray-800">{{ $ticket->category->name ?? 'Sin categoría' }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Creado por:</span>
                                @if($ticket->creator)
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.users.show', $ticket->creator->id) }}" class="text-blue-700 font-medium hover:underline">
                                        {{ $ticket->creator->username }}
                                    </a>
                                @elseif(auth()->user()->role === 'agent')
                                    <a href="{{ route('agent.users.show', $ticket->creator->id) }}" class="text-blue-700 font-medium hover:underline">
                                        {{ $ticket->creator->username }}
                                    </a>
                                @else
                                    <span class="text-gray-700 font-medium">{{ $ticket->creator->username }}</span>
                                @endif
                                @else
                                <span class="text-gray-800 font-medium">Usuario</span>
                                @endif
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Fecha creación:</span>
                                <span class="text-gray-800">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            @if($ticket->resolved_at)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Resuelto:</span>
                                <span class="text-gray-800">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-bolt text-yellow-500"></i>
                            Acciones Rápidas
                        </h3>

                        <div class="space-y-3">
                            <a href="{{ route('agent.tickets.available') }}" class="flex items-center gap-2 w-full px-4 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                <i class="fas fa-hand-paper"></i>
                                Ver Más Tickets
                            </a>

                            <form action="{{ route('agent.tickets.next') }}" method="POST" class="inline w-full">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                    <i class="fas fa-play"></i>
                                    Tomar Siguiente
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
