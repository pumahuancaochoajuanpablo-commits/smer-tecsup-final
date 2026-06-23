<x-app-layout>
    <x-slot name="header">Encuestas de Riesgo</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('recomendacion'))
        <div class="tecsup-alert-info mb-4"><strong>Recomendacion automatica:</strong> {{ session('recomendacion') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between gap-4 mb-4 flex-wrap">
            <h3 class="text-lg font-semibold">Alumnos asignados para encuesta</h3>
            <input type="text" id="buscar-encuesta" class="tecsup-input max-w-xs" placeholder="Buscar alumno o tutora">
        </div>

        <div class="overflow-x-auto">
            <table class="tecsup-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Tutora</th>
                        <th>Carrera / Semestre / Grupo</th>
                        <th>Ultimo riesgo</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $asignacion)
                        @php
                            $ultima = $asignacion->entrevistas->sortByDesc('fecha')->first();
                        @endphp
                        <tr class="fila-encuesta">
                            <td class="texto-encuesta font-medium text-tecsup-dark">{{ $asignacion->estudiante->user->name }}</td>
                            <td class="texto-encuesta">{{ $asignacion->tutor->user->name }}</td>
                            <td>{{ $asignacion->estudiante->carrera }} / {{ $asignacion->estudiante->ciclo ?? 'N/A' }} / {{ $asignacion->estudiante->grupo ?? 'N/A' }}</td>
                            <td>{{ $ultima ? strtoupper($ultima->nivel_riesgo) . ' (' . $ultima->puntaje_total . ')' : 'SIN ENCUESTA' }}</td>
                            <td>
                                <a href="{{ route('admin.encuestas.crear', $asignacion) }}" class="btn-tecsup-success text-xs py-1 px-3">
                                    Registrar encuesta
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-8">Primero asigna alumnos a una tutora.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('buscar-encuesta')?.addEventListener('input', function () {
                const filtro = this.value.toLowerCase();
                document.querySelectorAll('.fila-encuesta').forEach((fila) => {
                    fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
                });
            });
        </script>
    @endpush
</x-app-layout>
