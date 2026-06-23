<nav x-data="{ open: false }" class="tecsup-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <div class="bg-tecsup-cyan rounded-md px-2 py-1 flex items-center">
                        <span class="text-white font-bold text-sm tracking-widest">TECSUP</span>
                    </div>
                    <span class="hidden sm:block text-white/80 text-sm font-medium">SMER</span>
                </a>

                @auth
                    <div class="hidden sm:flex items-center gap-1 ms-4">
                        @php $rol = Auth::user()->rol->nombre; @endphp

                        @if($rol === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Panel</x-nav-link>
                            <x-nav-link :href="route('admin.tutores')" :active="request()->routeIs('admin.tutores')">Tutores</x-nav-link>
                            <x-nav-link :href="route('admin.importar')" :active="request()->routeIs('admin.importar')">Importar</x-nav-link>
                            <x-nav-link :href="route('admin.asignaciones')" :active="request()->routeIs('admin.asignaciones')">Asignar</x-nav-link>
                            <x-nav-link :href="route('admin.config')" :active="request()->routeIs('admin.config')">Configuracion</x-nav-link>
                        @elseif($rol === 'tutor')
                            <x-nav-link :href="route('tutor.estudiantes')" :active="request()->routeIs('tutor.estudiantes')">Mis estudiantes</x-nav-link>
                        @elseif($rol === 'estudiante')
                            <x-nav-link :href="route('estudiante.estado')" :active="request()->routeIs('estudiante.estado')">Mi estado</x-nav-link>
                            <x-nav-link :href="route('estudiante.notificaciones')" :active="request()->routeIs('estudiante.notificaciones')">Notificaciones</x-nav-link>
                        @endif
                    </div>
                @endauth
            </div>

            @auth
                <div class="hidden sm:flex items-center gap-3">
                    @php $rol = Auth::user()->rol->nombre; @endphp
                    <span class="text-xs px-2 py-1 rounded-full bg-tecsup-cyan/20 text-tecsup-cyan font-semibold capitalize">
                        {{ $rol }}
                    </span>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 text-white/80 hover:text-white text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/10 transition-colors duration-150 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-tecsup-cyan flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Cerrar sesion
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="text-white/70 hover:text-white p-2 rounded-md hover:bg-white/10 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/10 bg-tecsup-dark">
        <div class="pt-2 pb-3 space-y-1 px-2">
            @auth
                @php $rol = Auth::user()->rol->nombre; @endphp
                @if($rol === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Panel</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tutores')" :active="request()->routeIs('admin.tutores')">Tutores</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.importar')" :active="request()->routeIs('admin.importar')">Importar</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.asignaciones')" :active="request()->routeIs('admin.asignaciones')">Asignar</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.config')" :active="request()->routeIs('admin.config')">Configuracion</x-responsive-nav-link>
                @elseif($rol === 'tutor')
                    <x-responsive-nav-link :href="route('tutor.estudiantes')" :active="request()->routeIs('tutor.estudiantes')">Mis estudiantes</x-responsive-nav-link>
                @elseif($rol === 'estudiante')
                    <x-responsive-nav-link :href="route('estudiante.estado')" :active="request()->routeIs('estudiante.estado')">Mi estado</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('estudiante.notificaciones')" :active="request()->routeIs('estudiante.notificaciones')">Notificaciones</x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <div class="pt-3 pb-2 border-t border-white/10 px-4">
                <div class="text-white font-medium text-sm">{{ Auth::user()->name }}</div>
                <div class="text-white/50 text-xs">{{ Auth::user()->email }}</div>
                <div class="mt-2 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">Perfil</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar sesion</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
