<x-app-layout>
    <x-slot name="header">Observaciones</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="tecsup-alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white rounded-xl shadow border border-tecsup-border mb-6">
        <div class="px-6 py-4 border-b border-tecsup-border">
            <div class="flex items-center gap-2 text-tecsup-dark font-semibold mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                Alumnos asignados
            </div>

            <input
                type="text"
                id="buscador"
                placeholder="Buscar por nombre..."
                class="tecsup-input w-full"
                onkeyup="filtrarEstudiantes()">
        </div>

        <div class="overflow-x-auto">
            <table class="tecsup-table" id="tablaEstudiantes">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Carrera/Ciclo</th>
                        <th>Ultimo riesgo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estudiantes as $est)
                        @php
                            $badgeClass = match($est->nivel_riesgo) {
                                'alto' => 'badge-alto',
                                'medio' => 'badge-medio',
                                'bajo' => 'badge-bajo',
                                default => 'badge-bajo',
                            };
                        @endphp

                        <tr class="fila-estudiante transition-colors duration-150" data-entrevista-id="{{ $est->entrevista_id }}">
                            <td class="nombre-est font-medium text-tecsup-dark">{{ $est->nombre }}</td>
                            <td class="text-gray-500">{{ $est->carrera }}</td>
                            <td><span class="{{ $badgeClass }}">{{ strtoupper($est->nivel_riesgo) }}</span></td>
                            <td>
                                <button
                                    type="button"
                                    onclick="seleccionarEstudiante('{{ $est->entrevista_id }}')"
                                    class="btn-tecsup-success text-xs">
                                    Seleccionar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-400 py-6">
                                Aun no tienes estudiantes con entrevistas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow border border-tecsup-border">
            <div class="px-6 py-4 border-b border-tecsup-border">
                <h3 class="font-semibold text-tecsup-dark">Observaciones existentes</h3>
                <p class="text-sm text-gray-500">
                    Estudiante seleccionado:
                    <span id="nombreEstudiante">Ninguno</span>
                </p>
            </div>

            <div class="p-6">
                <div id="observacionesPrevias" class="space-y-3 text-sm text-gray-600">
                    <p class="text-gray-400">Selecciona una encuesta para ver sus observaciones.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-tecsup-border">
            <div class="px-6 py-4 border-b border-tecsup-border">
                <h3 class="font-semibold text-tecsup-dark">Nueva observacion</h3>
                <p class="text-sm text-gray-500">Se agregara a la misma encuesta seleccionada.</p>
            </div>

            <form action="{{ route('tutor.observaciones.guardar') }}" method="POST">
                @csrf
                <input type="hidden" id="entrevista_id" name="entrevista_id">

                <div class="p-6">
                    <textarea
                        name="observacion"
                        rows="8"
                        required
                        class="tecsup-input w-full resize-none"
                        placeholder="Escribe aqui la nueva observacion del estudiante..."></textarea>
                </div>

                <div class="px-6 pb-6 flex justify-end">
                    <button type="submit" class="btn-tecsup-success">Guardar observacion</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const estudiantes = @json($estudiantes->keyBy('entrevista_id'));

            function seleccionarEstudiante(entrevistaId)
            {
                const estudiante = estudiantes[entrevistaId];
                if (!estudiante) return;

                document.getElementById('entrevista_id').value = entrevistaId;
                document.getElementById('nombreEstudiante').innerText = estudiante.nombre;

                document.querySelectorAll('.fila-estudiante').forEach(fila => {
                    const seleccionada = fila.dataset.entrevistaId === entrevistaId;
                    fila.classList.toggle('bg-cyan-50', seleccionada);
                    fila.classList.toggle('ring-2', seleccionada);
                    fila.classList.toggle('ring-tecsup-cyan', seleccionada);
                    fila.classList.toggle('opacity-45', !seleccionada);
                });

                renderObservaciones(estudiante.observaciones || []);
            }

            function renderObservaciones(observaciones)
            {
                const contenedor = document.getElementById('observacionesPrevias');
                contenedor.innerHTML = '';

                if (!observaciones.length) {
                    contenedor.innerHTML = '<p class="text-gray-400">Esta encuesta aun no tiene observaciones.</p>';
                    return;
                }

                observaciones.forEach((observacion, index) => {
                    const bloque = document.createElement('div');
                    bloque.className = 'border border-tecsup-border rounded-lg p-3 bg-gray-50';

                    const titulo = document.createElement('p');
                    titulo.className = 'font-semibold text-tecsup-dark';
                    titulo.innerText = `Observacion ${index + 1}:`;

                    const texto = document.createElement('p');
                    texto.className = 'mt-1 whitespace-pre-wrap';
                    texto.innerText = observacion.texto;

                    const fecha = document.createElement('p');
                    fecha.className = 'mt-2 text-xs text-gray-400';
                    fecha.innerText = observacion.fecha || '';

                    bloque.appendChild(titulo);
                    bloque.appendChild(texto);
                    bloque.appendChild(fecha);
                    contenedor.appendChild(bloque);
                });
            }

            function filtrarEstudiantes()
            {
                const filtro = document.getElementById('buscador').value.toLowerCase();

                document.querySelectorAll('.fila-estudiante').forEach(fila => {
                    const nombreEl = fila.querySelector('.nombre-est');
                    if (!nombreEl) return;

                    const nombre = nombreEl.innerText.toLowerCase();
                    fila.style.display = nombre.includes(filtro) ? '' : 'none';
                });
            }
        </script>
    @endpush
</x-app-layout>
