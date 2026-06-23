<x-app-layout>
    <x-slot name="header">Asignar Tutorias</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="tecsup-alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 lg:col-span-1">
            <h3 class="text-lg font-semibold mb-4">Nueva Asignacion</h3>
            <form method="POST" action="{{ route('admin.asignaciones.guardar') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="tecsup-label">Tutora</label>
                    <select name="tutor_id" class="tecsup-input" required>
                        <option value="">Seleccionar tutora</option>
                        @foreach($tutores as $tutor)
                            <option value="{{ $tutor->id }}">{{ $tutor->user->name }} ({{ $tutor->codigo }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <label class="tecsup-label mb-0">Estudiantes</label>
                        <span class="text-xs text-gray-500">Marca uno o varios</span>
                    </div>
                    <input type="text" id="buscar-estudiante" class="tecsup-input mb-3" placeholder="Buscar estudiante o codigo">

                    <div class="border border-gray-200 rounded-lg divide-y max-h-80 overflow-y-auto" id="lista-estudiantes">
                        @forelse($estudiantes as $estudiante)
                            <label class="estudiante-opcion flex items-start gap-3 p-3 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="estudiantes[]" value="{{ $estudiante->id }}" class="mt-1 rounded border-gray-300 text-tecsup-cyan">
                                <span>
                                    <span class="block text-sm font-semibold text-tecsup-dark estudiante-texto">{{ $estudiante->user->name }} {{ $estudiante->codigo }}</span>
                                    <span class="block text-xs text-gray-500">{{ $estudiante->codigo }} - {{ $estudiante->carrera }} {{ $estudiante->ciclo ? ' / Semestre ' . $estudiante->ciclo : '' }}</span>
                                </span>
                            </label>
                        @empty
                            <p class="p-4 text-sm text-gray-500">No quedan estudiantes sin asignar.</p>
                        @endforelse
                    </div>
                </div>

                <button type="submit" class="btn-tecsup-primary w-full justify-center">Asignar Tutoria</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Asignaciones Actuales</h3>
            <div class="overflow-x-auto">
                <table class="tecsup-table">
                    <thead>
                        <tr>
                            <th>Tutora</th>
                            <th>Estudiante</th>
                            <th>Carrera / Semestre / Grupo</th>
                            <th>Fecha Inicio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignaciones as $asignacion)
                            <tr>
                                <td>{{ $asignacion->tutor->user->name }}</td>
                                <td>{{ $asignacion->estudiante->user->name }}</td>
                                <td>{{ $asignacion->estudiante->carrera }} / {{ $asignacion->estudiante->ciclo ?? 'N/A' }} / {{ $asignacion->estudiante->grupo ?? 'N/A' }}</td>
                                <td>{{ $asignacion->fecha_inicio->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-8">Sin asignaciones</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('buscar-estudiante')?.addEventListener('input', function () {
                const filtro = this.value.toLowerCase();
                document.querySelectorAll('.estudiante-opcion').forEach((opcion) => {
                    const texto = opcion.querySelector('.estudiante-texto').textContent.toLowerCase();
                    opcion.style.display = texto.includes(filtro) ? 'flex' : 'none';
                });
            });
        </script>
    @endpush
</x-app-layout>
