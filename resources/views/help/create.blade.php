<x-app-layout>

    <head>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    </head>

    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Crear nueva entrada de ayuda</h1>
        <form method="POST" action="{{ route('help.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-2">Título</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-2">Contenido</label>
                <input id="content" type="hidden" name="content">
                <trix-editor input="content" class="trix-content bg-white border rounded px-3 py-2"></trix-editor>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Enviar para revisión</button>
        </form>
        <!-- Trix Editor -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.js"></script>
    </div>
</x-app-layout>
