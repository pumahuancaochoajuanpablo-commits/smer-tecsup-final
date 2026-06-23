<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SMER - Tecsup') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 overflow-x-hidden" x-data="{ sidebarOpen: false }">
    <header class="fixed top-0 left-0 right-0 z-50 h-16 bg-white flex items-center justify-between px-4 sm:px-8 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="sm:hidden text-tecsup-dark/70 hover:text-tecsup-dark p-1 rounded focus:outline-none"
                    aria-label="Abrir menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="text-tecsup-cyan font-bold text-base sm:text-xl tracking-wide leading-tight">
                Sistema de Monitoreo Estudiantil
            </span>
        </div>

        @auth
        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
            <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>
            <span class="px-3 py-1 rounded-full bg-tecsup-cyan/10 text-tecsup-cyan font-semibold capitalize">
                {{ Auth::user()->rol->nombre }}
            </span>
        </div>
        @endauth
    </header>

    <div class="flex pt-16 min-h-screen">
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-black/50 sm:hidden"
             style="display:none;">
        </div>

        <aside class="fixed top-[76px] left-3 sm:top-[80px] sm:left-4 bottom-4 z-40 w-[min(18rem,calc(100vw-1.5rem))] sm:w-64 rounded-[24px] sm:rounded-[30px] bg-tecsup-cyan shadow-xl flex flex-col transform transition-transform duration-200 ease-in-out -translate-x-[calc(100%+1.5rem)] sm:translate-x-0"
               :class="{ 'translate-x-0': sidebarOpen, '-translate-x-[calc(100%+1.5rem)]': !sidebarOpen }"
               x-cloak>
            <div class="px-4 py-4 flex items-center justify-center border-b border-white/25">
                <img src="{{ asset('logo-tecsup.png') }}"
                     alt="Tecsup"
                     class="h-9 w-auto object-contain brightness-0 invert">
            </div>

            @auth
            @php $rolNombre = Auth::user()->rol->nombre; @endphp
            <nav class="flex-1 py-3 overflow-y-auto">
                @if($rolNombre === 'tutor')
                    <x-sidebar-link href="{{ route('tutor.dashboard') }}" :active="request()->routeIs('tutor.dashboard')" icon="dashboard">
                        Dashboard
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('tutor.estudiantes') }}" :active="request()->routeIs('tutor.estudiantes')" icon="students">
                        Mis alumnos
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('tutor.observaciones') }}" :active="request()->routeIs('tutor.observaciones')" icon="observations">
                        Observaciones
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('tutor.alertas') }}" :active="request()->routeIs('tutor.alertas')" icon="alert">
                        Alertas
                    </x-sidebar-link>
                @elseif($rolNombre === 'admin')
                    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="dashboard">
                        Dashboard
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('admin.tutores') }}" :active="request()->routeIs('admin.tutores')" icon="students">
                        Tutores
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('admin.importar') }}" :active="request()->routeIs('admin.importar')" icon="import">
                        Importar
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('admin.asignaciones') }}" :active="request()->routeIs('admin.asignaciones')" icon="assign">
                        Asignaciones
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('admin.encuestas.index') }}" :active="request()->routeIs('admin.encuestas*')" icon="observations">
                        Encuestas
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('admin.config') }}" :active="request()->routeIs('admin.config')" icon="config">
                        Configuracion
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('admin.auditoria.index') }}" :active="request()->routeIs('admin.auditoria*')" icon="audit">
                        Auditoria
                    </x-sidebar-link>
                @elseif($rolNombre === 'estudiante')
                    <x-sidebar-link href="{{ route('estudiante.estado') }}" :active="request()->routeIs('estudiante.estado')" icon="dashboard">
                        Mi Estado
                    </x-sidebar-link>
                    <x-sidebar-link href="{{ route('estudiante.notificaciones') }}" :active="request()->routeIs('estudiante.notificaciones')" icon="alert">
                        Notificaciones
                    </x-sidebar-link>
                @endif
            </nav>
            @endauth

            @auth
            <div class="p-4 border-t border-white/25">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full border-2 border-white text-white text-xs font-bold py-2 px-3 rounded-lg hover:bg-white hover:text-tecsup-cyan transition-colors duration-150 uppercase tracking-widest">
                        Cerrar sesion
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <main class="flex-1 sm:ml-[296px] bg-gray-50 min-h-screen flex flex-col">
            <div class="p-3 sm:p-6 flex-1">
                @isset($header)
                @auth
                @php $rolNombre = Auth::user()->rol->nombre; @endphp
                <div class="bg-tecsup-dark text-white rounded-2xl sm:rounded-full px-4 sm:px-6 py-3 mb-5 sm:mb-6 shadow-md flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 [&_h1]:!text-white [&_h2]:!text-white [&_h1]:!font-bold [&_h2]:!font-bold">
                    <div class="text-lg sm:text-lg tracking-wide text-white leading-tight break-words">{{ $header }}</div>
                    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                        <div class="flex items-center gap-2 bg-white/10 rounded-full px-3 py-1.5">
                            <svg class="w-4 h-4 text-white/70 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-white text-sm font-medium hidden sm:inline">{{ Auth::user()->name }}</span>
                            <span class="text-white text-sm font-medium sm:hidden">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </div>
                        <span class="bg-tecsup-cyan text-white text-xs font-bold px-3 py-1 rounded-full capitalize">
                            {{ ucfirst($rolNombre) }}
                        </span>
                    </div>
                </div>
                @endauth
                @endisset

                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
