<x-app-layout>
    <x-slot name="header">Nueva derivacion</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-tecsup-border">
            <div class="p-6">
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-gray-700">
                        El estudiante presenta riesgo alto y requiere atencion especializada del area de Bienestar.
                    </p>
                </div>

                <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-3">Informacion del estudiante</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nombre</p>
                            <p class="font-semibold">{{ $estudiante->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Codigo</p>
                            <p class="font-semibold">{{ $estudiante->codigo }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Carrera</p>
                            <p class="font-semibold">{{ $estudiante->carrera }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tutor</p>
                            <p class="font-semibold">{{ $tutor->user->name }}</p>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="tecsup-alert-danger mb-4">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="tecsup-alert-danger mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('derivaciones.registrar') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="estudiante_id" value="{{ $estudiante->id }}">

                    <div>
                        <label for="motivo" class="tecsup-label">Motivo de la derivacion</label>
                        <input
                            type="text"
                            id="motivo"
                            name="motivo"
                            class="tecsup-input"
                            value="{{ old('motivo', 'Riesgo alto - requiere atencion de Bienestar') }}"
                            required>
                    </div>

                    <div>
                        <label for="descripcion" class="tecsup-label">Descripcion detallada</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="6"
                            class="tecsup-input"
                            required>{{ old('descripcion', 'El estudiante presenta nivel de riesgo alto. Se recomienda derivacion a psicologia o bienestar estudiantil para evaluacion y seguimiento prioritario.') }}</textarea>
                    </div>

                    <div>
                        <label for="responsable_bienestar" class="tecsup-label">Responsable de Bienestar</label>
                        <input
                            type="text"
                            id="responsable_bienestar"
                            name="responsable_bienestar"
                            class="tecsup-input"
                            value="{{ old('responsable_bienestar', 'Yeferson Quispe') }}">
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                        <p class="text-sm text-gray-700">
                            Al enviar, el sistema registrara la derivacion y avisara al administrador de Bienestar.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="btn-tecsup-success justify-center">
                            Enviar derivacion
                        </button>
                        <a href="{{ route('tutor.alertas') }}" class="btn-tecsup-outline justify-center">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
