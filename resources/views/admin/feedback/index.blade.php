@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6 flex items-center gap-2">
        <i class="fas fa-star text-yellow-500"></i> Feedback de Usuarios
    </h2>
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Agente</label>
            <select name="agent_id" class="px-4 py-2 border rounded-lg">
                <option value="">Todos</option>
                @foreach($agents as $agent)
                <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->username }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
            <input type="date" name="from" value="{{ request('from') }}" class="px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
            <input type="date" name="to" value="{{ request('to') }}" class="px-4 py-2 border rounded-lg">
        </div>
        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-semibold">Filtrar</button>
        <a href="{{ route('admin.feedback.export', request()->all()) }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold">Exportar CSV</a>
    </form>
    <div class="mb-4">
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
                @forelse($feedbacks as $feedback)
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
    <div class="mt-6">
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection
