@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">📝 Detalles de Registro de Auditoría</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Fecha</p>
                        <p class="font-semibold">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Usuario</p>
                        <p class="font-semibold">{{ $log->user?->name ?? 'Sistema' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Acción</p>
                        <span class="px-3 py-1 text-sm rounded font-semibold
                            @if($log->accion === 'create') bg-green-100 text-green-800
                            @elseif($log->accion === 'update') bg-blue-100 text-blue-800
                            @elseif($log->accion === 'delete') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ strtoupper($log->accion) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Modelo</p>
                        <p class="font-semibold">{{ $log->modelo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">ID Registro</p>
                        <p class="font-semibold">{{ $log->modelo_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Dirección IP</p>
                        <p class="font-semibold text-xs">{{ $log->ip_address }}</p>
                    </div>
                </div>

                @if($log->detalles)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Detalles de la Acción</h3>
                    <div class="bg-gray-50 p-4 rounded overflow-auto">
                        <pre class="text-sm">{{ json_encode($log->detalles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('admin.auditoria.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        ← Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
