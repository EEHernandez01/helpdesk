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
                        <div id="commentsContainerAgent" class="max-h-[60vh] overflow-y-auto pr-1">
                        <div class="space-y-4">
                            @foreach($ticket->comments as $comment)
                                @php
                                    $isMine = (int)($comment->user_id ?? 0) === (int)auth()->id();
                                @endphp
                                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[85%] sm:max-w-[70%]">
                                        <div class="flex items-center gap-2 mb-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                            @unless($isMine)
                                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ strtoupper(substr($comment->user->username ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="font-semibold text-gray-700">{{ $comment->user->username ?? 'Usuario' }}</span>
                                            @endunless
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            @if($comment->is_internal)
                                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-[10px] rounded-full">Nota interna</span>
                                            @endif
                                        </div>
                                        <div class="rounded-2xl px-4 py-3 shadow-sm border {{ $isMine ? 'bg-blue-100 border-blue-200 rounded-br-sm' : 'bg-gray-100 border-gray-200 rounded-bl-sm' }}">
                                            <p class="text-gray-800 whitespace-pre-wrap">{{ $comment->content }}</p>

                                            @php
                                                if (!function_exists('isImageFile')) {
                                                    function isImageFile($filename) {
                                                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                                        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                                    }
                                                }
                                            @endphp

                                            @if(!empty(json_decode($comment->attachments ?? '[]', true)))
                                                <div class="mt-3 pt-3 border-t border-gray-200">
                                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Archivos adjuntos:</h4>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        @foreach(json_decode($comment->attachments, true) as $attachment)
                                                            @if(isImageFile($attachment))
                                                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all duration-200">
                                                                    <div class="relative pb-[60%] bg-gray-100">
                                                                        <img src="{{ Storage::url($attachment) }}"
                                                                            alt="{{ basename($attachment) }}"
                                                                            class="absolute inset-0 w-full h-full object-cover image-thumbnail cursor-pointer"
                                                                            data-src="{{ Storage::url($attachment) }}"
                                                                            data-filename="{{ basename($attachment) }}">
                                                                    </div>
                                                                    <div class="p-2 bg-white">
                                                                        <p class="text-xs text-gray-700 truncate">{{ basename($attachment) }}</p>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <a href="{{ Storage::url($attachment) }}" target="_blank"
                                                                    class="flex items-center p-2 rounded-lg hover:bg-gray-100 border border-gray-200 group transition-colors duration-150">
                                                                    <div class="bg-indigo-100 p-1 rounded-md mr-2">
                                                                        <i class="fas fa-file text-indigo-600 text-sm"></i>
                                                                    </div>
                                                                    <div class="text-xs text-gray-700 truncate group-hover:text-indigo-600">
                                                                        {{ basename($attachment) }}
                                                                    </div>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        </div>
                        @else
                        <p class="text-gray-500 italic">No hay comentarios aún.</p>
                        @endif
                        <!-- Feedback al cerrar el ticket -->
                        @if($ticket->status === 'cerrado' && $ticket->creator && Auth::id() === $ticket->creator->id)
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

                        @if($ticket->status === 'cerrado')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                    <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 font-semibold">
                                        Cerrado
                                    </div>
                                </div>
                            </div>
                            <!-- La sección de nota de resolución se muestra abajo en el panel lateral -->
                        @else
                            <form action="{{ route('agent.tickets.update', $ticket) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                    <select name="status" id="statusSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="abierto" {{ $ticket->status === 'abierto' ? 'selected' : '' }}>Abierto</option>
                                        <option value="en progreso" {{ $ticket->status === 'en progreso' ? 'selected' : '' }}>En Progreso</option>
                                        <option value="resuelto" {{ $ticket->status === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                        <option value="cerrado" {{ $ticket->status === 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                                    </select>
                                </div>

                                <div id="resolutionNotesDiv" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notas de Resolución</label>
                                    <textarea name="resolution_notes" rows="4" placeholder="Agrega notas sobre la resolución..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ $ticket->resolution_notes }}</textarea>
                                </div>

                                <button type="submit" class="w-full px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                                    <i class="fas fa-save mr-2"></i>Actualizar Estado
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Información del ticket -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Información
                        </h3>

                        <div class="space-y-3">
                            @php
                                $priorityMap = [
                                    'alta' => 'bg-red-100 text-red-800',
                                    'media' => 'bg-yellow-100 text-yellow-800',
                                    'baja' => 'bg-green-100 text-green-800',
                                    'urgente' => 'bg-orange-100 text-orange-800',
                                ];
                                $priorityBadge = $priorityMap[$ticket->priority] ?? 'bg-blue-100 text-blue-800';
                            @endphp
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Prioridad:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $priorityBadge }}">
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

                    @if($ticket->status === 'cerrado' && !empty($ticket->resolution_notes))
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-500"></i>
                            Nota de Resolución
                        </h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->resolution_notes }}</p>
                        </div>
                    </div>
                    @endif

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const commentsContainer = document.getElementById('commentsContainerAgent');
        if (commentsContainer) {
            commentsContainer.scrollTop = commentsContainer.scrollHeight;
        }

        // Mostrar/ocultar campo de notas de resolución basado en el estado seleccionado
        const statusSelect = document.getElementById('statusSelect');
        const resolutionNotesDiv = document.getElementById('resolutionNotesDiv');

        if (statusSelect && resolutionNotesDiv) {
            // Función para toggle
            function toggleResolutionNotes() {
                if (statusSelect.value === 'cerrado') {
                    resolutionNotesDiv.style.display = 'block';
                } else {
                    resolutionNotesDiv.style.display = 'none';
                }
            }

            // Evento de cambio
            statusSelect.addEventListener('change', toggleResolutionNotes);

            // Inicial check
            toggleResolutionNotes();
        }
    });
    </script>
