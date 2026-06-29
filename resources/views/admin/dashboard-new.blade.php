<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-2 md:py-6">
        <div class="max-w-7xl mx-auto">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-5 mb-6">
                <div class="bg-white border border-gray-100 p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total Estudiantes</p>
                    <p class="text-3xl font-bold text-tecsup-dark mt-2">{{ $totalEstudiantes }}</p>
                </div>

                <div class="bg-white border border-gray-100 p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total Tutores</p>
                    <p class="text-3xl font-bold text-tecsup-dark mt-2">{{ $totalTutores }}</p>
                </div>

                <div class="bg-white border border-gray-100 p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total Entrevistas</p>
                    <p class="text-3xl font-bold text-tecsup-dark mt-2">{{ $totalEntrevistas }}</p>
                </div>

                <div class="bg-white border border-red-100 p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Riesgo Alto Hoy</p>
                    <p class="text-3xl font-bold text-red-700 mt-2">{{ $riesgos['alto'] ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 md:gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 md:p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Distribucion por Nivel de Riesgo</h3>
                    <div class="chart-frame">
                        <canvas id="riesgoChart"></canvas>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 md:p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Ultimas Entrevistas</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse($ultimasEntrevistas as $entrevista)
                            <a href="{{ route('admin.encuestas.ver', $entrevista) }}" class="flex items-start justify-between gap-3 p-3 bg-gray-50 rounded-md hover:bg-tecsup-light transition border border-transparent hover:border-tecsup-cyan">
                                <div class="min-w-0">
                                    <p class="font-semibold text-sm">{{ $entrevista->asignacion->estudiante->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $entrevista->fecha->format('d/m/Y') }}</p>
                                </div>
                                <span class="shrink-0 px-3 py-1 text-xs rounded font-semibold
                                    @if($entrevista->nivel_riesgo === 'alto') bg-red-100 text-red-800
                                    @elseif($entrevista->nivel_riesgo === 'medio') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ strtoupper($entrevista->nivel_riesgo) }}
                                </span>
                            </a>
                        @empty
                            <p class="text-gray-500 text-center py-8">Sin entrevistas registradas</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-5">
                <a href="{{ route('admin.reportes.informe') }}" class="bg-white border border-gray-100 rounded-lg p-5 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-tecsup-cyan uppercase tracking-wide">PDF</p>
                    <h4 class="font-semibold mt-2">Informe General</h4>
                    <p class="text-sm text-gray-600 mt-2">Generar PDF con datos generales</p>
                </a>

                <a href="{{ route('admin.reportes.excel') }}" class="bg-white border border-gray-100 rounded-lg p-5 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-tecsup-cyan uppercase tracking-wide">Excel</p>
                    <h4 class="font-semibold mt-2">Exportar Entrevistas</h4>
                    <p class="text-sm text-gray-600 mt-2">Descargar entrevistas en bloque</p>
                </a>

                <a href="{{ route('admin.reportes.fichas-masivas') }}" class="bg-white border border-gray-100 rounded-lg p-5 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-tecsup-cyan uppercase tracking-wide">ZIP</p>
                    <h4 class="font-semibold mt-2">Fichas Masivas</h4>
                    <p class="text-sm text-gray-600 mt-2">Generar fichas PDF por lote</p>
                </a>

                <a href="{{ route('admin.auditoria.index') }}" class="bg-white border border-gray-100 rounded-lg p-5 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-tecsup-cyan uppercase tracking-wide">Control</p>
                    <h4 class="font-semibold mt-2">Auditoria</h4>
                    <p class="text-sm text-gray-600 mt-2">Ver registros del sistema</p>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
