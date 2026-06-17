<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Entrevista - ') . $estudiante->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('tutor.guardar') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="asignacion_id" value="{{ $asignacion->id }}">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            @php
                                $indicadores = [
                                    'acad_2' => 'Rendimiento Académico',
                                    'emoc_2' => 'Estado Emocional',
                                    'soc_2' => 'Relaciones Sociales',
                                    'econ_2' => 'Situación Económica',
                                    'fam_2' => 'Relaciones Familiares',
                                    'salud_2' => 'Estado de Salud',
                                ];
                            @endphp

                            @foreach($indicadores as $campo => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                <select name="{{ $campo }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Seleccionar</option>
                                    @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} - {{ ['Muy Bajo', 'Bajo', 'Regular', 'Bueno', 'Excelente'][$i-1] }}</option>
                                    @endfor
                                </select>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Documento de evidencia</label>
                            <input type="file" name="documento" class="w-full border-gray-300 rounded-md shadow-sm" accept="application/pdf,image/*">
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG o PNG hasta 5MB.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observacion" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Notas adicionales..."></textarea>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Guardar Entrevista y Calcular Riesgo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
