<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-10">
            <!-- Encabezado -->
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Panel de Administración</h2>
                <p class="text-gray-600 text-lg mt-2">Bienvenido, aquí puedes gestionar y monitorear el sistema de Helpdesk</p>
            </div>

            <!-- Métricas generales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 text-center flex flex-col items-center">
                    <div class="bg-blue-100 p-3 rounded-full mb-2">
                        <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700">Tickets Totales</h3>
                    <p class="mt-2 text-3xl font-bold text-blue-800">{{ $totalTickets }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 text-center flex flex-col items-center">
                    <div class="bg-green-100 p-3 rounded-full mb-2">
                        <i class="fas fa-folder-open text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700">Tickets Abiertos</h3>
                    <p class="mt-2 text-3xl font-bold text-green-800">{{ $nuevoTickets }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 text-center flex flex-col items-center">
                    <div class="bg-purple-100 p-3 rounded-full mb-2">
                        <i class="fas fa-check-circle text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700">Tickets Cerrados</h3>
                    <p class="mt-2 text-3xl font-bold text-purple-800">{{ $cerradoTickets }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 text-center flex flex-col items-center">
                    <div class="bg-yellow-100 p-3 rounded-full mb-2">
                        <i class="fas fa-user-friends text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700">Agentes en Línea</h3>
                    <p class="mt-2 text-3xl font-bold text-yellow-800">{{ $cerradoTickets }}</p>
                </div>
            </div>

            <!-- Accesos rápidos para administrador -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.help.review') }}" class="flex items-center gap-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-6 rounded-2xl shadow-lg hover:from-purple-700 hover:to-indigo-700 transition">
                    <i class="fas fa-clipboard-check text-2xl"></i>
                    <span class="font-semibold text-lg">Revisar entradas de ayuda</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-2xl shadow-lg hover:from-blue-700 hover:to-purple-700 transition">
                    <i class="fas fa-users text-2xl"></i>
                    <span class="font-semibold text-lg">Gestionar Usuarios</span>
                </a>
                <a href="{{ route('admin.stats.index') }}" class="flex items-center gap-4 bg-gradient-to-r from-green-500 to-green-700 text-white p-6 rounded-2xl shadow-lg hover:from-green-600 hover:to-green-800 transition">
                    <i class="fas fa-chart-bar text-2xl"></i>
                    <span class="font-semibold text-lg">Ver Estadísticas</span>
                </a>
                <a href="{{ route('tickets.create') }}" class="flex items-center gap-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-2xl shadow-lg hover:from-yellow-600 hover:to-yellow-700 transition">
                    <i class="fas fa-plus-circle text-2xl"></i>
                    <span class="font-semibold text-lg">Crear Ticket</span>
                </a>
                <a href="{{ route('admin.computers.index') }}" class="flex items-center gap-4 bg-gradient-to-r from-indigo-500 to-indigo-700 text-white p-6 rounded-2xl shadow-lg hover:from-indigo-600 hover:to-indigo-800 transition">
                    <i class="fas fa-laptop text-2xl"></i>
                    <span class="font-semibold text-lg">Ver Equipos</span>
                </a>
                <a href="{{ route('admin.system-status.index') }}" class="flex items-center gap-4 bg-gradient-to-r from-orange-500 to-orange-700 text-white p-6 rounded-2xl shadow-lg hover:from-orange-600 hover:to-orange-800 transition">
                    <i class="fas fa-server text-2xl"></i>
                    <span class="font-semibold text-lg">Estado del Sistema</span>
                </a>
                {{--
                <a href="{{ route('categories.index') }}" class="flex items-center gap-4 bg-gradient-to-r from-pink-500 to-pink-700 text-white p-6 rounded-2xl shadow-lg hover:from-pink-600 hover:to-pink-800 transition">
                    <i class="fas fa-tags text-2xl"></i>
                    <span class="font-semibold text-lg">Categorías</span>
                </a>
                <a href="{{ route('departments.index') }}" class="flex items-center gap-4 bg-gradient-to-r from-gray-500 to-gray-700 text-white p-6 rounded-2xl shadow-lg hover:from-gray-600 hover:to-gray-800 transition">
                    <i class="fas fa-building text-2xl"></i>
                    <span class="font-semibold text-lg">Departamentos</span>
                </a>
                --}}
                <!-- Puedes agregar más accesos rápidos aquí -->
            </div>

            <!-- Herramientas administrativas -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mt-10">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                    <i class="fas fa-tools text-purple-600"></i>
                    Herramientas administrativas
                </h3>
                <div class="flex gap-4 overflow-x-auto pb-2 justify-center">
                    <a href="#" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-xl shadow hover:from-red-600 hover:to-pink-600 transition">
                        <i class="fas fa-bell text-2xl mb-2"></i>
                        <span class="text-sm font-semibold">Notificaciones</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-blue-500 to-blue-700 text-white p-4 rounded-xl shadow hover:from-blue-600 hover:to-blue-800 transition">
                        <i class="fas fa-chart-pie text-2xl mb-2"></i>
                        <span class="text-sm font-semibold">Reportes</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-gray-700 to-gray-900 text-white p-4 rounded-xl shadow hover:from-gray-800 hover:to-black transition">
                        <i class="fas fa-clipboard-list text-2xl mb-2"></i>
                        <span class="text-sm font-semibold">Logs</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-green-500 to-green-700 text-white p-4 rounded-xl shadow hover:from-green-600 hover:to-green-800 transition">
                        <i class="fas fa-cogs text-2xl mb-2"></i>
                        <span class="text-sm font-semibold">Configuración</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-yellow-500 to-yellow-700 text-white p-4 rounded-xl shadow hover:from-yellow-600 hover:to-yellow-800 transition">
                        <i class="fas fa-database text-2xl mb-2"></i>
                        <span class="text-sm font-semibold">Mantenimiento</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-indigo-500 to-indigo-700 text-white p-4 rounded-xl shadow hover:from-indigo-600 hover:to-indigo-800 transition">
                        <a href="{{ route('help.index') }}" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-indigo-500 to-indigo-700 text-white p-4 rounded-xl shadow hover:from-indigo-600 hover:to-indigo-800 transition">
                            <i class="fas fa-book text-2xl mb-2"></i>
                            <span class="text-sm font-semibold">Centro de Ayuda</span>
                        </a>
                    <a href="#" class="flex flex-col items-center justify-center min-w-[140px] bg-gradient-to-r from-pink-600 to-purple-700 text-white p-4 rounded-xl shadow hover:from-pink-700 hover:to-purple-800 transition">
                        <i class="fas fa-user-shield text-2xl mb-2"></i>
                        <span class="text-sm font-semibold">Roles y permisos</span>
                    </a>
                </div>
            </div>

            <!-- Tickets recientes -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden mt-10">
                <div class="border-b px-6 py-4 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-700">Últimos Tickets</h3>
                    <a href="{{ route('tickets.index') }}" class="text-sm font-semibold text-blue-600 hover:underline flex items-center gap-1"><i class="fas fa-list"></i> Ver todos</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">ID</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Asunto</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Estado</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500">Creado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($recentTickets as $ticket)
                            <tr>
                                <td class="px-4 py-2 text-gray-700">{{ $ticket->id }}</td>
                                <td class="px-4 py-2 text-gray-900">{{ $ticket->subject }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $ticket->status_class }}">
                                        {{ $ticket->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-500">{{ $ticket->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                <!-- Feedback de usuarios -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden mt-10">
                    <div class="border-b px-6 py-4 bg-yellow-50 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-700 flex items-center gap-2">
                            <i class="fas fa-star text-yellow-500"></i> Feedback de Usuarios
                        </h3>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                            Promedio: {{ number_format($avgRating, 2) }} / 5 &bull; Total: {{ $feedbackCount }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500">Ticket</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500">Usuario</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500">Calificación</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500">Comentario</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($recentFeedback as $feedback)
                                <tr>
                                    <td class="px-4 py-2 text-blue-700 font-semibold">#{{ $feedback->ticket->id ?? '-' }}</td>
                                    <td class="px-4 py-2 text-gray-900">{{ $feedback->user->username ?? 'Usuario' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="text-yellow-600 font-bold">{{ $feedback->rating }} <i class="fas fa-star"></i></span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">{{ $feedback->comment ?? '-' }}</td>
                                    <td class="px-4 py-2 text-gray-500">{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-6 px-6 text-center text-gray-500">No hay feedback registrado aún.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
