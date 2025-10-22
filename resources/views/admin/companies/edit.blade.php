<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Empresa') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.companies.update', $company) }}"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Logo Actual
                                </label>
                                <div class="flex items-center space-x-4">
                                    <div class="h-16 w-32 flex items-center justify-center border-2 border-gray-200 rounded">
                                        <img src="{{ $company->logo_url }}"
                                            alt="{{ $company->nombre }}"
                                            class="h-full w-full object-contain">
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            {{ $company->logo ? 'Logo personalizado' : 'Logo por defecto' }}

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cambiar Logo
                                </label>
                                <input type="file"
                                    id="logo"
                                    name="logo"
                                    accept="image/*,.svg"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-xs text-gray-500">
                                    Formatos permitidos: JPEG, PNG, JPG, GIF, SVG. Tamaño máximo: 2MB
                                </p>
                                @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Favicon Actual
                                </label>
                                <div class="flex items-center space-x-4">
                                    <div class="h-8 w-8 flex items-center justify-center border border-gray-200 rounded">
                                        <img src="{{ $company->favicon_url }}"
                                            alt="Favicon {{ $company->nombre }}"
                                            class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            {{ $company->favicon ? 'Favicon personalizado' : 'Usando logo como favicon' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cambiar Favicon
                                </label>
                                <input type="file"
                                    id="favicon"
                                    name="favicon"
                                    accept="image/*,.svg,.ico"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-xs text-gray-500">
                                    Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, ICO. Tamaño máximo: 1MB.
                                    <br>Recomendado: 16x16, 32x32 o 64x64 píxeles
                                </p>
                                @error('favicon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre de la Empresa *
                                </label>
                                <input type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="{{ old('nombre', $company->nombre) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $company->email) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <input type="text"
                                    id="telefono"
                                    name="telefono"
                                    value="{{ old('telefono', $company->telefono) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- RFC -->
                            <div>
                                <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">
                                    RFC
                                </label>
                                <input type="text"
                                    id="rfc"
                                    name="rfc"
                                    value="{{ old('rfc', $company->rfc) }}"
                                    maxlength="13"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('rfc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Dirección -->
                            <div>
                                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dirección
                                </label>
                                <input type="text"
                                    id="direccion"
                                    name="direccion"
                                    value="{{ old('direccion', $company->direccion) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Ciudad -->
                            <div>
                                <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ciudad
                                </label>
                                <input type="text"
                                    id="ciudad"
                                    name="ciudad"
                                    value="{{ old('ciudad', $company->ciudad) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('ciudad')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Estado -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        name="activo"
                                        value="1"
                                        {{ old('activo', $company->activo) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Empresa activa</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.companies.index') }}"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Actualizar Empresa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
