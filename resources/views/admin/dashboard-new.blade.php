<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Dashboard Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Estudiantes</p>
                            <p class="text-4xl font-bold">{{ $totalEstudiantes }}</p>
                        </div>
                        <div class="text-5xl opacity-20">👥</div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Tutores</p>
                            <p class="text-4xl font-bold">{{ $totalTutores }}</p>
                        </div>
                        <div class="text-5xl opacity-20">👨‍🏫</div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Entrevistas</p>
                            <p class="text-4xl font-bold">{{ $totalEntrevistas }}</p>
                        </div>
                        <div class="text-5xl opacity-20">📋</div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Riesgo Alto Hoy</p>
                            <p class="text-4xl font-bold">{{ $riesgos['alto'] ?? 0 }}</p>
                        </div>
                        <div class="text-5xl opacity-20">⚠️</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Gráfico de Distribución de Riesgo -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">📊 Distribución por Nivel de Riesgo</h3>
                    <div class="relative h-80">
                        <canvas id="riesgoChart"></canvas>
                    </div>
                </div>

                <!-- Últimas Entrevistas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">📅 Últimas Entrevistas</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse($ultimasEntrevistas as $entrevista)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                                <div>
                                    <p class="font-semibold text-sm">{{ $entrevista->asignacion->estudiante->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $entrevista->fecha->format('d/m/Y') }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs rounded font-semibold
                                    @if($entrevista->nivel_riesgo === 'alto') bg-red-100 text-red-800
                                    @elseif($entrevista->nivel_riesgo === 'medio') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ strtoupper($entrevista->nivel_riesgo) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">Sin entrevistas registradas</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.reportes.informe') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
                    <div class="text-3xl mb-2">📄</div>
                    <h4 class="font-semibold">Informe General</h4>
                    <p class="text-sm text-gray-600 mt-2">Generar PDF con datos generales</p>
                </a>

                <a href="{{ route('admin.reportes.excel') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
                    <div class="text-3xl mb-2">📊</div>
                    <h4 class="font-semibold">Exportar a Excel</h4>
                    <p class="text-sm text-gray-600 mt-2">Descargar todas las entrevistas</p>
                </a>

                <a href="{{ route('admin.auditoria.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
                    <div class="text-3xl mb-2">🔍</div>
                    <h4 class="font-semibold">Auditoría</h4>
                    <p class="text-sm text-gray-600 mt-2">Ver registros del sistema</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Riesgo
        const ctx = document.getElementById('riesgoChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Riesgo Bajo', 'Riesgo Medio', 'Riesgo Alto'],
                datasets: [{
                    label: 'Cantidad de Entrevistas',
                    data: [
                        {{ $riesgos['bajo'] ?? 0 }},
                        {{ $riesgos['medio'] ?? 0 }},
                        {{ $riesgos['alto'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</x-app-layout>
