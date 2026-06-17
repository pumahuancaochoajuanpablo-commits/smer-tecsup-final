<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial de Entrevistas - ') . $estudiante->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($entrevistas->isEmpty())
                        <p class="text-center text-gray-500 py-8">No hay entrevistas registradas</p>
                    @else
                        <div class="space-y-4">
                            @foreach($entrevistas as $e)
                            <div class="border rounded-lg p-4 {{ $e->nivel_riesgo === 'alto' ? 'border-red-300 bg-red-50' : ($e->nivel_riesgo === 'medio' ? 'border-yellow-300 bg-yellow-50' : 'border-green-300 bg-green-50') }}">
                                <div class="flex justify-between items-center">
                                    <div class="font-semibold">{{ $e->fecha }}</div>
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
                                @if($e->observacion)
                                <div class="text-sm mt-1 text-gray-500">
                                    <span class="font-medium">Observación:</span> {{ $e->observacion->texto }}
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                    <a href="{{ route('tutor.estudiantes') }}" class="mt-4 inline-block text-blue-600 hover:underline">&larr; Volver a Mis Estudiantes</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
