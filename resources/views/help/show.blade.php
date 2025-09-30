<x-app-layout>
    <div class="max-w-3xl mx-auto py-10">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-4">{{ $entry->title }}</h1>
            <div class="prose max-w-none mb-6">
                {!! $entry->content !!}
            </div>
            <div class="flex items-center justify-between text-sm text-gray-500">
                <span>Por: {{ $entry->author->name ?? 'Desconocido' }}</span>
                <span>{{ $entry->created_at->format('d/m/Y H:i') }}</span>
                @if($entry->tags)
                    <span>
                        Etiquetas:
                        @foreach($entry->tags as $tag)
                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full mr-1">{{ $tag->name }}</span>
                        @endforeach
                    </span>
                @endif
            </div>
            <a href="{{ route('help.index') }}" class="mt-8 inline-block text-blue-600 hover:underline">← Volver al centro de ayuda</a>
        </div>
    </div>
</x-app-layout>
