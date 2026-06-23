<x-app-layout>
    <x-slot name="header">Mis Estudiantes</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('recomendacion'))
        <div class="tecsup-alert-info mb-4"><strong>Recomendacion:</strong> {{ session('recomendacion') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow border border-tecsup-border">
        <div class="px-6 py-4 border-b border-tecsup-border flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-2 text-tecsup-dark font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
                Alumnos asignados
            </div>
            <input type="text"
                   id="buscador"
                   placeholder="Buscar por nombre..."
                   class="tecsup-input max-w-xs text-sm"
                   onkeyup="filtrarEstudiantes()">
        </div>

        <div class="overflow-x-auto">
            <table class="tecsup-table" id="tablaEstudiantes">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Carrera / Ciclo / Grupo</th>
                        <th>Codigo</th>
                        <th>Ultimo riesgo</th>
                        <th>Ultima encuesta</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estudiantes as $est)
                    @php
                        $badgeClass = match($est->nivel_riesgo) {
                            'alto' => 'badge-alto',
                            'medio' => 'badge-medio',
                            'bajo' => 'badge-bajo',
                            default => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500',
                        };
                    @endphp
                    <tr class="fila-estudiante">
                        <td class="font-medium text-tecsup-dark nombre-est">{{ $est->nombre }}</td>
                        <td class="text-gray-500">
                            {{ $est->carrera ?: 'Sin carrera' }}
                            {{ $est->ciclo ? ' - Ciclo ' . $est->ciclo : '' }}
                            {{ $est->grupo ? ' - Grupo ' . $est->grupo : '' }}
                        </td>
                        <td class="text-gray-400 text-xs font-mono">{{ $est->codigo }}</td>
                        <td>
                            <span class="{{ $badgeClass }}">{{ $est->nivel_riesgo ? strtoupper($est->nivel_riesgo) : 'SIN DATOS' }}</span>
                        </td>
                        <td class="text-gray-400 text-sm">
                            {{ $est->fecha_ultima ? \Carbon\Carbon::parse($est->fecha_ultima)->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('tutor.entrevista', $est->id) }}"
                                   class="btn-tecsup-success text-xs py-1 px-3">
                                    Encuesta
                                </a>
                                <a href="{{ route('tutor.historial', $est->id) }}"
                                   class="btn-tecsup-outline text-xs py-1 px-3">
                                    Historial
                                </a>
                                @if($est->nivel_riesgo === 'alto')
                                <a href="{{ route('derivaciones.crear', $est->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-600 text-white text-xs font-semibold hover:bg-red-700 transition-colors">
                                    Derivar
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-10">
                            No tienes estudiantes asignados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        function filtrarEstudiantes() {
            const filtro = document.getElementById('buscador').value.toLowerCase();
            document.querySelectorAll('.fila-estudiante').forEach(fila => {
                const nombre = fila.querySelector('.nombre-est').textContent.toLowerCase();
                fila.style.display = nombre.includes(filtro) ? '' : 'none';
            });
        }
    </script>
    @endpush
</x-app-layout>
