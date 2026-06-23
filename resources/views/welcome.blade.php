<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SMER Tecsup</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <main class="min-h-screen flex items-center justify-center px-6">
            <section class="w-full max-w-md bg-white border border-slate-200 rounded-lg shadow-sm p-8 text-center">
                <p class="text-sm font-semibold text-tecsup-cyan uppercase tracking-wide">SMER Tecsup</p>
                <h1 class="mt-3 text-2xl font-bold text-tecsup-dark">Sistema de Monitoreo Estudiantil</h1>
                <p class="mt-3 text-sm text-slate-600">
                    Ingresa con tu cuenta para consultar el panel correspondiente a tu rol.
                </p>

                <div class="mt-6 flex flex-col gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-tecsup-primary">Ir al panel</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-tecsup-primary">Iniciar sesion</a>
                    @endauth
                </div>
            </section>
        </main>
    </body>
</html>
