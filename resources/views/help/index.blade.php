
<x-app-layout>
<div class="flex min-h-screen bg-gray-50" x-data="{ selected: 'faq' }">
    <!-- Panel lateral (índice) -->
    <aside class="w-64 bg-white border-r p-6 space-y-4">
        <h2 class="text-xl font-bold mb-4">Centro de Ayuda</h2>
        <nav class="space-y-2">
            <button @click="selected = 'faq'" :class="selected === 'faq' ? 'font-bold text-purple-700' : 'text-gray-700'" class="block w-full text-left px-2 py-1 hover:bg-purple-50 rounded">Preguntas Frecuentes</button>
            <button @click="selected = 'guides'" :class="selected === 'guides' ? 'font-bold text-purple-700' : 'text-gray-700'" class="block w-full text-left px-2 py-1 hover:bg-purple-50 rounded">Guías rápidas</button>
            <button @click="selected = 'videos'" :class="selected === 'videos' ? 'font-bold text-purple-700' : 'text-gray-700'" class="block w-full text-left px-2 py-1 hover:bg-purple-50 rounded">Videos tutoriales</button>
            <button @click="selected = 'manual'" :class="selected === 'manual' ? 'font-bold text-purple-700' : 'text-gray-700'" class="block w-full text-left px-2 py-1 hover:bg-purple-50 rounded">Manual de usuario</button>
            <button @click="selected = 'glossary'" :class="selected === 'glossary' ? 'font-bold text-purple-700' : 'text-gray-700'" class="block w-full text-left px-2 py-1 hover:bg-purple-50 rounded">Glosario</button>
            <button @click="selected = 'contact'" :class="selected === 'contact' ? 'font-bold text-purple-700' : 'text-gray-700'" class="block w-full text-left px-2 py-1 hover:bg-purple-50 rounded">Contacto de soporte</button>
            <button @click="selected = 'entries'" :class="selected === 'entries' ? 'font-bold text-purple-700' : 'text-gray-700'" class="block w-full text-left px-2 py-1 hover:bg-purple-50 rounded">Artículos y guías</button>
        </nav>
        @can('create', App\Models\HelpEntry::class)
            <a href="{{ route('help.create') }}" class="block mt-6 bg-blue-500 text-white px-4 py-2 rounded text-center">Crear nueva entrada</a>
        @endcan
    </aside>

    <!-- Panel derecho (contenido) -->
    <main class="flex-1 p-10">
        <div x-show="selected === 'faq'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Preguntas Frecuentes (FAQ)</h2>
                @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent')))
                    <a href="{{ route('help.sections.edit', 'faq') }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                @endif
            </div>
            <ul class="list-disc ml-6">
                <li>¿Cómo creo un ticket?</li>
                <li>¿Cómo hago seguimiento a mi solicitud?</li>
                <li>¿Dónde puedo ver el estado de mi ticket?</li>
            </ul>
        </div>
        <div x-show="selected === 'guides'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Guías rápidas</h2>
                @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent')))
                    <a href="{{ route('help.sections.edit', 'guia') }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                @endif
            </div>
            <ul class="list-disc ml-6">
                <li><a href="#">Crear un ticket</a></li>
                <li><a href="#">Cerrar un ticket</a></li>
                <li><a href="#">Agregar comentarios</a></li>
            </ul>
        </div>
        <div x-show="selected === 'videos'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Videos tutoriales</h2>
                @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent')))
                    <a href="{{ route('help.sections.edit', 'videos') }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                @endif
            </div>
            <ul class="list-disc ml-6">
                <li><a href="#">Video: Introducción al sistema</a></li>
                <li><a href="#">Video: Seguimiento de tickets</a></li>
            </ul>
        </div>
        <div x-show="selected === 'manual'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Manual de usuario</h2>
                @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent')))
                    <a href="{{ route('help.sections.edit', 'manual') }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                @endif
            </div>
            <a href="#" class="text-blue-600 underline">Descargar PDF</a>
        </div>
        <div x-show="selected === 'glossary'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Glosario de términos</h2>
                @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent')))
                    <a href="{{ route('help.sections.edit', 'glosario') }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                @endif
            </div>
            <ul class="list-disc ml-6">
                <li>Ticket: Solicitud de soporte o incidencia.</li>
                <li>Agente: Persona encargada de resolver tickets.</li>
                <li>Administrador: Usuario con permisos de gestión.</li>
            </ul>
        </div>
        <div x-show="selected === 'contact'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Contacto de soporte</h2>
                @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent')))
                    <a href="{{ route('help.sections.edit', 'contacto') }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                @endif
            </div>
            <p>Email: soporte@ejemplo.com</p>
            <p>Teléfono: 123-456-7890</p>
        </div>
        <div x-show="selected === 'entries'">
            <h2 class="text-2xl font-bold mb-4">Artículos y guías del centro de ayuda</h2>
            @foreach($entries as $entry)
                <div class="border p-4 mb-4 rounded shadow">
                    <h3 class="font-bold text-lg">
                        <a href="{{ route('help.show', $entry) }}" class="text-purple-700 hover:underline">
                            {{ $entry->title }}
                        </a>
                    </h3>
                    <p>{{ Str::limit(strip_tags($entry->content), 120) }}</p>
                    <small>Por: {{ $entry->author->name ?? 'Desconocido' }}</small>
                </div>
            @endforeach
        </div>
    </main>
</div>
<!-- Alpine.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>
