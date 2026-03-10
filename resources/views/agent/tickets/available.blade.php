<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-6">
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        Tickets Disponibles
                    </h1>
                    <p class="text-gray-600 mt-1">Selecciona los tickets que deseas atender o agregar a pendientes</p>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('agent.tickets.next') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-green-500 to-green-700 text-white hover:from-green-600 hover:to-green-800 transition shadow-lg">
                            <i class="fas fa-play"></i> Tomar Siguiente
                        </button>
                    </form>
                    <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gray-500 text-white hover:bg-gray-600 transition shadow-lg">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-orange-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Disponibles</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $availableTickets->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alta Prioridad</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $availableTickets->where('priority', 'alta')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Media Prioridad</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $availableTickets->where('priority', 'media')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Baja Prioridad</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $availableTickets->where('priority', 'baja')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y controles -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Filtrar por prioridad:</label>
                            <select id="priorityFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todas</option>
                                <option value="alta">Alta</option>
                                <option value="media">Media</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Ordenar por:</label>
                            <select id="sortFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="priority-desc">Prioridad (Alta → Baja)</option>
                                <option value="priority-asc">Prioridad (Baja → Alta)</option>
                                <option value="date-asc">Fecha (Más antiguo)</option>
                                <option value="date-desc">Fecha (Más reciente)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="selectAll" class="text-sm font-medium text-gray-700">Seleccionar todos</label>
                        </div>
                        <span id="selectedCount" class="text-sm text-gray-500">0 seleccionados</span>
                    </div>
                </div>
            </div>

            <!-- Acciones en lote -->
            <div id="batchActions" class="bg-blue-50 border border-blue-200 rounded-2xl p-4 hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-tasks text-blue-500"></i>
                        <span class="font-medium text-blue-800">Acciones en lote para <span id="selectedCountDisplay">0</span> tickets</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button id="assignSelected" class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                            <i class="fas fa-hand-paper"></i>
                            Asignar Seleccionados
                        </button>
                        <button id="addToPending" class="flex items-center gap-2 px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium">
                            <i class="fas fa-clock"></i>
                            Agregar a Pendientes
                        </button>
                        <button id="clearSelection" class="flex items-center gap-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium">
                            <i class="fas fa-times"></i>
                            Limpiar Selección
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de tickets disponibles -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden">
                <div class="border-b px-6 py-4 bg-orange-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-hand-paper text-orange-500 text-xl"></i>
                        <h3 class="text-lg font-bold text-gray-700">Tickets Sin Asignar</h3>
                    </div>
                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                        <span id="visibleCount">{{ $availableTickets->count() }}</span> tickets
                    </span>
                </div>

                @if($availableTickets->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">
                                    <input type="checkbox" id="selectAllTable" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">ID</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">Asunto</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">Prioridad</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">Departamento</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">Creado por</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">Fecha</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($availableTickets as $ticket)
                            <tr class="hover:bg-gray-50 transition-colors ticket-row" data-priority="{{ $ticket->priority }}" data-date="{{ $ticket->created_at->timestamp }}">
                                <td class="px-4 py-3">
                                    <input type="checkbox" class="ticket-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" value="{{ $ticket->id }}">
                                </td>
                                <td class="px-4 py-3 text-gray-700 font-mono">#{{ $ticket->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="max-w-xs">
                                        <p class="text-gray-900 font-medium">{{ $ticket->title }}</p>
                                        @if($ticket->category)
                                        <p class="text-xs text-gray-500">{{ $ticket->category->name }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($ticket->priority === 'alta') bg-red-100 text-red-800 border border-red-200
                                        @elseif($ticket->priority === 'media') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @else bg-blue-100 text-blue-800 border border-blue-200
                                        @endif">
                                        <i class="fas fa-flag mr-1"></i>
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $ticket->department->name ?? 'Sin departamento' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            {{ strtoupper(substr($ticket->creator->username ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-gray-700">{{ $ticket->creator->username ?? 'Usuario' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    <div class="text-sm">
                                        <div>{{ $ticket->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs">{{ $ticket->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('agent.tickets.assign', $ticket) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                                <i class="fas fa-hand-paper"></i>
                                                Asignar
                                            </button>
                                        </form>
                                        <form action="{{ route('agent.tickets.add-to-pending') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="ticket_ids" value="[{{ $ticket->id }}]">
                                            <button type="submit" class="flex items-center gap-1 px-3 py-1 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium">
                                                <i class="fas fa-clock"></i>
                                                Pendiente
                                            </button>
                                        </form>
                                        <a href="{{ route('agent.tickets.show', $ticket) }}" class="flex items-center gap-1 px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                                            <i class="fas fa-eye"></i>
                                            Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="py-12 text-center">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">¡Excelente trabajo!</h3>
                    <p class="text-gray-500">No hay tickets disponibles para asignar en este momento.</p>
                    <a href="{{ route('agent.dashboard') }}" class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-arrow-left"></i>
                        Volver al Dashboard
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-question text-blue-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700">Confirmar acción</h3>
            </div>
            <p id="confirmMessage" class="text-gray-600 mb-6"></p>
            <div class="flex gap-3 justify-end">
                <button onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                    Cancelar
                </button>
                <button id="confirmAction" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        // Variables globales
        let selectedTickets = new Set();
        let currentAction = '';

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initializeFilters();
            initializeCheckboxes();
            initializeBatchActions();
        });

        // Filtros
        function initializeFilters() {
            const priorityFilter = document.getElementById('priorityFilter');
            const sortFilter = document.getElementById('sortFilter');

            priorityFilter.addEventListener('change', filterTickets);
            sortFilter.addEventListener('change', filterTickets);
        }

        function filterTickets() {
            const priorityFilter = document.getElementById('priorityFilter').value;
            const sortFilter = document.getElementById('sortFilter').value;
            const rows = document.querySelectorAll('.ticket-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const priority = row.dataset.priority;
                const date = parseInt(row.dataset.date);
                let show = true;

                // Filtro por prioridad
                if (priorityFilter && priority !== priorityFilter) {
                    show = false;
                }

                // Mostrar/ocultar fila
                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            // Actualizar contador
            document.getElementById('visibleCount').textContent = visibleCount;

            // Ordenar filas
            sortTableRows(sortFilter);
        }

        function sortTableRows(sortType) {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('.ticket-row:not([style*="display: none"])'));

            rows.sort((a, b) => {
                const priorityA = a.dataset.priority;
                const priorityB = b.dataset.priority;
                const dateA = parseInt(a.dataset.date);
                const dateB = parseInt(b.dataset.date);

                const priorityOrder = {
                    'alta': 3,
                    'media': 2,
                    'baja': 1
                };

                switch (sortType) {
                    case 'priority-desc':
                        return priorityOrder[priorityB] - priorityOrder[priorityA];
                    case 'priority-asc':
                        return priorityOrder[priorityA] - priorityOrder[priorityB];
                    case 'date-asc':
                        return dateA - dateB;
                    case 'date-desc':
                        return dateB - dateA;
                    default:
                        return 0;
                }
            });

            rows.forEach(row => tbody.appendChild(row));
        }

        // Checkboxes
        function initializeCheckboxes() {
            const selectAll = document.getElementById('selectAll');
            const selectAllTable = document.getElementById('selectAllTable');
            const checkboxes = document.querySelectorAll('.ticket-checkbox');

            selectAll.addEventListener('change', function() {
                const checked = this.checked;
                selectAllTable.checked = checked;
                checkboxes.forEach(checkbox => {
                    if (checkbox.closest('.ticket-row').style.display !== 'none') {
                        checkbox.checked = checked;
                        updateSelection(checkbox);
                    }
                });
            });

            selectAllTable.addEventListener('change', function() {
                const checked = this.checked;
                selectAll.checked = checked;
                checkboxes.forEach(checkbox => {
                    if (checkbox.closest('.ticket-row').style.display !== 'none') {
                        checkbox.checked = checked;
                        updateSelection(checkbox);
                    }
                });
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelection(this);
                    updateSelectAllState();
                });
            });
        }

        function updateSelection(checkbox) {
            const ticketId = checkbox.value;
            if (checkbox.checked) {
                selectedTickets.add(ticketId);
            } else {
                selectedTickets.delete(ticketId);
            }
            updateSelectedCount();
            updateBatchActionsVisibility();
        }

        function updateSelectAllState() {
            const visibleCheckboxes = document.querySelectorAll('.ticket-checkbox:not([style*="display: none"])');
            const checkedCheckboxes = document.querySelectorAll('.ticket-checkbox:checked:not([style*="display: none"])');

            const selectAll = document.getElementById('selectAll');
            const selectAllTable = document.getElementById('selectAllTable');

            if (checkedCheckboxes.length === 0) {
                selectAll.checked = false;
                selectAllTable.checked = false;
            } else if (checkedCheckboxes.length === visibleCheckboxes.length) {
                selectAll.checked = true;
                selectAllTable.checked = true;
            } else {
                selectAll.checked = false;
                selectAllTable.checked = false;
            }
        }

        function updateSelectedCount() {
            const count = selectedTickets.size;
            document.getElementById('selectedCount').textContent = `${count} seleccionados`;
            document.getElementById('selectedCountDisplay').textContent = count;
        }

        function updateBatchActionsVisibility() {
            const batchActions = document.getElementById('batchActions');
            if (selectedTickets.size > 0) {
                batchActions.classList.remove('hidden');
            } else {
                batchActions.classList.add('hidden');
            }
        }

        // Acciones en lote
        function initializeBatchActions() {
            document.getElementById('assignSelected').addEventListener('click', () => {
                showConfirmModal('¿Estás seguro de que quieres asignar los tickets seleccionados?', 'assign');
            });

            document.getElementById('addToPending').addEventListener('click', () => {
                showConfirmModal('¿Estás seguro de que quieres agregar los tickets seleccionados a pendientes?', 'pending');
            });

            document.getElementById('clearSelection').addEventListener('click', clearSelection);
        }

        function showConfirmModal(message, action) {
            currentAction = action;
            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            currentAction = '';
        }

        function clearSelection() {
            selectedTickets.clear();
            document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            document.getElementById('selectAllTable').checked = false;
            updateSelectedCount();
            updateBatchActionsVisibility();
        }

        // Confirmar acciones
        document.getElementById('confirmAction').addEventListener('click', function() {
            if (selectedTickets.size === 0) {
                closeModal();
                return;
            }

            const ticketIds = Array.from(selectedTickets);

            switch (currentAction) {
                case 'assign':
                    assignSelectedTickets(ticketIds);
                    break;
                case 'pending':
                    addSelectedToPending(ticketIds);
                    break;
            }

            closeModal();
        });

        function assignSelectedTickets(ticketIds) {
            // Crear formulario para asignar múltiples tickets
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("agent.tickets.assign-multiple") }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const ticketsInput = document.createElement('input');
            ticketsInput.type = 'hidden';
            ticketsInput.name = 'ticket_ids';
            ticketsInput.value = JSON.stringify(ticketIds);

            form.appendChild(csrfToken);
            form.appendChild(ticketsInput);
            document.body.appendChild(form);
            form.submit();
        }

        function addSelectedToPending(ticketIds) {
            // Aquí puedes implementar la lógica para agregar a pendientes
            // Por ahora, redirigimos a una ruta que maneje esto
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("agent.tickets.add-to-pending") }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const ticketsInput = document.createElement('input');
            ticketsInput.type = 'hidden';
            ticketsInput.name = 'ticket_ids';
            ticketsInput.value = JSON.stringify(ticketIds);

            form.appendChild(csrfToken);
            form.appendChild(ticketsInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-app-layout>
