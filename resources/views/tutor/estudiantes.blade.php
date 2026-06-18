<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Estudiantes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('recomendacion'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">{{ session('recomendacion') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Estudiantes Asignados</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($estudiantes as $est)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">{{ $est->nombre }}</span>
                                @php
                                    $color = match($est->nivel_riesgo) {
                                        'alto' => 'bg-red-500',
                                        'medio' => 'bg-yellow-500',
                                        'bajo' => 'bg-green-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp
                                <span class="{{ $color }} text-white text-xs px-2 py-1 rounded-full">{{ strtoupper($est->nivel_riesgo) }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <div>Código: {{ $est->codigo }}</div>
                                <div>Carrera: {{ $est->carrera }}</div>
                                @if($est->fecha_ultima)
                                <div>Última entrevista: {{ $est->fecha_ultima }}</div>
                                @endif
                            </div>
                            <div class="mt-3 flex gap-2 flex-wrap">
                                <a href="{{ route('tutor.entrevista', $est->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Nueva Entrevista</a>
                                <a href="{{ route('tutor.historial', $est->id) }}" class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700">Historial</a>
                                @if($est->nivel_riesgo === 'alto')
                                <a href="{{ route('derivaciones.crear', $est->id) }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Derivar</a>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center text-gray-500 py-8">No tienes estudiantes asignados</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
