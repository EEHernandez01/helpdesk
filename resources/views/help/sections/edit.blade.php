<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Editar sección: {{ ucfirst($type) }}</h1>
        <form method="POST" action="{{ route('help.sections.update', $type) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-semibold mb-2">Título (opcional)</label>
                <input type="text" name="title" value="{{ $section->title ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-2">Contenido</label>
                <input id="content" type="hidden" name="content" value="{{ $section->content ?? '' }}">
                <trix-editor input="content" class="trix-content bg-white border rounded px-3 py-2"></trix-editor>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar cambios</button>
        </form>
        <link rel="stylesheet" type="text/css" href="/node_modules/trix/dist/trix.css">
        <script type="text/javascript" src="/node_modules/trix/dist/trix.umd.min.js"></script>
    </div>
</x-app-layout>
