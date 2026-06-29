<x-app-layout>
    <x-slot name="header">Detalle de entrevista</x-slot>

    @php
        $riesgoClase = [
            'alto' => 'bg-red-100 text-red-800 border-red-200',
            'medio' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'bajo' => 'bg-green-100 text-green-800 border-green-200',
        ][$entrevista->nivel_riesgo] ?? 'bg-gray-100 text-gray-800 border-gray-200';

        $indicadores = [
            'Rendimiento academico' => $entrevista->acad_2,
            'Bienestar emocional' => $entrevista->emoc_2,
            'Trabajo en equipo' => $entrevista->soc_2,
            'Comunicacion efectiva' => $entrevista->econ_2,
            'Trabajo y economia' => $entrevista->fam_2,
            'Estres y estado emocional' => $entrevista->salud_2,
        ];
    @endphp

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-tecsup-border p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-tecsup-dark">Entrevista registrada</h2>
                    <p class="text-sm text-gray-500 mt-1">Consulta de la encuesta guardada en el sistema.</p>
                </div>

                <span class="inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-semibold {{ $riesgoClase }}">
                    Riesgo {{ ucfirst($entrevista->nivel_riesgo) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-lg border border-tecsup-border p-4">
                    <p class="text-xs font-semibold uppercase text-gray-500">Estudiante</p>
                    <p class="font-semibold text-tecsup-dark mt-1">{{ $entrevista->asignacion->estudiante->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $entrevista->asignacion->estudiante->codigo }}</p>
                </div>

                <div class="rounded-lg border border-tecsup-border p-4">
                    <p class="text-xs font-semibold uppercase text-gray-500">Tutor</p>
                    <p class="font-semibold text-tecsup-dark mt-1">{{ $entrevista->asignacion->tutor->user->name }}</p>
                </div>

                <div class="rounded-lg border border-tecsup-border p-4">
                    <p class="text-xs font-semibold uppercase text-gray-500">Fecha</p>
                    <p class="font-semibold text-tecsup-dark mt-1">{{ $entrevista->fecha->format('d/m/Y') }}</p>
                </div>

                <div class="rounded-lg border border-tecsup-border p-4">
                    <p class="text-xs font-semibold uppercase text-gray-500">Puntaje</p>
                    <p class="font-semibold text-tecsup-dark mt-1">{{ $entrevista->puntaje_total }}/18</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-tecsup-border p-6">
            <h3 class="text-lg font-semibold text-tecsup-dark mb-4">Resultados por indicador</h3>

            <div class="rounded-lg border border-tecsup-border overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-tecsup-dark text-white">
                            <th class="border border-tecsup-dark px-4 py-3 text-left">Indicador</th>
                            <th class="border border-tecsup-dark px-4 py-3 text-center w-32">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($indicadores as $indicador => $valor)
                            <tr class="odd:bg-white even:bg-tecsup-light/40">
                                <td class="border border-tecsup-border px-4 py-3">{{ $indicador }}</td>
                                <td class="border border-tecsup-border px-4 py-3 text-center font-semibold">{{ $valor }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($entrevista->recomendacion)
            <div class="bg-white rounded-xl shadow-sm border border-tecsup-border p-6">
                <h3 class="text-lg font-semibold text-tecsup-dark mb-3">Recomendacion</h3>
                <p class="text-gray-700 leading-relaxed">{{ $entrevista->recomendacion->acciones }}</p>
            </div>
        @endif

        @if($entrevista->observaciones->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-tecsup-border p-6">
                <h3 class="text-lg font-semibold text-tecsup-dark mb-3">Observaciones</h3>
                <div class="space-y-3">
                    @foreach($entrevista->observaciones as $observacion)
                        <div class="rounded-lg border border-tecsup-border bg-gray-50 p-4">
                            <p class="text-sm font-semibold text-tecsup-dark">Observacion {{ $loop->iteration }}</p>
                            <p class="text-gray-700 mt-1">{{ $observacion->texto }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn-tecsup-outline">Volver al panel</a>
        </div>
    </div>
</x-app-layout>
