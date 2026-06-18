<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Derivaciones a Bienestar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Derivaciones Registradas</h3>
                        <a href="{{ route('derivaciones.estadisticas') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Ver Estadísticas
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Estudiante</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Tutor</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Motivo</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Estado</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Fecha Derivación</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($derivaciones as $derivacion)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $derivacion->estudiante->nombre }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $derivacion->tutor->nombre }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $derivacion->motivo }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        @php
                                            $statusColors = [
                                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                                'derivado' => 'bg-blue-100 text-blue-800',
                                                'completado' => 'bg-green-100 text-green-800',
                                                'rechazado' => 'bg-red-100 text-red-800',
                                            ];
                                            $color = $statusColors[$derivacion->estado] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 rounded text-sm font-semibold {{ $color }}">
                                            {{ ucfirst($derivacion->estado) }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $derivacion->fecha_derivacion->format('d/m/Y H:i') }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <a href="{{ route('derivaciones.ver', $derivacion->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                        No hay derivaciones registradas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $derivaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
