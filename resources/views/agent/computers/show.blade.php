<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-6">
                    Detalles del Equipo</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nombre del Equipo</label>
                            <p class="text-lg text-gray-800">{{ $computer->computer_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Número de Serie</label>
                            <p class="text-lg text-gray-800">{{ $computer->serial_number }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Modelo</label>
                            <p class="text-lg text-gray-800">{{ $computer->model }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">RAM</label>
                            <p class="text-lg text-gray-800">{{ $computer->ram }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Procesador</label>
                            <p class="text-lg text-gray-800">{{ $computer->processor }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sistema Operativo</label>
                            <p class="text-lg text-gray-800">{{ $computer->operating_system }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Empresa</label>
                        <p class="text-lg text-gray-800">{{ $computer->company->nombre ?? 'No asignada' }}</p>
                    </div>
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-500">Departamento</label>
                        <p class="text-lg text-gray-800">{{ $computer->department->name ?? 'No asignado' }}</p>
                    </div>
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-500">Usuario Asignado</label>
                        <p class="text-lg text-gray-800">{{ $computer->user->name ?? 'No asignado' }}</p>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('agent.computers.edit', $computer) }}"
                        class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition shadow-lg">
                        <i class="fas fa-edit mr-2"></i>Editar
                    </a>
                    <a href="{{ route('agent.computers.index') }}"
                        class="px-6 py-2 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
