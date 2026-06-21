<x-app-layout>
    <x-slot name="header">
        Observaciones
    </x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- LISTA DE ALUMNOS YA ENTREVISTADOS --}}
    <div class="bg-white rounded-xl shadow border border-tecsup-border mb-6">

        <div class="px-6 py-4 border-b border-tecsup-border">

            <div class="flex items-center gap-2 text-tecsup-dark font-semibold mb-3">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>

                Alumnos Asignados
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
                        <th>Último Riesgo</th>
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
                            default => 'badge-bajo'
                        };
                    @endphp

                    <tr class="fila-estudiante">

                        <td class="nombre-est">
                            {{ $est->nombre }}
                        </td>

                        <td>
                            {{ $est->carrera }}
                        </td>

                        <td>
                            <span class="{{ $badgeClass }}">
                                {{ strtoupper($est->nivel_riesgo) }}
                            </span>
                        </td>

                        <td>
                            <button
                                type="button"
                                onclick="seleccionarEstudiante(
                                    '{{ $est->entrevista_id }}',
                                    '{{ $est->nombre }}'
                                )"
                                class="btn-tecsup-success text-xs">

                                Seleccionar
                            </button>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center text-gray-400 py-6">
                            Aún no tienes estudiantes con entrevistas registradas.
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- FORMULARIO OBSERVACIÓN --}}
    <div class="bg-white rounded-xl shadow border border-tecsup-border">

        <div class="px-6 py-4 border-b border-tecsup-border">

            <h3 class="font-semibold text-tecsup-dark">
                Nueva Observación
            </h3>

            <p class="text-sm text-gray-500">
                Estudiante seleccionado:
                <span id="nombreEstudiante">
                    Ninguno
                </span>
            </p>

        </div>

        <form action="{{ route('tutor.observaciones.guardar') }}"
              method="POST">

            @csrf

            <input
                type="hidden"
                id="entrevista_id"
                name="entrevista_id">

            <div class="p-6">

                <textarea
                    name="observacion"
                    rows="8"
                    required
                    class="tecsup-input w-full resize-none"
                    placeholder="Escriba aquí la observación del estudiante..."></textarea>

            </div>

            <div class="px-6 pb-6 flex justify-end">

                <button
                    type="submit"
                    class="btn-tecsup-success">

                    Guardar Cambios

                </button>

            </div>

        </form>

    </div>

    @push('scripts')

    <script>

        function seleccionarEstudiante(entrevistaId, nombre)
        {
            document.getElementById('entrevista_id').value = entrevistaId;
            document.getElementById('nombreEstudiante').innerText = nombre;
        }

        function filtrarEstudiantes()
        {
            const filtro =
                document
                    .getElementById('buscador')
                    .value
                    .toLowerCase();

            document
                .querySelectorAll('.fila-estudiante')
                .forEach(fila =>
                {
                    const nombreEl = fila.querySelector('.nombre-est');
                    if (!nombreEl) return;

                    const nombre = nombreEl.innerText.toLowerCase();

                    fila.style.display =
                        nombre.includes(filtro)
                        ? ''
                        : 'none';
                });
        }

    </script>

    @endpush

</x-app-layout>