@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold">📋 AUDITORÍA DEL SISTEMA</h2>
                    <a href="{{ route('admin.auditoria.excel') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        📊 Exportar a Excel
                    </a>
                </div>

                <!-- Filtros -->
                <form method="GET" action="{{ route('admin.auditoria.index') }}" class="mb-6 p-4 bg-gray-50 rounded">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Acción</label>
                            <select name="accion" class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">Todas</option>
                                @foreach($acciones as $accion)
                                    <option value="{{ $accion }}" {{ request('accion') == $accion ? 'selected' : '' }}>
                                        {{ ucfirst($accion) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Modelo</label>
                            <select name="modelo" class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">Todos</option>
                                @foreach($modelos as $modelo)
                                    <option value="{{ $modelo }}" {{ request('modelo') == $modelo ? 'selected' : '' }}>
                                        {{ $modelo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Desde</label>
                            <input type="date" name="desde" value="{{ request('desde') }}" class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Hasta</label>
                            <input type="date" name="hasta" value="{{ request('hasta') }}" class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                🔍 Filtrar
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabla de logs -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">Fecha</th>
                                <th class="px-4 py-2 text-left">Usuario</th>
                                <th class="px-4 py-2 text-left">Acción</th>
                                <th class="px-4 py-2 text-left">Modelo</th>
                                <th class="px-4 py-2 text-left">IP</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-4 py-2">{{ $log->user?->name ?? 'Sistema' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded font-semibold
                                            @if($log->accion === 'create') bg-green-100 text-green-800
                                            @elseif($log->accion === 'update') bg-blue-100 text-blue-800
                                            @elseif($log->accion === 'delete') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ strtoupper($log->accion) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $log->modelo }}</td>
                                    <td class="px-4 py-2 text-xs">{{ $log->ip_address }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.auditoria.show', $log) }}" class="text-blue-600 hover:underline">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                        No hay registros de auditoría
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
