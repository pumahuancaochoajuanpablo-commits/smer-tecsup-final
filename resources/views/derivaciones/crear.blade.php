<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Derivación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4">
                        <p class="text-sm text-gray-700">
                            <strong>Precondición:</strong> El estudiante presenta riesgo alto que requiere atención especializada del área de bienestar.
                        </p>
                    </div>

                    <div class="mb-4 bg-gray-50 p-4 rounded">
                        <h3 class="font-semibold text-lg mb-2">Información del Estudiante</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nombre</p>
                                <p class="font-semibold">{{ $estudiante->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Código</p>
                                <p class="font-semibold">{{ $estudiante->codigo }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Carrera</p>
                                <p class="font-semibold">{{ $estudiante->carrera }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Nivel de Riesgo</p>
                                @php
                                    $color = match($estudiante->nivel_riesgo) {
                                        'alto' => 'text-red-600 font-bold',
                                        'medio' => 'text-yellow-600 font-bold',
                                        'bajo' => 'text-green-600 font-bold',
                                        default => 'text-gray-600',
                                    };
                                @endphp
                                <p class="{{ $color }}">{{ strtoupper($estudiante->nivel_riesgo) }}</p>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('derivaciones.registrar') }}" method="POST" class="space-y-6">
                        @csrf

                        <input type="hidden" name="estudiante_id" value="{{ $estudiante->id }}">

                        <div>
                            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Motivo de la Derivación <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="motivo" name="motivo" class="w-full border border-gray-300 rounded px-3 py-2 @error('motivo') border-red-500 @enderror" 
                                placeholder="Ej: Riesgo alto - Problemas emocionales" required>
                            @error('motivo')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción Detallada <span class="text-red-600">*</span>
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="6" class="w-full border border-gray-300 rounded px-3 py-2 @error('descripcion') border-red-500 @enderror" 
                                placeholder="Describe detalladamente los motivos de la derivación, síntomas observados, comportamientos, etc." required></textarea>
                            @error('descripcion')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="responsable_bienestar" class="block text-sm font-medium text-gray-700 mb-2">
                                Responsable de Bienestar Estudiantil (Opcional)
                            </label>
                            <input type="text" id="responsable_bienestar" name="responsable_bienestar" 
                                class="w-full border border-gray-300 rounded px-3 py-2" 
                                placeholder="Nombre del responsable en el área de bienestar">
                            @error('responsable_bienestar')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                            <p class="text-sm text-gray-700">
                                <strong>Nota:</strong> Una vez enviada la solicitud, el sistema registrará la derivación y notificará al área de bienestar estudiantil.
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-semibold">
                                Enviar Derivación
                            </button>
                            <a href="{{ route('derivaciones.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 font-semibold">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
