<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Estado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Información Personal</h3>
                    <p><span class="text-gray-600">Nombre:</span> {{ Auth::user()->name }}</p>
                    <p><span class="text-gray-600">Código:</span> {{ $estudiante->codigo }}</p>
                    <p><span class="text-gray-600">Carrera:</span> {{ $estudiante->carrera }}</p>
                    @if($asignacion)
                    <p><span class="text-gray-600">Tutor:</span> {{ $asignacion->tutor->user->name }}</p>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Último Nivel de Riesgo</h3>
                    @if($ultimaEntrevista)
                        @php
                            $color = match($ultimaEntrevista->nivel_riesgo) {
                                'alto' => 'text-red-600 bg-red-100',
                                'medio' => 'text-yellow-600 bg-yellow-100',
                                'bajo' => 'text-green-600 bg-green-100',
                                default => 'text-gray-600 bg-gray-100',
                            };
                        @endphp
                        <div class="text-center p-4 rounded {{ $color }}">
                            <div class="text-4xl font-bold">{{ strtoupper($ultimaEntrevista->nivel_riesgo) }}</div>
                            <div class="text-sm mt-2">Puntaje: {{ $ultimaEntrevista->puntaje_total }}</div>
                            <div class="text-sm">{{ $ultimaEntrevista->fecha }}</div>
                        </div>
                        @if($recomendacion)
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm">
                            <strong>Recomendación:</strong> {{ $recomendacion->acciones }}
                        </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">Aún no tienes entrevistas registradas</p>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Indicadores</h3>
                    @if($ultimaEntrevista)
                        @php
                            $indicadores = [
                                'Académico' => $ultimaEntrevista->acad_2,
                                'Emocional' => $ultimaEntrevista->emoc_2,
                                'Social' => $ultimaEntrevista->soc_2,
                                'Económico' => $ultimaEntrevista->econ_2,
                                'Familiar' => $ultimaEntrevista->fam_2,
                                'Salud' => $ultimaEntrevista->salud_2,
                            ];
                        @endphp
                        @foreach($indicadores as $label => $valor)
                        <div class="mb-2">
                            <div class="flex justify-between text-sm">
                                <span>{{ $label }}</span>
                                <span>{{ $valor }}/5</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($valor/5)*100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">Sin datos</p>
                    @endif
                </div>
            </div>

            @if($historial->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 p-6">
                <h3 class="font-semibold text-lg mb-4">Últimas Entrevistas</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Puntaje</th>
                            <th class="py-2">Nivel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historial as $h)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2">{{ $h->fecha }}</td>
                            <td class="py-2">{{ $h->puntaje_total }}</td>
                            <td class="py-2">
                                @php
                                    $c = match($h->nivel_riesgo) {'alto'=>'text-red-600','medio'=>'text-yellow-600','bajo'=>'text-green-600',default=>''};
                                @endphp
                                <span class="{{ $c }} font-medium">{{ strtoupper($h->nivel_riesgo) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
