<x-app-layout>
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Revisión de entradas de ayuda</h1>
    @foreach($entries as $entry)
        <div class="border p-4 mb-4 rounded shadow">
            <h3 class="font-bold text-lg">{{ $entry->title }}</h3>
            <p>{{ $entry->content }}</p>
            <small>Por: {{ $entry->author->name ?? 'Desconocido' }}</small>
            <form method="POST" action="{{ route('admin.help.updateStatus', $entry) }}" class="mt-2">
                @csrf
                @method('PATCH')
                <select name="status" class="border rounded px-2 py-1">
                    <option value="aprobado">Aprobar</option>
                    <option value="rechazado">Rechazar</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Actualizar</button>
            </form>
        </div>
    @endforeach
</div>
</x-app-layout>
