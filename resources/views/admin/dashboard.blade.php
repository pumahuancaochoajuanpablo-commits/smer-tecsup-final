<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel Administrativo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Estudiantes</div>
                        <div class="text-3xl font-bold">{{ $totalEstudiantes }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Tutores</div>
                        <div class="text-3xl font-bold">{{ $totalTutores }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Entrevistas</div>
                        <div class="text-3xl font-bold">{{ $totalEntrevistas }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Distribucion por Nivel de Riesgo</h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-green-100 p-4 rounded">
                            <div class="text-2xl font-bold text-green-700">{{ $riesgos['bajo'] ?? 0 }}</div>
                            <div class="text-green-600">Bajo</div>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded">
                            <div class="text-2xl font-bold text-yellow-700">{{ $riesgos['medio'] ?? 0 }}</div>
                            <div class="text-yellow-600">Medio</div>
                        </div>
                        <div class="bg-red-100 p-4 rounded">
                            <div class="text-2xl font-bold text-red-700">{{ $riesgos['alto'] ?? 0 }}</div>
                            <div class="text-red-600">Alto</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
