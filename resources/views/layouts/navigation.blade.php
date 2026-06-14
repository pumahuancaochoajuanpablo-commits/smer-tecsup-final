<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
                @auth
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @php $rol = Auth::user()->rol->nombre; @endphp

                    @if($rol === 'admin')
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('admin.tutores')" :active="request()->routeIs('admin.tutores')">
                        Tutores
                    </x-nav-link>
                    <x-nav-link :href="route('admin.importar')" :active="request()->routeIs('admin.importar')">
                        Importar
                    </x-nav-link>
                    <x-nav-link :href="route('admin.asignaciones')" :active="request()->routeIs('admin.asignaciones')">
                        Asignar
                    </x-nav-link>
                    <x-nav-link :href="route('admin.config')" :active="request()->routeIs('admin.config')">
                        Configuración
                    </x-nav-link>
                    @elseif($rol === 'tutor')
                    <x-nav-link :href="route('tutor.estudiantes')" :active="request()->routeIs('tutor.estudiantes')">
                        Mis Estudiantes
                    </x-nav-link>
                    @elseif($rol === 'estudiante')
                    <x-nav-link :href="route('estudiante.estado')" :active="request()->routeIs('estudiante.estado')">
                        Mi Estado
                    </x-nav-link>
                    <x-nav-link :href="route('estudiante.notificaciones')" :active="request()->routeIs('estudiante.notificaciones')">
                        Notificaciones
                    </x-nav-link>
                    @endif
                </div>
                @endauth
            </div>

            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
            @php $rol = Auth::user()->rol->nombre; @endphp
            @if($rol === 'admin')
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.tutores')" :active="request()->routeIs('admin.tutores')">Tutores</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.importar')" :active="request()->routeIs('admin.importar')">Importar</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.asignaciones')" :active="request()->routeIs('admin.asignaciones')">Asignar</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.config')" :active="request()->routeIs('admin.config')">Configuración</x-responsive-nav-link>
            @elseif($rol === 'tutor')
            <x-responsive-nav-link :href="route('tutor.estudiantes')" :active="request()->routeIs('tutor.estudiantes')">Mis Estudiantes</x-responsive-nav-link>
            @elseif($rol === 'estudiante')
            <x-responsive-nav-link :href="route('estudiante.estado')" :active="request()->routeIs('estudiante.estado')">Mi Estado</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('estudiante.notificaciones')" :active="request()->routeIs('estudiante.notificaciones')">Notificaciones</x-responsive-nav-link>
            @endif
            @endauth
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name ?? '' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email ?? '' }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
