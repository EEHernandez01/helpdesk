<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-100 py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-8">

            <!-- Header principal -->
            <div class="text-center space-y-4">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-600 rounded-2xl shadow-lg shadow-purple-200 mb-4 text-white font-bold text-3xl">
                    {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">
                    Portal de Soporte TI
                </h1>
                <p class="text-gray-600 text-lg">
                    ¡Bienvenido(a), <span class="font-semibold text-purple-600">{{ auth()->user()->name }}</span>!
                </p>
                <p class="text-gray-500">¿En qué podemos ayudarte hoy?</p>
            </div>

            <!-- Acciones rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Ver tickets -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <a href="{{ route('tickets.index') }}" class="block p-6">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl shadow-lg">
                                <i class="fas fa-ticket-alt text-2xl text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-800">Mis Tickets</h3>
                                <p class="text-sm text-gray-600 mt-1">Gestiona todos tus casos de soporte</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Crear ticket -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <a href="{{ route('tickets.create') }}" class="block p-6">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl shadow-lg">
                                <i class="fas fa-plus text-2xl text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-800">Nuevo Ticket</h3>
                                <p class="text-sm text-gray-600 mt-1">Solicita asistencia para un problema</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Centro de ayuda (visible solo para admin y agentes) -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'agent')
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <a href="{{ route('help.index') }}" class="block p-6">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl shadow-lg">
                                <i class="fas fa-question-circle text-2xl text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-800">Centro de Ayuda</h3>
                                <p class="text-sm text-gray-600 mt-1">Consulta preguntas frecuentes</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            </div>

            <!-- Estado del sistema y contacto -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Estado del sistema -->
                <x-system-status-widget :showManageButton="true" />

                <!-- Contacto directo -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl">
                            <i class="fas fa-headset text-xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Contacto Directo</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Para recibir asistencia inmediata, comunícate con nosotros:</p>
                    <div class="space-y-3">
                        <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-100">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2 rounded-lg mr-3">
                                <i class="fas fa-phone text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Teléfono</p>
                                <p class="font-semibold text-gray-800">(55) 5972 8130 Ext. 232</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-100">
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-2 rounded-lg mr-3">
                                <i class="fas fa-envelope text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-semibold text-gray-800">soporte.ti@genbio.com.mx</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-3 rounded-xl">
                        <i class="fas fa-lightbulb text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Preguntas Frecuentes</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach([
                    ['¿Cuánto tiempo tarda la resolución de un ticket?', 'El tiempo promedio de resolución es dependiendo de la complejidad del problema.', 'fas fa-clock'],
                    ['¿Puedo cancelar un ticket enviado?', 'Sí, puedes cerrar un ticket en cualquier momento desde la vista de detalles del ticket agregando un comentario.', 'fas fa-times-circle'],
                    ['¿Cómo puedo actualizar la información de un ticket?', 'Puedes añadir comentarios o actualizar la información desde la sección de detalles del ticket.', 'fas fa-edit'],
                    ['¿Qué información debo incluir en mi ticket?', 'Incluye descripción detallada del problema, pasos para reproducirlo y capturas de pantalla si es posible.', 'fas fa-info-circle']
                    ] as [$titulo, $contenido, $icono])
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border-l-4 border-purple-500">
                        <div class="flex items-start gap-3">
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-2 rounded-lg mt-1">
                                <i class="{{ $icono }} text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">{{ $titulo }}</h4>
                                <p class="text-sm text-gray-600">{{ $contenido }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Botón de acción principal -->
            <div class="text-center">
                <a href="{{ route('tickets.create') }}"
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-8 py-4 rounded-2xl shadow-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 font-semibold text-lg">
                    <i class="fas fa-plus text-xl"></i>
                    Crear Nuevo Ticket de Soporte
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

