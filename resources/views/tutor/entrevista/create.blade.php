<x-app-layout>
    <x-slot name="header">Registrar Encuesta - {{ $estudiante->user->name }}</x-slot>

    @php
        $formAction = $formAction ?? route('tutor.guardar');
        $preguntas = [
            'acad_2' => [
                'criterio' => 'Rendimiento academico',
                'texto' => 'Que tan satisfecho(a) estas con tus calificaciones y asistencia a clases en este semestre?',
                'alto' => 'Insatisfaccion alta, dificultades en varias asignaturas o ausencias frecuentes.',
                'medio' => 'Desempeno regular, algunas ausencias o dificultades puntuales.',
                'bajo' => 'Buen desempeno y asistencia constante.',
            ],
            'emoc_2' => [
                'criterio' => 'Bienestar emocional',
                'texto' => 'Sientes que cuentas con apoyo emocional cuando enfrentas problemas personales o academicos?',
                'alto' => 'No tengo redes de apoyo o me siento solo(a).',
                'medio' => 'Tengo poco apoyo o apoyo no constante.',
                'bajo' => 'Cuento con buen apoyo y lo uso cuando lo necesito.',
            ],
            'soc_2' => [
                'criterio' => 'Trabajo en equipo',
                'texto' => 'Como describirias tu experiencia al trabajar en equipo durante actividades academicas o extracurriculares?',
                'alto' => 'Frecuentes conflictos o dificultad para colaborar.',
                'medio' => 'Participacion moderada, a veces con problemas de coordinacion.',
                'bajo' => 'Buena colaboracion y comunicacion positiva.',
            ],
            'econ_2' => [
                'criterio' => 'Comunicacion efectiva',
                'texto' => 'Que tan comodo(a) te sientes al expresar tus ideas en publico o al interactuar con companeros y docentes?',
                'alto' => 'Muy incomodo(a) o evito hablar en publico.',
                'medio' => 'A veces inseguro(a), pero logro comunicarme.',
                'bajo' => 'Comodo(a) y claro(a) al comunicarme.',
            ],
            'fam_2' => [
                'criterio' => 'Trabajo / Economia',
                'texto' => 'Actualmente trabajas o enfrentas dificultades economicas que puedan afectar tus estudios?',
                'alto' => 'Trabajo muchas horas o enfrento serios problemas economicos.',
                'medio' => 'Trabajo parcial o problemas economicos moderados.',
                'bajo' => 'No trabajo o mis finanzas no afectan mis estudios.',
            ],
            'salud_2' => [
                'criterio' => 'Estres - estado emocional',
                'texto' => 'Con que frecuencia el estres o la ansiedad afectan tu desempeno academico o personal?',
                'alto' => 'Frecuentemente o casi siempre.',
                'medio' => 'Ocasionalmente.',
                'bajo' => 'Rara vez o nunca.',
            ],
        ];
    @endphp

    @if($errors->any())
        <div class="tecsup-alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4 flex-wrap mb-5">
                <div>
                    <h3 class="text-lg font-semibold">Datos del Alumno</h3>
                    <p class="text-sm text-gray-600">Completa o confirma los datos antes de registrar la encuesta.</p>
                </div>
                <div class="text-sm text-gray-500">Tutora: {{ $asignacion->tutor->user->name ?? 'N/A' }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label class="tecsup-label">Alumno</label>
                    <input type="text" value="{{ $estudiante->user->name }}" class="tecsup-input bg-gray-50" readonly>
                </div>
                <div>
                    <label class="tecsup-label">Codigo</label>
                    <input type="text" value="{{ $estudiante->codigo }}" class="tecsup-input bg-gray-50" readonly>
                </div>
                <div>
                    <label class="tecsup-label">Semestre</label>
                    <input type="text" name="ciclo" value="{{ old('ciclo', $estudiante->ciclo) }}" class="tecsup-input">
                </div>
                <div>
                    <label class="tecsup-label">Grupo</label>
                    <input type="text" name="grupo" value="{{ old('grupo', $estudiante->grupo) }}" class="tecsup-input" placeholder="A, B, C">
                </div>
                <div class="md:col-span-3">
                    <label class="tecsup-label">Especialidad / Carrera</label>
                    <input type="text" name="carrera" value="{{ old('carrera', $estudiante->carrera) }}" class="tecsup-input">
                </div>
                <div>
                    <label class="tecsup-label">Edad</label>
                    <input type="number" name="edad" value="{{ old('edad', $estudiante->edad) }}" min="10" max="80" class="tecsup-input">
                </div>
                <div>
                    <label class="tecsup-label">Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="tecsup-input" required>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold mb-3">Ayuda visual para clasificar respuestas</h3>
            <div class="overflow-x-auto">
                <table class="tecsup-table">
                    <thead>
                        <tr>
                            <th>Criterio</th>
                            <th>Alto (3)</th>
                            <th>Medio (2)</th>
                            <th>Bajo (1)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preguntas as $pregunta)
                            <tr>
                                <td class="font-semibold">{{ $pregunta['criterio'] }}</td>
                                <td>{{ $pregunta['alto'] }}</td>
                                <td>{{ $pregunta['medio'] }}</td>
                                <td>{{ $pregunta['bajo'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold mb-4">Preguntas de la Encuesta</h3>
            <div class="space-y-4">
                @foreach($preguntas as $campo => $pregunta)
                    <fieldset class="border border-gray-200 rounded-lg p-4">
                        <legend class="px-2 text-sm font-semibold text-tecsup-dark">{{ $pregunta['criterio'] }}</legend>
                        <p class="text-sm text-gray-700 mb-3">{{ $loop->iteration }}. {{ $pregunta['texto'] }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-start gap-3 border border-red-100 rounded-md p-3 cursor-pointer hover:bg-red-50">
                                <input type="radio" name="{{ $campo }}" value="3" class="mt-1" required {{ old($campo) == 3 ? 'checked' : '' }}>
                                <span><strong class="text-red-700">Alto</strong><span class="block text-xs text-gray-600">{{ $pregunta['alto'] }}</span></span>
                            </label>
                            <label class="flex items-start gap-3 border border-yellow-100 rounded-md p-3 cursor-pointer hover:bg-yellow-50">
                                <input type="radio" name="{{ $campo }}" value="2" class="mt-1" required {{ old($campo) == 2 ? 'checked' : '' }}>
                                <span><strong class="text-yellow-700">Medio</strong><span class="block text-xs text-gray-600">{{ $pregunta['medio'] }}</span></span>
                            </label>
                            <label class="flex items-start gap-3 border border-green-100 rounded-md p-3 cursor-pointer hover:bg-green-50">
                                <input type="radio" name="{{ $campo }}" value="1" class="mt-1" required {{ old($campo) == 1 ? 'checked' : '' }}>
                                <span><strong class="text-green-700">Bajo</strong><span class="block text-xs text-gray-600">{{ $pregunta['bajo'] }}</span></span>
                            </label>
                        </div>
                    </fieldset>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="tecsup-label">Documento de evidencia</label>
                    <input type="file" name="documento" class="tecsup-input" accept="application/pdf,image/*">
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG o PNG hasta 5MB.</p>
                </div>
                <div>
                    <label class="tecsup-label">Observaciones Tutora</label>
                    <textarea name="observacion" rows="4" class="tecsup-input" placeholder="Campo abierto para observaciones...">{{ old('observacion') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-tecsup-primary">Guardar Encuesta y Calcular Riesgo</button>
        </div>
    </form>
</x-app-layout>
