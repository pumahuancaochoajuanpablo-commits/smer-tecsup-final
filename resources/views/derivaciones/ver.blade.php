<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de Derivación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Información de la Derivación</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">ID Derivación</p>
                                    <p class="font-semibold">#{{ $derivacion->id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Fecha de Derivación</p>
                                    <p class="font-semibold">{{ $derivacion->fecha_derivacion->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Estado</p>
                                    @php
                                        $statusColors = [
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'derivado' => 'bg-blue-100 text-blue-800',
                                            'completado' => 'bg-green-100 text-green-800',
                                            'rechazado' => 'bg-red-100 text-red-800',
                                        ];
                                        $color = $statusColors[$derivacion->estado] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded text-sm font-semibold {{ $color }}">
                                        {{ ucfirst($derivacion->estado) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Información del Estudiante</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Nombre</p>
                                    <p class="font-semibold">{{ $derivacion->estudiante->nombre }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Código</p>
                                    <p class="font-semibold">{{ $derivacion->estudiante->codigo }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Nivel de Riesgo</p>
                                    @php
                                        $riskColor = match($derivacion->estudiante->nivel_riesgo) {
                                            'alto' => 'text-red-600 font-bold',
                                            'medio' => 'text-yellow-600 font-bold',
                                            'bajo' => 'text-green-600 font-bold',
                                            default => 'text-gray-600',
                                        };
                                    @endphp
                                    <p class="{{ $riskColor }}">{{ strtoupper($derivacion->estudiante->nivel_riesgo) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded mb-6">
                        <h3 class="text-lg font-semibold mb-2">Motivo de la Derivación</h3>
                        <p class="text-gray-700">{{ $derivacion->motivo }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded mb-6">
                        <h3 class="text-lg font-semibold mb-2">Descripción Detallada</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $derivacion->descripcion }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Tutor Responsable</h3>
                            <p class="text-gray-700">{{ $derivacion->tutor->nombre }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Responsable de Bienestar</h3>
                            <p class="text-gray-700">{{ $derivacion->responsable_bienestar ?? 'No asignado' }}</p>
                        </div>
                    </div>

                    @if($derivacion->observaciones && count($derivacion->observaciones) > 0)
                        <div class="bg-blue-50 p-4 rounded mb-6">
                            <h3 class="text-lg font-semibold mb-3">Observaciones y Seguimiento</h3>
                            <div class="space-y-3">
                                @foreach($derivacion->observaciones as $obs)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <p class="text-sm text-gray-600">
                                            <strong>{{ $obs['usuario'] }}</strong> - {{ \Carbon\Carbon::parse($obs['fecha'])->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-gray-700 mt-1">{{ $obs['comentario'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->hasRole('admin') || auth()->user()->is_admin)
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mb-6">
                            <h3 class="text-lg font-semibold mb-3">Actualizar Estado de Derivación</h3>
                            <form action="{{ route('derivaciones.actualizar', $derivacion->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nuevo Estado
                                        </label>
                                        <select id="estado" name="estado" class="w-full border border-gray-300 rounded px-3 py-2">
                                            <option value="pendiente" {{ $derivacion->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="derivado" {{ $derivacion->estado === 'derivado' ? 'selected' : '' }}>Derivado</option>
                                            <option value="completado" {{ $derivacion->estado === 'completado' ? 'selected' : '' }}>Completado</option>
                                            <option value="rechazado" {{ $derivacion->estado === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="responsable_bienestar" class="block text-sm font-medium text-gray-700 mb-2">
                                            Responsable de Bienestar
                                        </label>
                                        <input type="text" id="responsable_bienestar" name="responsable_bienestar" 
                                            class="w-full border border-gray-300 rounded px-3 py-2" 
                                            value="{{ $derivacion->responsable_bienestar ?? '' }}">
                                    </div>
                                </div>

                                <div>
                                    <label for="observacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Agregar Observación
                                    </label>
                                    <textarea id="observacion" name="observacion" rows="3" 
                                        class="w-full border border-gray-300 rounded px-3 py-2" 
                                        placeholder="Agrega una observación sobre el seguimiento de esta derivación..."></textarea>
                                </div>

                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                                    Actualizar Derivación
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="flex gap-4">
                        <a href="{{ route('derivaciones.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 font-semibold">
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
