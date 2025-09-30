<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-user text-indigo-500"></i> Perfil de Usuario
        </h1>
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-700">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="text-xl font-semibold text-gray-800">{{ $user->name }}</div>
                    <div class="text-gray-500">{{ $user->email }}</div>
                    <div class="text-sm text-gray-600">Rol: <span class="font-semibold">{{ ucfirst($user->role) }}</span></div>
                    <div class="text-sm text-gray-600">Departamento: {{ $user->department->name ?? 'Sin departamento' }}</div>
                    <div class="text-sm text-gray-600">Empresa: {{ $user->company->nombre ?? 'Sin empresa' }}</div>
                </div>
            </div>
            <a href="{{ route('agent.users.edit', $user->id) }}" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Editar usuario
            </a>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-ticket-alt text-blue-500"></i> Tickets creados
            </h2>
            @if($user->tickets->count())
                <ul class="divide-y divide-gray-100">
                    @foreach($user->tickets as $ticket)
                        <li class="py-2 flex justify-between items-center">
                            <span>#{{ $ticket->id }} - {{ $ticket->title }}</span>
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ ucfirst($ticket->status) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No ha creado tickets.</p>
            @endif
        </div>
    </div>
</x-app-layout>
