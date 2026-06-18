<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estadísticas de Derivaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-gray-600 text-sm">Total de Derivaciones</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                </div>
                
                <div class="bg-yellow-50 p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <div class="text-yellow-700 text-sm font-semibold">Pendientes</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pendientes'] }}</div>
                </div>
                
                <div class="bg-blue-50 p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="text-blue-700 text-sm font-semibold">Derivadas</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['derivadas'] }}</div>
                </div>
                
                <div class="bg-green-50 p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="text-green-700 text-sm font-semibold">Completadas</div>
                    <div class="text-3xl font-bold text-green-600">{{ $stats['completadas'] }}</div>
                </div>
                
                <div class="bg-red-50 p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <div class="text-red-700 text-sm font-semibold">Rechazadas</div>
                    <div class="text-3xl font-bold text-red-600">{{ $stats['rechazadas'] }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Análisis de Derivaciones</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <p class="text-sm text-gray-600 mb-2">Tasa de Resolución</p>
                            @php
                                $totalOctual = $stats['total'] > 0 ? $stats['total'] : 1;
                                $resolutionRate = ($stats['completadas'] + $stats['rechazadas']) / $totalOctual * 100;
                            @endphp
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($resolutionRate, 1) }}%</p>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <p class="text-sm text-gray-600 mb-2">Tasa de Derivaciones Completadas</p>
                            @php
                                $completionRate = $stats['completadas'] / $totalOctual * 100;
                            @endphp
                            <p class="text-2xl font-bold text-green-600">{{ number_format($completionRate, 1) }}%</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('derivaciones.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                            Ver Todas las Derivaciones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
