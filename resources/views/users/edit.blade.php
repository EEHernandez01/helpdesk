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
                        Editar Usuario
                    </h1>
                    <p class="text-gray-600 text-lg">
                        Modificando información de:
                        <span class="font-semibold text-purple-600">{{ $user->name }}</span>
                    </p>
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
                        <div class="text-sm text-blue-600 font-medium">Email Actual</div>
                        <div class="text-blue-800 font-semibold">{{ $user->email }}</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4">
                        <div class="text-sm text-green-600 font-medium">Rol Actual</div>
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

            {{-- Formulario mejorado --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl shadow-gray-200/50 border border-white/20 overflow-hidden">
                {{-- Header del formulario --}}
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6">
                    <h2 class="text-xl font-semibold text-white flex items-center gap-3">
                        <i class="fas fa-user-edit"></i>
                        Modificar Información
                    </h2>
                </div>

                <form action="{{ auth()->user()->role === 'admin' ? route('admin.users.update', $user) : route('agent.users.update', $user) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        {{-- Información Personal --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Información Personal</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nombre --}}
                                <div class="group">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-user mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        Nombre completo
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                            class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none hover:border-gray-300"
                                            placeholder="Ingresa el nombre completo"
                                            required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i class="fas fa-check text-green-500 opacity-100 transition-opacity duration-200" id="name-check"></i>
                                        </div>
                                    </div>
                                    @error('name')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>

                                {{-- Username --}}
                                <div class="group">
                                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-user-tag mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        Usuario
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                            class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none hover:border-gray-300"
                                            placeholder="Ej: juan.perez"
                                            required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i class="fas fa-check text-green-500 opacity-100 transition-opacity duration-200" id="username-check"></i>
                                        </div>
                                    </div>
                                    @error('username')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="group">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-envelope mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        Correo electrónico
                                    </label>
                                    <div class="relative">
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                            class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none hover:border-gray-300"
                                            placeholder="usuario@ejemplo.com"
                                            required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i class="fas fa-check text-green-500 opacity-100 transition-opacity duration-200" id="email-check"></i>
                                        </div>
                                    </div>
                                    @error('email')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>

                                {{-- ID Empleado --}}
                                <div class="group">
                                    <label for="id_employee" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-id-badge mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        RH ID Empleado
                                    </label>
                                    <input type="text" name="id_employee" id="id_employee" value="{{ old('id_employee', $user->id_employee) }}"
                                        class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none hover:border-gray-300"
                                        placeholder="Ej: EMP001">
                                    @error('id_employee')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>

                                {{-- Compañía --}}
                                <div class="group">
                                    <label for="empresa_id" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-building mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        Compañía
                                    </label>
                                    <select name="empresa_id" id="empresa_id" class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none hover:border-gray-300 cursor-pointer">
                                        <option value="" class="text-gray-400">Selecciona una compañía</option>
                                        @foreach($companies as $company)
                                        <option value="{{ $company->id }}" @if(old('empresa_id', $user->empresa_id) == $company->id) selected @endif>{{ $company->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('empresa_id')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>

                                {{-- Departamento --}}
                                <div class="group">
                                    <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-sitemap mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        Departamento
                                    </label>
                                    <select name="department_id" id="department_id" class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none hover:border-gray-300 cursor-pointer">
                                        <option value="" class="text-gray-400">Selecciona un departamento</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}" @if(old('department_id', $user->department_id) == $department->id) selected @endif>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Equipos de cómputo asignados --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-laptop text-purple-600 text-sm"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Equipos de cómputo asignados</h3>
                                <span class="inline-flex items-center px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">
                                    Opcional
                                </span>
                            </div>

                            <div class="group">
                                <p class="text-sm text-gray-600 mb-3 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-purple-500"></i>
                                    Selecciona uno o varios equipos asignados al usuario.
                                </p>

                                <select id="computers" name="computers[]" multiple
                                    class="block w-full rounded-xl border-2  text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 hover:border-gray-300 cursor-pointer">
                                    @foreach($computers as $computer)
                                    <option value="{{ $computer->id }}"
                                        @if(collect(old('computers', $user->pc->pluck('id') ?? []))->contains($computer->id)) selected @endif>
                                        {{ $computer->computer_name ?? 'Equipo #' . $computer->id }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('computers')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Permisos y Acceso --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-indigo-600 text-sm"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Permisos y Acceso</h3>
                            </div>

                            {{-- Rol --}}
                            <div class="group">
                                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                    <i class="fas fa-user-tag mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                    Nivel de acceso
                                </label>
                                <select name="role" id="role"
                                    class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none hover:border-gray-300 cursor-pointer"
                                    required>
                                    <option value="admin" @if(old('role', $user->role) === 'admin') selected @endif class="text-red-600 font-medium">
                                        🔴 Administrador - Acceso completo
                                    </option>
                                    <option value="agent" @if(old('role', $user->role) === 'agent') selected @endif class="text-orange-600 font-medium">
                                        🟡 Agente - Gestión y supervisión
                                    </option>
                                    <option value="employee" @if(old('role', $user->role) === 'employee') selected @endif class="text-green-600 font-medium">
                                        🟢 Empleado - Acceso básico
                                    </option>
                                </select>
                                @error('role')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Cambio de Contraseña --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-key text-amber-600 text-sm"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Cambio de Contraseña</h3>
                                <span class="inline-flex items-center px-3 py-1 text-xs bg-amber-100 text-amber-700 rounded-full">
                                    Opcional
                                </span>
                            </div>

                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-info-circle text-amber-600 mt-1"></i>
                                    <div class="text-amber-800 text-sm">
                                        <p class="font-medium mb-1">Información sobre el cambio de contraseña:</p>
                                        <p>Deja estos campos en blanco si no deseas cambiar la contraseña actual del usuario.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nueva contraseña --}}
                                <div class="group">
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-lock mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        Nueva contraseña
                                    </label>
                                    <input type="password" name="password" id="password"
                                        class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 hover:border-gray-300"
                                        placeholder="********">
                                    @error('password')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>

                                {{-- Confirmar contraseña --}}
                                <div class="group">
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-purple-600">
                                        <i class="fas fa-lock mr-2 text-gray-400 group-focus-within:text-purple-600"></i>
                                        Confirmar nueva contraseña
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 hover:border-gray-300"
                                        placeholder="********">
                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.users.index') : route('agent.users.index') }}"
                                class="px-6 py-3 rounded-xl border-2 border-gray-200 bg-white text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 flex items-center gap-2 shadow-sm">
                                <i class="fas fa-arrow-left"></i>
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold hover:shadow-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 flex items-center gap-2 shadow-md">
                                <i class="fas fa-save"></i>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
