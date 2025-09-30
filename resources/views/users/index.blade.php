<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-8">
            <div class="text-center space-y-4 mb-8">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                    Gestión de Usuarios</h2>
                <p class="text-gray-600 text-lg">Administra todos los usuarios del sistema</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-4">
                    <button id="viewToggle"
                        class="inline-block bg-gray-600 text-white px-4 py-2 rounded-xl shadow hover:bg-gray-700 transition font-semibold">
                        <i class="fas fa-table mr-2"></i>Vista Tabla
                    </button>
                    @if($isAdmin)
                        <a href="{{ route('admin.users.create') }}"
                            class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-xl shadow hover:from-blue-700 hover:to-purple-700 transition font-semibold">
                            <i class="fas fa-plus mr-2"></i>Agregar Usuario
                        </a>
                        <a href="{{ route('admin.tickets.create-user') }}"
                            class="inline-block bg-gradient-to-r from-green-500 to-green-700 text-white px-6 py-2 rounded-xl shadow hover:from-green-600 hover:to-green-800 transition font-semibold">
                            <i class="fas fa-ticket-alt"></i> Crear Ticket para Usuario
                        </a>
                    @else
                        <a href="{{ route('agent.tickets.create-user') }}"
                            class="inline-block bg-gradient-to-r from-green-500 to-green-700 text-white px-6 py-2 rounded-xl shadow hover:from-green-600 hover:to-green-800 transition font-semibold">
                            <i class="fas fa-ticket-alt"></i> Crear Ticket para Usuario
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filtros y Búsqueda (formulario GET) -->
            <form method="GET" action="{{ $isAdmin ? route('admin.users.index') : route('agent.users.index') }}"
                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-gray-200/50 border border-white/20 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar usuario</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, email..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por rol</label>
                        <select name="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" {{ request('role') == '' ? 'selected' : '' }}>Todos los roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                            <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Todos</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ $isAdmin ? route('admin.users.index') : route('agent.users.index') }}"
                            class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition mt-2 text-center block">
                            <i class="fas fa-times mr-2"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50/80 backdrop-blur-sm border border-blue-200 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-medium">Total Usuarios</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $users->total() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50/80 backdrop-blur-sm border border-green-200 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-medium">Usuarios Activos</p>
                            <p class="text-2xl font-bold text-green-800">
                                {{ $users->where('status', 'active')->count() ?: $users->count() }}
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-user-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50/80 backdrop-blur-sm border border-yellow-200 rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-600 text-sm font-medium">Admins</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ $users->where('role', 'admin')->count() }}
                            </p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-user-shield text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de Usuarios -->
            <div id="usersGrid"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                @forelse($users as $user)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 user-card flex flex-col h-full min-h-[400px]"
                    data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-
                    data-role="{{ $user->role }}" data-status="{{ $user->status ?? 'active' }}"
                    data-departament="{{ $user->departament_id }}">

                    <!-- Header de la tarjeta -->
                    <div class="p-6 pb-4">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start space-x-3 min-w-0 flex-1">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-gray-800 break-words leading-tight text-sm">
                                        {{ $user->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">ID: {{ $user->id }}</p>
                                    @if ($user->company)
                                    <p class="text-xs text-purple-600 font-semibold">
                                        {{ $user->company->nombre }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                @if ($user->status === 'active' || !isset($user->status))
                                <span class="w-3 h-3 bg-green-500 rounded-full" title="Activo"></span>
                                @else
                                <span class="w-3 h-3 bg-red-500 rounded-full" title="Inactivo"></span>
                                @endif
                            </div>
                        </div>

                        <!-- Información del usuario -->
                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope w-4 mr-2"></i>
                                <span class="truncate">{{ $user->email }}</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <i class="fas fa-user-tag w-4 mr-2"></i>
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium
                                            @if ($user->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->role === 'agent') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            @if ($user->department)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-building w-4 mr-2"></i>
                                <span class="truncate">{{ $user->department->name }}</span>
                            </div>
                            @endif
                            <!-- Equipo asignado -->
                            @if ($user->pc->isNotEmpty())
                            <div class="bg-gray-50 rounded-lg p-3 mt-3">
                                <div class="flex items-center text-sm font-medium text-gray-700">
                                    <i class="fas fa-laptop w-4 mr-2"></i>
                                    Equipos ({{ $user->pc->count() }})
                                </div>
                            </div>
                            @else
                            <div class="bg-yellow-50 rounded-lg p-3 mt-3">
                                <div class="flex items-center text-sm text-yellow-700">
                                    <i class="fas fa-exclamation-triangle w-4 mr-2"></i>
                                    Sin equipo asignado
                                </div>
                            </div>
                            @endif

                            <!-- Última conexión -->
                            @if ($user->last_login_at)
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock w-3 mr-2"></i>
                                Último acceso: {{ $user->last_login_at->diffForHumans() }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="border-t border-gray-100 p-4 mt-auto">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ $isAdmin ? route('admin.users.show', $user->id) : route('agent.users.show', $user->id) }}"
                                class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 h-10 rounded-xl text-center flex items-center justify-center hover:from-blue-600 hover:to-purple-700 transition text-xs font-medium">
                                <i class="fas fa-eye mr-1"></i>Ver
                            </a>
                            <a href="{{ $isAdmin ? route('admin.users.edit', $user->id) : route('agent.users.edit', $user->id) }}"
                                class="flex-1 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-3 h-10 rounded-xl text-center flex items-center justify-center hover:from-yellow-600 hover:to-yellow-700 transition text-xs font-medium">
                                <i class="fas fa-edit mr-1"></i>Editar
                            </a>
                        </div>
                        @if($isAdmin)
                        <div class="flex flex-wrap gap-2 mt-2">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');"
                                class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-3 h-10 rounded-xl flex items-center justify-center hover:from-red-600 hover:to-red-700 transition text-xs font-medium">
                                    <i class="fas fa-trash mr-1"></i>Eliminar
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow p-12 text-center">
                        <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay usuarios registrados</h3>
                        <p class="text-gray-500 mb-6">Comienza agregando tu primer usuario al sistema</p>
                        <a href="{{ route('admin.users.create') }}"
                            class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow hover:from-blue-700 hover:to-purple-700 transition font-semibold">
                            <i class="fas fa-plus mr-2"></i>Agregar Usuario
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Vista de tabla (oculta por defecto) -->
            <div id="usersTable" class="hidden bg-white/80 backdrop-blur-sm rounded-2xl shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Correo</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Equipo</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Compañía</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-6">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3 flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-medium text-gray-900 break-words leading-tight">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="py-3 px-6">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium
                                            @if ($user->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->role === 'agent') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-sm text-gray-900">
                                @if ($user->equipment && $user->equipment->count() > 0)
                                <div class="space-y-1">
                                    @foreach ($user->equipment->take(2) as $equipment)
                                    <div class="text-xs">{{ $equipment->serial_number }}</div>
                                    @endforeach
                                    @if ($user->equipment->count() > 2)
                                    <div class="text-xs text-blue-600">
                                        +{{ $user->equipment->count() - 2 }} más</div>
                                    @endif
                                </div>
                                @else
                                <span class="text-gray-400">Sin equipo</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-sm text-gray-900">
                                {{ $user->company?->nombre ?? 'Sin compañía' }}
                            </td>
                            <td class="py-3 px-6">
                                @if ($user->status === 'active' || !isset($user->status))
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Activo
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactivo
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ $isAdmin ? route('admin.users.show', $user->id) : route('agent.users.show', $user->id) }}"
                                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 py-1 rounded-xl hover:from-blue-600 hover:to-purple-700 transition text-xs font-semibold">Detalles</a>
                                    <a href="{{ $isAdmin ? route('admin.users.edit', $user->id) : route('agent.users.edit', $user->id) }}"
                                        class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-3 py-1 rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition text-xs font-semibold">Editar</a>
                                    @if($isAdmin)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1 rounded-xl hover:from-red-600 hover:to-red-700 transition text-xs font-semibold">Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 px-6 text-center text-gray-500">No hay usuarios
                                registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación personalizada -->
            <div class="mt-8 flex justify-center">
                <div class="inline-flex space-x-2 bg-white/80 backdrop-blur-sm rounded-xl shadow border border-gray-200 px-4 py-2">
                    @if ($users->onFirstPage())
                    <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-angle-left"></i>
                    </span>
                    @else
                    <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transition">
                        <i class="fas fa-angle-left"></i>
                    </a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if ($page == $users->currentPage())
                    <span class="px-3 py-2 rounded-lg bg-blue-600 text-white font-bold shadow">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-blue-100 transition">{{ $page }}</a>
                    @endif
                    @endforeach

                    @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transition">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    @else
                    <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para funcionalidad (solo toggle de vista) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewToggle = document.getElementById('viewToggle');
            const usersGrid = document.getElementById('usersGrid');
            const usersTable = document.getElementById('usersTable');
            viewToggle.addEventListener('click', function() {
                if (usersGrid.style.display === 'none') {
                    usersGrid.style.display = 'grid';
                    usersTable.style.display = 'none';
                    viewToggle.innerHTML = '<i class="fas fa-table mr-2"></i>Vista Tabla';
                } else {
                    usersGrid.style.display = 'none';
                    usersTable.style.display = 'block';
                    viewToggle.innerHTML = '<i class="fas fa-th-large mr-2"></i>Vista Grid';
                }
            });
        });
    </script>

    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
