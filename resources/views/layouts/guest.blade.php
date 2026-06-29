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
    <body class="font-sans antialiased bg-tecsup-dark">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 px-4"
             style="background: linear-gradient(135deg, #0C2333 0%, #0e3050 60%, #0CB9D7 100%);">

            <div class="mb-6 w-full sm:max-w-md">
                <div class="bg-tecsup-dark rounded-full px-6 py-3 shadow-lg text-center">
                    <span class="text-white font-bold text-lg tracking-wide">
                        Sistema de Monitoreo Estudiantil
                    </span>
                </div>
            </div>

            <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="bg-tecsup-dark px-8 py-5 flex items-center gap-4">
                    <img src="{{ asset('logo-tecsup.png') }}" alt="Tecsup" class="h-8 w-auto object-contain shrink-0">

                    @php
                        $tituloAuth = match(true) {
                            request()->routeIs('register') => 'Crear cuenta',
                            request()->routeIs('password.request') => 'Recuperar contrasena',
                            request()->routeIs('password.reset') => 'Restablecer contrasena',
                            request()->routeIs('password.confirm') => 'Confirmar contrasena',
                            request()->routeIs('verification.notice') => 'Verificar correo',
                            default => 'Iniciar sesion',
                        };
                    @endphp

                    <h2 class="text-white font-bold text-lg tracking-wide border-l border-white/20 pl-4">
                        {{ $tituloAuth }}
                    </h2>
                </div>

                <div class="px-8 py-8">
                    {{ $slot }}
                </div>
            </div>

            <p class="mt-6 text-white/50 text-xs text-center">
                &copy; {{ date('Y') }} Tecsup - Todos los derechos reservados
            </p>
        </div>
    </body>
</html>
