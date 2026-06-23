<x-app-layout>
    <x-slot name="header">Alertas</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="tecsup-alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-tecsup-dark rounded-xl p-5 flex flex-col gap-1 border-l-4 border-red-500 shadow">
            <span class="text-white/60 text-xs font-semibold uppercase tracking-widest">Riesgo alto sin derivar</span>
            <span class="text-4xl font-bold text-white">{{ $resumen['sin_derivar'] }}</span>
            <span class="text-white/50 text-sm">Accion inmediata requerida</span>
        </div>

        <div class="bg-tecsup-dark rounded-xl p-5 flex flex-col gap-1 border-l-4 border-orange-400 shadow">
            <span class="text-white/60 text-xs font-semibold uppercase tracking-widest">Derivaciones pendientes</span>
            <span class="text-4xl font-bold text-white">{{ $resumen['pendientes'] }}</span>
            <span class="text-white/50 text-sm">Esperando respuesta de Bienestar</span>
        </div>

        <div class="bg-tecsup-dark rounded-xl p-5 flex flex-col gap-1 border-l-4 border-green-500 shadow">
            <span class="text-white/60 text-xs font-semibold uppercase tracking-widest">Resueltas este mes</span>
            <span class="text-4xl font-bold text-white">{{ $resumen['resueltas'] }}</span>
            <span class="text-white/50 text-sm">Casos cerrados</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow border border-tecsup-border mb-6">
        <div class="px-6 py-4 border-b border-tecsup-border flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-8.25 3.75h.008v.008h-.008v-.008z"/>
            </svg>
            <h3 class="font-semibold text-tecsup-dark">Estudiantes en riesgo alto sin derivar</h3>
        </div>

        @if($sinDerivar->isEmpty())
            <p class="text-gray-400 text-sm text-center py-8">No tienes estudiantes en riesgo alto pendientes de derivar.</p>
        @else
            <div class="overflow-x-auto">
                <table class="tecsup-table">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Carrera/Ciclo</th>
                            <th>Ultima entrevista</th>
                            <th>Puntaje</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sinDerivar as $est)
                            <tr>
                                <td class="font-medium text-tecsup-dark">{{ $est->nombre }}</td>
                                <td class="text-gray-500">{{ $est->carrera }}</td>
                                <td class="text-gray-500">{{ \Carbon\Carbon::parse($est->fecha)->format('d/m/Y') }}</td>
                                <td class="text-gray-500">{{ $est->puntaje_total }}</td>
                                <td>
                                    <a href="{{ route('derivaciones.crear', $est->estudiante_id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-600 text-white text-xs font-semibold hover:bg-red-700 transition-colors">
                                        Derivar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow border border-tecsup-border">
        <div class="px-6 py-4 border-b border-tecsup-border flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                <circle cx="12" cy="12" r="9"/>
            </svg>
            <h3 class="font-semibold text-tecsup-dark">Derivaciones en seguimiento</h3>
        </div>

        @if($derivacionesPendientes->isEmpty())
            <p class="text-gray-400 text-sm text-center py-8">No tienes derivaciones pendientes de respuesta.</p>
        @else
            <div class="overflow-x-auto">
                <table class="tecsup-table">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Motivo</th>
                            <th>Fecha de envio</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($derivacionesPendientes as $derivacion)
                            <tr>
                                <td class="font-medium text-tecsup-dark">{{ $derivacion->estudiante->user->name }}</td>
                                <td class="text-gray-500">{{ $derivacion->motivo }}</td>
                                <td class="text-gray-500">{{ optional($derivacion->fecha_derivacion)->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-600">
                                        PENDIENTE
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('tutor.derivaciones.ver', $derivacion->id) }}"
                                       class="btn-tecsup-outline text-xs py-1 px-3">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
