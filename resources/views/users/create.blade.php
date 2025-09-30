<x-app-layout>
    {{-- FontAwesome para iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-8">
            {{-- Encabezado mejorado --}}
            <div class="text-center space-y-4">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-blue-200 mb-4">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">
                        Crear Nuevo Usuario
                    </h1>
                    <p class="text-gray-600 text-lg">Completa la información para agregar un nuevo usuario al sistema</p>
                </div>
            </div>

            {{-- Formulario mejorado --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl shadow-gray-200/50 border border-white/20 overflow-hidden">
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-8">
                    @csrf

                    {{-- Información Personal --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 pb-4 mb-8 border-b-2 border-gray-100">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Información Personal</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {{-- Nombre completo - Ocupa más espacio --}}
                            <div class="lg:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-user mr-2 text-gray-500"></i>
                                    Nombre completo
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300"
                                    placeholder="Ingresa el nombre completo del usuario"
                                    required>
                                @error('name')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            {{-- Username --}}
                            <div>
                                <label for="username" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-user-tag mr-2 text-gray-500"></i>
                                    Usuario
                                </label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300"
                                    placeholder="Ej: juan.perez"
                                    required>
                                @error('username')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            {{-- Email - Ocupa todo el ancho --}}
                            <div class="lg:col-span-3">
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-envelope mr-2 text-gray-500"></i>
                                    Correo electrónico
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300"
                                    placeholder="usuario@ejemplo.com"
                                    required>
                                @error('email')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            {{-- ID Empleado --}}
                            <div>
                                <label for="id_employee" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-id-badge mr-2 text-gray-500"></i>
                                    RH ID Empleado
                                </label>
                                <input type="text" name="id_employee" id="id_employee" value="{{ old('id_employee') }}"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300"
                                    placeholder="Ej: EMP001">
                                @error('id_employee')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            {{-- Fecha de contratación --}}
                            <div>
                                <label for="hire_date" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>
                                    Fecha de contratación
                                </label>
                                <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300">
                                @error('hire_date')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            {{-- Estado en línea --}}
                            <div>
                                <label for="is_online" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-circle mr-2 text-gray-500"></i>
                                    Estado en línea
                                </label>
                                <select name="is_online" id="is_online"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300 cursor-pointer">
                                    <option value="0" @if(old('is_online')==='0') selected @endif>🔴 Desconectado</option>
                                    <option value="1" @if(old('is_online')==='1') selected @endif>🟢 En línea</option>
                                </select>
                                @error('is_online')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Permisos y Acceso --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 pb-4 mb-8 border-b-2 border-gray-100">
                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shield-alt text-indigo-600"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Permisos y Seguridad</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Primera columna: Rol y Estado --}}
                            <div class="space-y-6">
                                {{-- Rol --}}
                                <div>
                                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-user-tag mr-2 text-gray-500"></i>
                                        Nivel de acceso
                                    </label>
                                    <select name="role" id="role"
                                        class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300 cursor-pointer"
                                        required>
                                        <option value="" class="text-gray-400">Selecciona el nivel de acceso</option>
                                        <option value="admin" @if(old('role')==='admin' ) selected @endif class="text-red-600 font-medium">
                                            🔴 Administrador - Acceso completo
                                        </option>
                                        <option value="agent" @if(old('role')==='agent' ) selected @endif class="text-orange-600 font-medium">
                                            🟡 Agente - Gestión y supervisión
                                        </option>
                                        <option value="employee" @if(old('role')==='employee' ) selected @endif class="text-green-600 font-medium">
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

                                {{-- Estado --}}
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-toggle-on mr-2 text-gray-500"></i>
                                        Estado de la cuenta
                                    </label>
                                    <select name="status" id="status"
                                        class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300 cursor-pointer"
                                        required>
                                        <option value="active" @if(old('status')==='active' ) selected @endif>✅ Activo</option>
                                        <option value="inactive" @if(old('status')==='inactive' ) selected @endif>⏸️ Inactivo</option>
                                        <option value="suspended" @if(old('status')==='suspended' ) selected @endif>🚫 Suspendido</option>
                                    </select>
                                    @error('status')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Segunda columna: Contraseñas --}}
                            <div class="space-y-6">
                                {{-- Contraseña --}}
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-lock mr-2 text-gray-500"></i>
                                        Contraseña
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password" id="password"
                                            class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 pr-12 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300"
                                            placeholder="Mínimo 8 caracteres"
                                            required>
                                        <button type="button" onclick="togglePassword('password')"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                            <i class="fas fa-eye" id="password-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>

                                {{-- Confirmar contraseña --}}
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-lock mr-2 text-gray-500"></i>
                                        Confirmar contraseña
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 pr-12 text-gray-800 placeholder-gray-400 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300"
                                            placeholder="Repite la contraseña"
                                            required>
                                        <button type="button" onclick="togglePassword('password_confirmation')"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                            <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Asignación de Recursos --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 pb-4 mb-8 border-b-2 border-gray-100">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-green-600"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Asignación de Recursos</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Compañía --}}
                            <div>
                                <label for="empresa_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-building mr-2 text-gray-500"></i>
                                    Compañía
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="empresa_id" id="empresa_id"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300 cursor-pointer"
                                    required>
                                    <option value="" class="text-gray-400">Selecciona una compañía</option>
                                    @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @if(old('empresa_id')==$company->id) selected @endif>
                                        🏢 {{ $company->nombre }}
                                    </option>
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
                            <div>
                                <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-sitemap mr-2 text-gray-500"></i>
                                    Departamento
                                </label>
                                <select name="department_id" id="department_id"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300 cursor-pointer">
                                    <option value="" class="text-gray-400">Selecciona un departamento</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @if(old('department_id')==$department->id) selected @endif>
                                        🏢 {{ $department->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            {{-- Equipo de cómputo --}}
                            <div>
                                <label for="computer_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-laptop mr-2 text-gray-500"></i>
                                    Equipo de cómputo
                                    <span class="inline-flex items-center px-2 py-1 ml-2 text-xs bg-blue-100 text-blue-700 rounded-full">
                                        Opcional
                                    </span>
                                </label>
                                <select name="computer_id" id="computer_id"
                                    class="block w-full h-12 rounded-xl border-2 border-gray-200 px-4 text-gray-800 transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none hover:border-gray-300 cursor-pointer">
                                    <option value="" class="text-gray-400">Sin asignar por ahora</option>
                                    @foreach($computers as $computer)
                                    <option value="{{ $computer->id }}" @if(old('computer_id')==$computer->id) selected @endif>
                                        💻 {{ $computer->computer_name }} - {{ $computer->serial_number }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Puedes asignar el equipo más tarde desde el panel de administración
                                </p>
                                @error('computer_id')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex flex-col sm:flex-row-reverse gap-4 pt-8 mt-8 border-t-2 border-gray-100">
                        <button type="submit"
                            class="flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-200 focus:outline-none">
                            <i class="fas fa-user-plus text-lg"></i>
                            <span class="text-lg">Crear Usuario</span>
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-8 py-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 focus:ring-4 focus:ring-gray-200 focus:outline-none">
                            <i class="fas fa-arrow-left text-lg"></i>
                            <span class="text-lg">Cancelar</span>
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tips Card mejorada --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900 mb-3 text-lg">💡 Consejos importantes</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-blue-800 text-sm">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-blue-600"></i>
                                <span>El usuario recibirá un correo de bienvenida</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                                <span>Los administradores tienen acceso completo</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-laptop text-blue-600"></i>
                                <span>Los equipos se pueden reasignar después</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-key text-blue-600"></i>
                                <span>Use contraseñas seguras de 8+ caracteres</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript mejorado --}}
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-eye');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Mejorar la experiencia de usuario
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus en el primer campo
            document.getElementById('name').focus();

            // Validación visual en tiempo real
            const inputs = document.querySelectorAll('input[required], select[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('border-red-300');
                        this.classList.remove('border-green-300');
                    } else {
                        this.classList.add('border-green-300');
                        this.classList.remove('border-red-300');
                    }
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('border-red-300')) {
                        this.classList.remove('border-red-300');
                        this.classList.add('border-gray-200');
                    }
                });
            });

            // Validación de email en tiempo real
            const emailField = document.getElementById('email');
            emailField.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.classList.add('border-yellow-400');
                    this.classList.remove('border-green-300');
                } else if (this.value && emailRegex.test(this.value)) {
                    this.classList.add('border-green-300');
                    this.classList.remove('border-yellow-400');
                }
            });

            // Confirmación de contraseña
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');

            function checkPasswordMatch() {
                if (confirmPassword.value && password.value !== confirmPassword.value) {
                    confirmPassword.classList.add('border-red-400');
                    confirmPassword.classList.remove('border-green-300');
                } else if (confirmPassword.value && password.value === confirmPassword.value) {
                    confirmPassword.classList.add('border-green-300');
                    confirmPassword.classList.remove('border-red-400');
                }
            }

            password.addEventListener('input', checkPasswordMatch);
            confirmPassword.addEventListener('input', checkPasswordMatch);
        });
    </script>
</x-app-layout>
