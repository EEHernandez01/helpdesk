<x-app-layout>
    <div class="max-w-2xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-user-edit text-indigo-500"></i> Editar Usuario
        </h1>
        <form method="POST" action="{{ route('agent.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded-lg px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border rounded-lg px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-lg px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                <select name="department_id" class="w-full border rounded-lg px-4 py-2">
                    <option value="">Sin departamento</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @if($user->department_id == $department->id) selected @endif>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                <select name="empresa_id" class="w-full border rounded-lg px-4 py-2">
                    <option value="">Sin empresa</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" @if($user->empresa_id == $company->id) selected @endif>{{ $company->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select name="role" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="admin" @if($user->role == 'admin') selected @endif>Administrador</option>
                    <option value="agent" @if($user->role == 'agent') selected @endif>Agente</option>
                    <option value="employee" @if($user->role == 'employee') selected @endif>Empleado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="active" @if($user->status == 'active') selected @endif>Activo</option>
                    <option value="inactive" @if($user->status == 'inactive') selected @endif>Inactivo</option>
                    <option value="suspended" @if($user->status == 'suspended') selected @endif>Suspendido</option>
                </select>
            </div>
            <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-save mr-2"></i> Guardar cambios
            </button>
        </form>
    </div>
</x-app-layout>
