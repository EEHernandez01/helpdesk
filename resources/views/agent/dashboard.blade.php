<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-6xl mx-auto px-4 space-y-10">
            <!-- Encabezado y métricas personales -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-600 rounded-2xl shadow-lg text-white font-bold text-3xl">
                        {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-1">
                            ¡Hola, {{ auth()->user()->username }}!
                        </h1>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-600">
                            <i class="fas fa-circle text-green-500 text-xs"></i> Disponible
                        </div>
                    </div>
                </div>
                <!-- Métricas personales -->
                <div class="flex flex-wrap gap-4 justify-center">
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4 flex flex-col items-center min-w-[120px]">
                        <i class="fas fa-ticket-alt text-blue-500 text-xl mb-1"></i>
                        <span class="text-xs text-gray-500">Abiertos</span>
                        <span class="text-lg font-bold text-blue-800">{{ $assignedCount ?? 0 }}</span>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4 flex flex-col items-center min-w-[120px]">
                        <i class="fas fa-spinner text-yellow-500 text-xl mb-1"></i>
                        <span class="text-xs text-gray-500">En Progreso</span>
                        <span class="text-lg font-bold text-yellow-700">{{ $inProgressCount ?? 0 }}</span>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4 flex flex-col items-center min-w-[120px]">
                        <i class="fas fa-check-circle text-green-500 text-xl mb-1"></i>
                        <span class="text-xs text-gray-500">Cerrados</span>
                        <span class="text-lg font-bold text-green-700">{{ $closedCount ?? 0 }}</span>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow p-4 flex flex-col items-center min-w-[120px]">
                        <i class="fas fa-clock text-purple-500 text-xl mb-1"></i>
                        <span class="text-xs text-gray-500">Resp. Promedio</span>
                        <span class="text-lg font-bold text-purple-700">{{ $avgResponse ?? '--' }}</span>
                    </div>
                </div>
            </div>

            <!-- Panel de notificaciones/alertas -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-xl p-4 flex items-center gap-3 shadow">
                <i class="fas fa-bell text-yellow-500 text-2xl"></i>
                <div>
                    <span class="font-semibold text-yellow-800">¡Atención!</span>
                    <span class="text-yellow-700">Tienes {{ $urgentCount ?? 0 }} tickets urgentes o vencidos.</span>
                        @if(isset($soonDueTickets) && $soonDueTickets->count())
                            <div class="mt-2 text-yellow-700">
                                <i class="fas fa-hourglass-half text-yellow-600"></i>
                                {{ $soonDueTickets->count() }} ticket(s) próximos a vencer en menos de 24h.
                            </div>
                        @endif
                        @if(isset($staleTickets) && $staleTickets->count())
                            <div class="mt-2 text-orange-700">
                                <i class="fas fa-exclamation-triangle text-orange-600"></i>
                                {{ $staleTickets->count() }} ticket(s) sin atención en más de 48h.
                            </div>
                        @endif
                </div>
            </div>

            <!-- Accesos rápidos -->
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('agent.tickets.index') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 transition shadow-lg">
                    <i class="fas fa-list"></i> Mis Tickets
                </a>
                <a href="{{ route('agent.tickets.available') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-orange-500 to-orange-700 text-white hover:from-orange-600 hover:to-orange-800 transition shadow-lg">
                    <i class="fas fa-hand-paper"></i> Tickets Disponibles
                </a>
                <form action="{{ route('agent.tickets.next') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-green-500 to-green-700 text-white hover:from-green-600 hover:to-green-800 transition shadow-lg">
                        <i class="fas fa-play"></i> Tomar Siguiente
                    </button>
                </form>
                <a href="{{ route('agent.tickets.create-user') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-indigo-500 to-indigo-700 text-white hover:from-indigo-600 hover:to-indigo-800 transition shadow-lg">
                    <i class="fas fa-user-plus"></i> Crear Ticket para Usuario
                </a>
                <a href="{{ route('help.index') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-pink-500 to-pink-700 text-white hover:from-pink-600 hover:to-pink-800 transition shadow-lg">
                    <i class="fas fa-book"></i> Centro de Ayuda
                </a>
                <a href="#" class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-yellow-500 to-yellow-700 text-white hover:from-yellow-600 hover:to-yellow-800 transition shadow-lg">
                    <i class="fas fa-history"></i> Historial Resueltos
                </a>
            </div>

            <!-- Tickets disponibles (nueva sección) -->
            @if(isset($availableTickets) && $availableTickets->count() > 0)
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden">
                <div class="border-b px-6 py-4 bg-orange-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-hand-paper text-orange-500 text-xl"></i>
                        <h3 class="text-lg font-bold text-gray-700">Tickets Disponibles para Asignar</h3>
                    </div>
                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $availableTickets->count() }} disponibles
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">ID</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Asunto</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Prioridad</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Departamento</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Creado</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($availableTickets->take(5) as $ticket)
                            <tr>
                                <td class="px-4 py-2 text-gray-700">{{ $ticket->id }}</td>
                                <td class="px-4 py-2 text-gray-900">{{ Str::limit($ticket->title, 50) }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($ticket->priority === 'alta') bg-red-100 text-red-800
                                        @elseif($ticket->priority === 'media') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-500">{{ $ticket->department->name ?? 'Sin departamento' }}</td>
                                <td class="px-4 py-2 text-gray-500">{{ $ticket->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('agent.tickets.assign', $ticket) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 font-semibold flex items-center gap-1">
                                            <i class="fas fa-hand-paper"></i> Asignar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($availableTickets->count() > 5)
                <div class="px-6 py-3 bg-gray-50 text-center">
                    <a href="{{ route('agent.tickets.available') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Ver todos los {{ $availableTickets->count() }} tickets disponibles
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Tickets asignados (tabla) -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-ticket-alt text-blue-500 text-xl"></i>
                    <h3 class="text-lg font-bold text-gray-700">Tickets Asignados</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">ID</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Asunto</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Estado</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Prioridad</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Creado</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($assignedTickets as $ticket)
                            <tr>
                                <td class="px-4 py-2 text-gray-700">{{ $ticket->id }}</td>
                                <td class="px-4 py-2 text-gray-900">{{ $ticket->title }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $ticket->status_class }}">
                                        {{ $ticket->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($ticket->priority === 'alta') bg-red-100 text-red-800
                                        @elseif($ticket->priority === 'media') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-500">{{ $ticket->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('agent.tickets.show', $ticket) }}" class="text-blue-600 hover:underline font-semibold flex items-center gap-1">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-6 px-6 text-center text-gray-500">No tienes tickets asignados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Gráfica de desempeño (placeholder) -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 flex flex-col items-center mt-4">
                <h4 class="text-lg font-bold text-gray-700 mb-2 flex items-center gap-2">
                    <i class="fas fa-chart-line text-green-500"></i> Desempeño Personal (Próximamente)
                </h4>
                <div class="w-full h-32 flex items-center justify-center text-gray-400">
                    <span>Gráfica de tickets resueltos y satisfacción aparecerá aquí.</span>
                </div>
            </div>
        </div>
    </div>
    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
