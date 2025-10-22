<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-8">
            <div class="text-center space-y-4 mb-8">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                    Equipos de Cómputo</h2>
                <p class="text-gray-600 text-lg">Búsqueda y gestión avanzada de equipos</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-4">
                    <a href="{{ route('admin.computers.create') }}"
                        class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-xl shadow hover:from-blue-700 hover:to-purple-700 transition font-semibold">
                        <i class="fas fa-plus mr-2"></i>Agregar Equipo
                    </a>
                </div>
            </div>
            <!-- Filtros y búsqueda avanzada -->
            <form method="GET" action="{{ route('admin.computers.index') }}"
                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-gray-200/50 border border-white/20 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar equipo</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, serial..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Procesador</label>
                        <input type="text" name="processor" value="{{ request('processor') }}" placeholder="Ej: i5, Ryzen..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Empresa</label>
                        <input type="text" name="company" value="{{ request('company') }}" placeholder="Empresa..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                        <input type="text" name="department" value="{{ request('department') }}" placeholder="Departamento..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ route('admin.computers.index') }}"
                            class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition mt-2 text-center block">
                            <i class="fas fa-times mr-2"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
            <!-- Tabla de equipos -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Procesador</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departamento</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($computers as $computer)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-6">{{ $computer->computer_name }}</td>
                            <td class="py-3 px-6">{{ $computer->serial_number }}</td>
                            <td class="py-3 px-6">{{ $computer->model }}</td>
                            <td class="py-3 px-6">{{ $computer->processor }}</td>
                            <td class="py-3 px-6">{{ $computer->company->nombre ?? '-' }}</td>
                            <td class="py-3 px-6">{{ $computer->department->name ?? '-' }}</td>
                            <td class="py-3 px-6">{{ $computer->user->name ?? '-' }}</td>
                            <td class="py-3 px-6">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.computers.show', $computer->id) }}"
                                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 py-1 rounded-xl hover:from-blue-600 hover:to-purple-700 transition text-xs font-semibold">Ver</a>
                                    <a href="{{ route('admin.computers.edit', $computer->id) }}"
                                        class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-3 py-1 rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition text-xs font-semibold">Editar</a>
                                    <form action="{{ route('admin.computers.destroy', $computer->id) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar equipo?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1 rounded-xl hover:from-red-600 hover:to-red-700 transition text-xs font-semibold">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-6 px-6 text-center text-gray-500">No hay equipos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                <div class="inline-flex space-x-2 bg-white/80 backdrop-blur-sm rounded-xl shadow border border-gray-200 px-4 py-2">
                    @if ($computers->onFirstPage())
                    <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-angle-left"></i>
                    </span>
                    @else
                    <a href="{{ $computers->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transition">
                        <i class="fas fa-angle-left"></i>
                    </a>
                    @endif

                    @foreach ($computers->getUrlRange(1, $computers->lastPage()) as $page => $url)
                    @if ($page == $computers->currentPage())
                    <span class="px-3 py-2 rounded-lg bg-blue-600 text-white font-bold shadow">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-blue-100 transition">{{ $page }}</a>
                    @endif
                    @endforeach

                    @if ($computers->hasMorePages())
                    <a href="{{ $computers->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transition">
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
</x-app-layout>
