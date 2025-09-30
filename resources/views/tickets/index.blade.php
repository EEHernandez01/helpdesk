<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Tickets</h2>
                <a href="{{ route('tickets.create') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700 transition shadow-lg">
                    <i class="fas fa-plus"></i> Crear Ticket
                </a>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden">
                <div class="p-8 text-gray-900">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6 border-b pb-2">Mis Tickets</h3>

                    @if ($tickets->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 bg-gray-50 rounded-xl">
                        <i class="fas fa-ticket-alt text-6xl text-purple-200 mb-4"></i>
                        <p class="text-gray-500 text-lg">No tienes tickets registrados.</p>
                        <a href="{{ route('tickets.create') }}"
                            class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-base font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-lg">
                            <i class="fas fa-plus"></i> Crear tu primer ticket
                        </a>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-xl">Título</th>
                                    <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                                    <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Creación</th>
                                    <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-xl">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($tickets as $ticket)
                                <tr class="hover:bg-purple-50/40 transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $ticket->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex items-center justify-center text-xs leading-5 font-semibold rounded-full
                                            {{
                                                $ticket->status == 'nuevo' ? 'bg-green-100 text-green-800' :
                                                ($ticket->status == 'en progreso' ? 'bg-yellow-100 text-yellow-800' :
                                                ($ticket->status == 'resuelto' ? 'bg-blue-100 text-blue-800' :
                                                ($ticket->status == 'cerrado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))
                                            }}">
                                            <i class="fas fa-circle mr-1 text-xs align-middle"></i>
                                            <span class="align-middle">{{ ucfirst($ticket->status) }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span class="px-3 py-1 inline-flex items-center justify-center text-xs leading-5 font-semibold rounded-full
                                            {{
                                                $ticket->priority == 'alta' ? 'bg-red-100 text-red-800' :
                                                ($ticket->priority == 'media' ? 'bg-yellow-100 text-yellow-800' :
                                                ($ticket->priority == 'baja' ? 'bg-green-100 text-green-800' :
                                                ($ticket->priority == 'urgente' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')))
                                            }}">
                                            <i class="fas fa-bolt mr-1 text-xs align-middle"></i>
                                            <span class="align-middle">{{ ucfirst($ticket->priority) }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        {{ $ticket->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-3">
                                            <a href="{{ route('tickets.show', $ticket->id) }}"
                                                class="text-indigo-600 hover:text-white bg-indigo-100 hover:bg-gradient-to-r hover:from-purple-600 hover:to-indigo-600 px-3 py-1 rounded-full transition duration-150 ease-in-out flex items-center gap-1 shadow">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            @if(Auth::user()->role === 'admin')
                                            <form id="delete-form-{{ $ticket->id }}"
                                                action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                                                onsubmit="return false;" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDelete('{{ $ticket->id }}', '{{ $ticket->title }}')"
                                                    class="text-red-600 hover:text-white bg-red-100 hover:bg-gradient-to-r hover:from-red-600 hover:to-pink-600 px-3 py-1 rounded-full transition duration-150 ease-in-out flex items-center gap-1 shadow">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white/90 rounded-2xl shadow-lg p-8 max-w-md w-full border-t-4 border-red-500">
            <div class="mb-4 text-center">
                <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Confirmar eliminación</h3>
                <p class="text-sm text-gray-500 mt-2">¿Estás seguro de que deseas eliminar el ticket <span
                        id="ticketTitle" class="font-semibold"></span>? Esta acción no se puede deshacer.</p>
            </div>
            <div class="flex justify-center space-x-4 mt-6">
                <button id="cancelDelete"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-150 ease-in-out">
                    Cancelar
                </button>
                <button id="confirmDeleteBtn"
                    class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:from-red-700 hover:to-pink-700 transition duration-150 ease-in-out">
                    Eliminar
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let currentTicketId = null;
        const modal = document.getElementById('deleteModal');
        const ticketTitle = document.getElementById('ticketTitle');
        const cancelBtn = document.getElementById('cancelDelete');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        function confirmDelete(ticketId, title) {
            currentTicketId = ticketId;
            ticketTitle.textContent = title;
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }

        function submitDeleteForm(ticketId) {
            const form = document.getElementById('delete-form-' + ticketId);
            if (form) {
                form.onsubmit = null; // Remove the return false
                form.submit();
            }
        }

        cancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            currentTicketId = null;
        });

        confirmBtn.addEventListener('click', function() {
            if (currentTicketId) {
                submitDeleteForm(currentTicketId);
            }
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                currentTicketId = null;
            }
        });
    </script>
    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
