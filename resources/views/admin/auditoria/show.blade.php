<x-app-layout>
    <x-slot name="header">Detalle de auditoria</x-slot>

    @php
        $acciones = [
            'create' => 'Registro creado',
            'update' => 'Registro actualizado',
            'delete' => 'Registro eliminado',
        ];

        $campos = [
            'asignacion_id' => 'Asignacion',
            'estudiante' => 'Estudiante',
            'puntaje' => 'Puntaje obtenido',
            'nivel_riesgo' => 'Nivel de riesgo',
            'info' => 'Informacion',
        ];

        $detalleLegible = collect($log->detalles ?? [])->mapWithKeys(function ($valor, $campo) use ($campos) {
            $etiqueta = $campos[$campo] ?? str_replace('_', ' ', ucfirst($campo));
            $contenido = is_array($valor) ? implode(', ', $valor) : $valor;

            if (is_string($contenido)) {
                $contenido = ucfirst($contenido);
            }

            return [$etiqueta => $contenido];
        });
    @endphp

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-tecsup-border">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6 text-tecsup-dark">Detalles del registro</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Fecha</p>
                        <p class="font-semibold">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Usuario</p>
                        <p class="font-semibold">{{ $log->user?->name ?? 'Sistema' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Accion</p>
                        <span class="px-3 py-1 text-sm rounded font-semibold
                            @if($log->accion === 'create') bg-green-100 text-green-800
                            @elseif($log->accion === 'update') bg-blue-100 text-blue-800
                            @elseif($log->accion === 'delete') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $acciones[$log->accion] ?? ucfirst($log->accion) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Modelo</p>
                        <p class="font-semibold">{{ $log->modelo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Registro</p>
                        <p class="font-semibold">{{ $log->modelo_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Direccion IP</p>
                        <p class="font-semibold text-xs">{{ $log->ip_address }}</p>
                    </div>
                </div>

                @if($log->detalles)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Detalles de la accion</h3>
                    <div class="rounded-lg border border-tecsup-border overflow-hidden">
                        <table class="w-full text-sm">
                            <tbody>
                                @foreach($detalleLegible as $campo => $valor)
                                    <tr class="odd:bg-white even:bg-tecsup-light/40">
                                        <th class="w-1/3 border border-tecsup-border px-4 py-3 text-left font-semibold text-tecsup-dark">
                                            {{ $campo }}
                                        </th>
                                        <td class="border border-tecsup-border px-4 py-3 text-tecsup-dark">
                                            {{ $valor }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('admin.auditoria.index') }}" class="btn-tecsup-outline">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
