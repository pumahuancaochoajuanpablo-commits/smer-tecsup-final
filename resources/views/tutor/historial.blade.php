<x-app-layout>
    <x-slot name="header">
        Historial de entrevistas - {{ $estudiante->user->name }}
    </x-slot>

    <div class="bg-white rounded-xl shadow border border-tecsup-border p-6">
        @if($entrevistas->isEmpty())
            <p class="text-center text-gray-500 py-8">No hay entrevistas registradas</p>
        @else
            <div class="space-y-4">
                @foreach($entrevistas as $e)
                    <div class="border rounded-lg p-4 {{ $e->nivel_riesgo === 'alto' ? 'border-red-300 bg-red-50' : ($e->nivel_riesgo === 'medio' ? 'border-yellow-300 bg-yellow-50' : 'border-green-300 bg-green-50') }}">
                        <div class="flex justify-between items-center">
                            <div class="font-semibold">{{ $e->fecha->format('d/m/Y') }}</div>
                            @php
                                $color = match($e->nivel_riesgo) {
                                    'alto' => 'bg-red-500',
                                    'medio' => 'bg-yellow-500',
                                    'bajo' => 'bg-green-500',
                                    default => 'bg-gray-400',
                                };
                            @endphp
                            <span class="{{ $color }} text-white px-2 py-1 rounded-full text-sm">{{ strtoupper($e->nivel_riesgo) }}</span>
                        </div>

                        <div class="grid grid-cols-3 md:grid-cols-6 gap-2 mt-2 text-sm">
                            <div>Acad: {{ $e->acad_2 }}</div>
                            <div>Emoc: {{ $e->emoc_2 }}</div>
                            <div>Soc: {{ $e->soc_2 }}</div>
                            <div>Econ: {{ $e->econ_2 }}</div>
                            <div>Fam: {{ $e->fam_2 }}</div>
                            <div>Salud: {{ $e->salud_2 }}</div>
                        </div>

                        <div class="text-sm text-gray-600 mt-1">Puntaje: {{ $e->puntaje_total }}</div>

                        @if($e->documento)
                            <div class="text-sm mt-1">
                                <span class="font-medium">Documento:</span>
                                <a href="{{ asset('storage/' . $e->documento) }}" target="_blank" class="text-blue-600 hover:underline">Ver evidencia</a>
                            </div>
                        @endif

                        @if($e->observaciones->isNotEmpty())
                            <div class="text-sm mt-3 text-gray-600 space-y-2">
                                @foreach($e->observaciones as $observacion)
                                    <div class="bg-white/70 border border-gray-200 rounded p-2">
                                        <span class="font-medium">Observacion {{ $loop->iteration }}:</span>
                                        <span>{{ $observacion->texto }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('tutor.estudiantes') }}" class="mt-4 inline-block text-blue-600 hover:underline">
            &larr; Volver a Mis Estudiantes
        </a>
    </div>
</x-app-layout>
