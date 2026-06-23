<x-app-layout>
    <x-slot name="header">Panel de Tutoria</x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-tecsup-dark rounded-xl p-5 flex flex-col gap-1 border-l-4 border-green-500 shadow">
            <span class="text-white/60 text-xs font-semibold uppercase tracking-widest">Riesgo bajo</span>
            <span class="text-4xl font-bold text-white">{{ $riesgos['bajo'] ?? 0 }}</span>
            <span class="text-white/50 text-sm">Bienestar adecuado</span>
        </div>

        <div class="bg-tecsup-dark rounded-xl p-5 flex flex-col gap-1 border-l-4 border-orange-400 shadow">
            <span class="text-white/60 text-xs font-semibold uppercase tracking-widest">Riesgo medio</span>
            <span class="text-4xl font-bold text-white">{{ $riesgos['medio'] ?? 0 }}</span>
            <span class="text-white/50 text-sm">Requieren seguimiento</span>
        </div>

        <div class="bg-tecsup-dark rounded-xl p-5 flex flex-col gap-1 border-l-4 border-red-500 shadow">
            <span class="text-white/60 text-xs font-semibold uppercase tracking-widest">Riesgo alto</span>
            <span class="text-4xl font-bold text-white">{{ $riesgos['alto'] ?? 0 }}</span>
            <span class="text-white/50 text-sm">Necesita atencion inmediata</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow border border-tecsup-border p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h3 class="text-tecsup-dark font-semibold text-base">Evolucion del riesgo</h3>
                <p class="text-sm text-gray-500">Distribucion de estudiantes por nivel en las ultimas entrevistas.</p>
            </div>
            <a href="{{ route('tutor.estudiantes') }}" class="btn-tecsup-outline justify-center">Registrar encuesta</a>
        </div>
        <canvas id="chartRiesgo" height="100"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow border border-tecsup-border p-6">
        <h3 class="text-tecsup-dark font-semibold text-base mb-4">Ultimas encuestas registradas</h3>

        @if($ultimasEntrevistas->isEmpty())
            <p class="text-gray-400 text-sm text-center py-6">Aun no hay encuestas registradas.</p>
        @else
        <div class="overflow-x-auto">
            <table class="tecsup-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Carrera</th>
                        <th>Fecha</th>
                        <th>Puntaje</th>
                        <th>Nivel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimasEntrevistas as $e)
                    @php
                        $badgeClass = match($e->nivel_riesgo) {
                            'alto' => 'badge-alto',
                            'medio' => 'badge-medio',
                            'bajo' => 'badge-bajo',
                            default => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <tr>
                        <td class="font-medium text-tecsup-dark">{{ $e->nombre }}</td>
                        <td class="text-gray-500">{{ $e->carrera }}</td>
                        <td class="text-gray-500">{{ \Carbon\Carbon::parse($e->fecha)->format('d/m/Y') }}</td>
                        <td class="text-gray-500">{{ $e->puntaje_total }}</td>
                        <td><span class="{{ $badgeClass }}">{{ strtoupper($e->nivel_riesgo) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartRiesgo').getContext('2d');
        const labels = @json($chartLabels);
        const dataAlto = @json($chartAlto);
        const dataMedio = @json($chartMedio);
        const dataBajo = @json($chartBajo);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Alto', data: dataAlto, backgroundColor: 'rgba(239,68,68,0.8)', borderRadius: 4 },
                    { label: 'Medio', data: dataMedio, backgroundColor: 'rgba(251,146,60,0.8)', borderRadius: 4 },
                    { label: 'Bajo', data: dataBajo, backgroundColor: 'rgba(34,197,94,0.8)', borderRadius: 4 },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
