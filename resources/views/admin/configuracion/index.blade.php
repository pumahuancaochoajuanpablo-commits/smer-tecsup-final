<x-app-layout>
    <x-slot name="header">Configuracion de Riesgo</x-slot>

    @php
        $base = $parametros->first();
        $bajo = old('umbral_bajo_global', $base->umbral_bajo ?? 7);
        $medio = old('umbral_medio_global', $base->umbral_medio ?? 8);
        $alto = old('umbral_alto_global', $base->umbral_alto ?? 14);
    @endphp

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="tecsup-alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-2">Semaforo de Nivel de Riesgo</h3>
            <p class="text-sm text-gray-600 mb-5">La encuesta suma 6 indicadores. Cada respuesta vale: Alto = 3, Medio = 2, Bajo = 1.</p>

            <form method="POST" action="{{ route('admin.config.guardar') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf

                <div>
                    <label class="tecsup-label">Bajo hasta</label>
                    <input type="number" name="umbral_bajo_global" value="{{ $bajo }}" min="1" max="18" class="tecsup-input" required>
                    <p class="text-xs text-gray-500 mt-1">Recomendado: 7</p>
                </div>

                <div>
                    <label class="tecsup-label">Medio desde</label>
                    <input type="number" name="umbral_medio_global" value="{{ $medio }}" min="1" max="18" class="tecsup-input" required>
                    <p class="text-xs text-gray-500 mt-1">Recomendado: 8</p>
                </div>

                <div>
                    <label class="tecsup-label">Alto desde</label>
                    <input type="number" name="umbral_alto_global" value="{{ $alto }}" min="1" max="18" class="tecsup-input" required>
                    <p class="text-xs text-gray-500 mt-1">Recomendado: 14</p>
                </div>

                <div class="md:col-span-3">
                    <button type="submit" class="btn-tecsup-primary">Guardar Configuracion</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold mb-3">Lectura actual</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b pb-2">
                    <span>Bajo</span>
                    <strong>1 a {{ $bajo }}</strong>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span>Medio</span>
                    <strong>{{ $medio }} a {{ $alto - 1 }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Alto</span>
                    <strong>{{ $alto }} a 18</strong>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
