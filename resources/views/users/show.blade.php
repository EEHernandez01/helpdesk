<x-app-layout>
    {{-- FontAwesome para iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-8">
            {{-- Encabezado mejorado --}}
            <div class="text-center space-y-4">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-600 rounded-2xl shadow-lg shadow-purple-200 mb-4 text-white font-bold text-2xl">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">
                        Perfil de Usuario
                    </h1>
                    <p class="text-gray-600 text-lg">
                        Visualizando información de:
                        <span class="font-semibold text-purple-600">{{ $user->name }}</span>
                    </p>
                    @if($user->company)
                    <div class="inline-flex items-center gap-2 mt-2 px-3 py-1 bg-purple-100 rounded-full text-sm text-purple-700 font-semibold">
                        <i class="fas fa-building text-xs"></i>
                        {{ $user->company->nombre }}
                    </div>
                    @else
                    <div class="inline-flex items-center gap-2 mt-2 px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-600">
                        <i class="fas fa-building text-xs"></i>
                        Sin compañía
                    </div>
                    @endif
                    <div class="inline-flex items-center gap-2 mt-2 px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-600">
                        <i class="fas fa-id-badge text-xs"></i>
                        ID: {{ $user->id }}
                    </div>
                </div>
            </div>

            {{-- Información actual del usuario --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-gray-200/50 border border-white/20 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-purple-600 text-sm"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Estado Actual</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="text-sm text-blue-600 font-medium">Email</div>
                        <div class="text-blue-800 font-semibold">{{ $user->email }}</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4">
                        <div class="text-sm text-green-600 font-medium">Rol</div>
                        <div class="text-green-800 font-semibold capitalize">
                            @if($user->role === 'admin') 🔴 Administrador
                            @elseif($user->role === 'agent') 🟡 Agente
                            @else 🟢 Usuario
                            @endif
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4">
                        <div class="text-sm text-purple-600 font-medium">Última Actualización</div>
                        <div class="text-purple-800 font-semibold">{{ $user->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            {{-- Herramientas rápidas --}}
            <div class="bg-blue-50 rounded-2xl shadow p-8">
                <div class="flex items-center gap-3 mb-5">
                    <i class="fas fa-headset text-blue-400 text-xl"></i>
                    <h2 class="text-xl font-bold text-blue-700">Herramientas rápidas de Helpdesk</h2>
                </div>
                <div class="flex flex-wrap gap-4 mb-4">
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="flex items-center gap-2 px-5 py-2 rounded-lg font-semibold bg-purple-600 text-white hover:bg-purple-700 transition shadow">
                        <i class="fas fa-edit"></i> Editar usuario
                    </a>
                    <a href="{{ route('tickets.create', ['user_id' => $user->id]) }}"
                        class="flex items-center gap-2 px-5 py-2 rounded-lg font-semibold bg-blue-600 text-white hover:bg-blue-700 transition shadow">
                        <i class="fas fa-plus"></i> Crear ticket
                    </a>
                    <a href="{{ route('admin.computers.index', ['assigned_user_id' => $user->id]) }}"
                        class="flex items-center gap-2 px-5 py-2 rounded-lg font-semibold bg-green-600 text-white hover:bg-green-700 transition shadow">
                        <i class="fas fa-laptop"></i> Ver equipos asignados
                    </a>
                    <a href="mailto:{{ $user->email }}"
                        class="flex items-center gap-2 px-5 py-2 rounded-lg font-semibold bg-gray-200 text-gray-800 hover:bg-gray-300 transition shadow">
                        <i class="fas fa-envelope"></i> Contactar por correo
                    </a>
                </div>
            </div>

            {{-- Historial de Tickets --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-gray-200/50 border border-white/20 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-ticket-alt text-blue-500 text-xl"></i>
                    <h2 class="text-2xl font-bold text-gray-700">Historial de Tickets</h2>
                </div>
                @if($user->tickets->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Asunto</th>
                                <th class="px-4 py-2">Estado</th>
                                <th class="px-4 py-2">Prioridad</th>
                                <th class="px-4 py-2">Creado</th>
                                <th class="px-4 py-2">Actualizado</th>
                                <th class="px-4 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($user->tickets as $ticket)
                            <tr>
                                <td class="px-4 py-2">{{ $ticket->id }}</td>
                                <td class="px-4 py-2">{{ $ticket->subject }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($ticket->status === 'abierto') bg-green-100 text-green-800
                                                @elseif($ticket->status === 'en progreso') bg-yellow-100 text-yellow-800
                                                @elseif($ticket->status === 'cerrado') bg-gray-200 text-gray-700
                                                @else bg-red-100 text-red-800
                                                @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($ticket->priority === 'alta') bg-red-100 text-red-800
                                                @elseif($ticket->priority === 'media') bg-yellow-100 text-yellow-800
                                                @else bg-blue-100 text-blue-800
                                                @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2">{{ $ticket->updated_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('tickets.show', $ticket) }}"
                                        class="text-blue-600 hover:underline font-semibold">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="bg-yellow-50 rounded-lg p-4 flex items-center text-yellow-700 gap-2">
                    <i class="fas fa-info-circle text-lg"></i>
                    <span>Este usuario no ha generado tickets aún.</span>
                </div>
                @endif
            </div>

            {{-- Información de las PCs asignadas --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-gray-200/50 border border-white/20 p-8">
                <div class="flex items-center gap-3 mb-5">
                    <i class="fas fa-laptop text-green-500 text-xl"></i>
                    <h2 class="text-xl font-bold text-gray-700">PCs Asignadas</h2>
                </div>
                @if($user->pc && $user->pc->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    @foreach($user->pc as $computer)
                    <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                        <div><strong>Nombre del PC:</strong> {{ $computer->computer_name ?? 'Sin nombre' }}</div>
                        <div><strong>Serial:</strong> {{ $computer->serial_number ?? 'Sin serial' }}</div>
                        <div><strong>Modelo:</strong> {{ $computer->model ?? 'Sin modelo' }}</div>
                        <div><strong>RAM:</strong> {{ $computer->ram ?? 'Sin RAM' }}</div>
                        <div><strong>Procesador:</strong> {{ $computer->processor ?? 'Sin procesador' }}</div>
                        <div><strong>Sistema Operativo:</strong> {{ $computer->operating_system ?? 'Sin SO' }}</div>
                        <div><strong>Asignado el:</strong> {{ $computer->assigned_date ? \Carbon\Carbon::parse($computer->assigned_date)->format('d/m/Y H:i') : 'N/A' }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-yellow-50 rounded-lg p-4 flex items-center text-yellow-700 gap-2">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                    <span>Este usuario no tiene equipos asignados.</span>
                </div>
                @endif
            </div>

            {{-- Botones de acción mejorados --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-8 mt-8 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-8 py-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 focus:ring-4 focus:ring-gray-200 focus:outline-none">
                    <i class="fas fa-arrow-left text-lg"></i>
                    <span class="text-lg">Volver a la lista de usuarios</span>
                </a>
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="flex items-center justify-center gap-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:ring-4 focus:ring-purple-200 focus:outline-none">
                    <i class="fas fa-edit text-lg"></i>
                    <span class="text-lg">Editar Usuario</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
