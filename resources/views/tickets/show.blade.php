<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-6xl mx-auto px-4 space-y-6">

            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Ticket #{{ $ticket->id }}
                        </h1>
                    </div>
                    <p class="text-gray-600 text-lg">{{ $ticket->title }}</p>
                    <p class="text-sm text-gray-500">Creado {{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() }}</p>
                </div>
                <div class="flex gap-3">
                    @if($ticket->status != 'cerrado' && $ticket->creator_id == auth()->id())
                    <a href="{{ route('tickets.edit', $ticket) }}"
                        class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl font-semibold shadow-lg hover:from-yellow-600 hover:to-yellow-700 transition">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    @endif
                    <a href="{{ route('tickets.index') }}"
                        class="flex items-center gap-2 px-6 py-3 bg-gray-500 text-white rounded-xl font-semibold hover:bg-gray-600 transition shadow-lg">
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

                    @php
                    $attachments = json_decode($ticket->attachments, true);
                    @endphp

                    @if(!empty($attachments))
                    <!-- Archivos adjuntos -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-paperclip text-green-500"></i>
                            Archivos adjuntos
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($attachments as $attachment)
                            @if(isImageFile($attachment))
                            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-all duration-200">
                                <div class="relative pb-[60%] bg-gray-100">
                                    <img src="{{ Storage::url($attachment) }}"
                                        alt="{{ basename($attachment) }}"
                                        class="absolute inset-0 w-full h-full object-cover image-thumbnail cursor-pointer"
                                        data-src="{{ Storage::url($attachment) }}"
                                        data-filename="{{ basename($attachment) }}">
                                </div>
                                <div class="p-3 bg-white">
                                    <p class="text-xs text-gray-700 truncate">{{ basename($attachment) }}</p>
                                </div>
                            </div>
                            @else
                            <a href="{{ Storage::url($attachment) }}" target="_blank"
                                class="flex items-center p-3 rounded-xl hover:bg-gray-50 border border-gray-200 group transition-colors duration-150">
                                <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-file text-indigo-600 text-lg"></i>
                                </div>
                                <div class="text-sm text-gray-700 truncate group-hover:text-indigo-600">
                                    {{ basename($attachment) }}
                                </div>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Resolución (si el ticket está resuelto) -->
                    @if($ticket->resolved_at)
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-500"></i>
                            Resolución
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Fecha de resolución:</span>
                                <span class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($ticket->resolved_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 block mb-2 font-medium">Notas de resolución:</span>
                                <div class="prose max-w-none">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->resolution_notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Historial de comentarios -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-comments text-purple-500"></i>
                            Comentarios ({{ $ticket->comments->count() }})
                        </h3>

                        @if($ticket->comments->count() > 0)
                        <div id="commentsContainer" class="max-h-[60vh] overflow-y-auto pr-1">
                        <div class="space-y-4">
                            @foreach($ticket->comments as $comment)
                                @php($isMine = (int)($comment->user_id ?? 0) === (int)auth()->id())
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
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-comment-slash text-4xl mb-3 text-gray-300"></i>
                            <p>No hay comentarios en este ticket.</p>
                        </div>
                        @endif

                        @if($ticket->status != 'cerrado')
                        <!-- Formulario para nuevo comentario -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                            <h4 class="text-md font-semibold text-gray-700 mb-3">Añadir comentario</h4>
                            <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-3">
                                    <textarea name="content" rows="3" placeholder="Escribe tu comentario aquí..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                    <div>
                                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">Adjuntos (opcional)</label>
                                        <input type="file" id="attachments" name="attachments[]" multiple
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Puedes seleccionar múltiples archivos. Máximo 10MB por archivo.</p>
                                    </div>
                                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-semibold">
                                        <i class="fas fa-paper-plane mr-2"></i>Enviar comentario
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
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

                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Estado:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $ticket->status == 'nuevo' ? 'bg-green-100 text-green-800' :
                                        ($ticket->status == 'en progreso' ? 'bg-yellow-100 text-yellow-800' :
                                        ($ticket->status == 'resuelto' ? 'bg-blue-100 text-blue-800' :
                                        ($ticket->status == 'cerrado' ? 'bg-red-100 text-red-800' :
                                        'bg-gray-100 text-gray-800'))) }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Prioridad:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $ticket->priority == 'alta' ? 'bg-red-100 text-red-800' :
                                    ($ticket->priority == 'media' ? 'bg-yellow-100 text-yellow-800' :
                                    ($ticket->priority == 'baja' ? 'bg-green-100 text-green-800' :
                                    ($ticket->priority == 'urgente' ? 'bg-orange-100 text-orange-800' :
                                    'bg-blue-100 text-blue-800'))) }}">
                                    <i class="fas fa-bolt mr-1 text-xs"></i> {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Información del ticket -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Información
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Departamento:</span>
                                <span class="text-gray-800 font-medium">{{ $ticket->department->name ?? 'Sin departamento' }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Categoría:</span>
                                <span class="text-gray-800 font-medium">{{ $ticket->category->name ?? 'Sin categoría' }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Creado por:</span>
                                <span class="text-gray-800 font-medium">{{ $ticket->creator->username ?? 'Usuario' }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Asignado a:</span>
                                <span class="text-gray-800 font-medium">{{ $ticket->assignedTo->username ?? 'No asignado' }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Fecha creación:</span>
                                <span class="text-gray-800 font-medium">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Última actualización:</span>
                                <span class="text-gray-800 font-medium">{{ $ticket->updated_at->format('d/m/Y H:i') }}</span>
                            </div>

                            @if($ticket->resolved_at)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Resuelto:</span>
                                <span class="text-gray-800 font-medium">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
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
                            <!-- Asignación de ticket (solo para administradores) -->
                            @if(auth()->user()->role === 'admin' && (!$ticket->assignedTo || $ticket->status !== 'cerrado'))
                                <div class="p-4 bg-gray-50 rounded-xl">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Asignar ticket a:</h4>
                                    <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <select name="agent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Seleccionar agente...</option>
                                            @foreach(\App\Models\User::where('role', 'agent')->orderBy('name')->get() as $agent)
                                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                                    {{ $agent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition">
                                            <i class="fas fa-user-plus mr-2"></i>Asignar
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <a href="{{ route('tickets.create') }}" class="flex items-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition">
                                <i class="fas fa-plus"></i>
                                Nuevo Ticket
                            </a>

                            <a href="{{ route('tickets.index') }}" class="flex items-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition">
                                <i class="fas fa-list"></i>
                                Ver Todos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para imágenes -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl max-h-full overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="modal-title" class="text-lg font-semibold text-gray-800"></h3>
                <div class="flex gap-2">
                    <a id="downloadBtn" href="#" download class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-download"></i>
                    </a>
                    <a id="openNewTabBtn" href="#" target="_blank" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button id="closeModalBtn" class="px-3 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-4">
                <img id="modalImage" src="" alt="" class="max-w-full max-h-96 object-contain">
            </div>
        </div>
    </div>

    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>

<!-- Script para el modal de imágenes -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.image-thumbnail');
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modal-title');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        const openNewTabBtn = document.getElementById('openNewTabBtn');
        const commentsContainer = document.getElementById('commentsContainer');

        // Auto-scroll de comentarios al final si el contenedor existe
        if (commentsContainer) {
            commentsContainer.scrollTop = commentsContainer.scrollHeight;
        }

        thumbnails.forEach(function(thumbnail) {
            thumbnail.addEventListener('click', function() {
                const imageSrc = this.getAttribute('data-src');
                const filename = this.getAttribute('data-filename');

                modalImage.src = imageSrc;
                modalTitle.textContent = filename;
                downloadBtn.href = imageSrc;
                openNewTabBtn.href = imageSrc;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            });
        });

        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        });

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });
    });
</script>
