<x-app-layout>
    <x-slot name="header">Gestion de derivaciones a Bienestar</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="tecsup-alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow border border-tecsup-border p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h3 class="text-lg font-semibold text-tecsup-dark">Derivaciones registradas</h3>
            <a href="{{ route('derivaciones.estadisticas') }}" class="btn-tecsup-outline justify-center">
                Ver estadisticas
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="tecsup-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Tutor</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($derivaciones as $derivacion)
                        @php
                            $statusColors = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'derivado' => 'bg-blue-100 text-blue-800',
                                'completado' => 'bg-green-100 text-green-800',
                                'rechazado' => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$derivacion->estado] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <tr>
                            <td class="font-medium text-tecsup-dark">{{ $derivacion->estudiante->user->name }}</td>
                            <td class="text-gray-500">{{ $derivacion->tutor->user->name }}</td>
                            <td class="text-gray-500">{{ $derivacion->motivo }}</td>
                            <td><span class="px-2 py-1 rounded text-xs font-semibold {{ $color }}">{{ strtoupper($derivacion->estado) }}</span></td>
                            <td class="text-gray-500">{{ $derivacion->fecha_derivacion->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('derivaciones.ver', $derivacion->id) }}" class="btn-tecsup-outline text-xs py-1 px-3">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-6">No hay derivaciones registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $derivaciones->links() }}
        </div>
    </div>
</x-app-layout>
