<nav class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center">
                                    <div class="h-12 w-12 flex items-center justify-center">
                <img src="{{ \App\Helpers\CompanyHelper::getCurrentUserCompanyLogo() }}"
                     alt="{{ \App\Helpers\CompanyHelper::getCurrentUserCompanyName() }}"
                     class="max-h-full max-w-full object-contain" />
            </div>
        </a>
        <!-- Links -->
        <div class="hidden sm:flex space-x-8">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>
            @if(Auth::user()->role === 'admin')
                <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                    Tickets
                </x-nav-link>
                <x-nav-link :href="route('admin.computers.index')" :active="request()->routeIs('admin.computers.*')">
                    Equipos
                </x-nav-link>
                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    Usuarios
                </x-nav-link>
                <x-nav-link :href="route('admin.companies.index')" :active="request()->routeIs('admin.companies.*')">
                    Empresas
                </x-nav-link>
            @elseif(Auth::user()->role === 'agent')
                <x-nav-link :href="route('agent.tickets.index')" :active="request()->routeIs('agent.tickets.*')">
                    Mis Tickets
                </x-nav-link>
                <x-nav-link :href="route('agent.computers.index')" :active="request()->routeIs('agent.computers.*')">
                    Equipos
                </x-nav-link>
                <x-nav-link :href="route('agent.users.index')" :active="request()->routeIs('agent.users.*')">
                    Usuarios
                </x-nav-link>
            @else
                <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                    Mis Tickets
                </x-nav-link>
            @endif
        </div>
        <!-- Usuario -->
        <div class="flex items-center space-x-3">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 font-bold flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->username ?? Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    {{-- <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Perfil') }}
                    </x-dropdown-link> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Cerrar sesión') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white shadow-sm">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <!-- Más links aquí si lo deseas -->
        </div>
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 font-bold flex items-center justify-center">
                    {{ strtoupper(substr(Auth::user()->username ?? Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                {{-- <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link> --}}
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
