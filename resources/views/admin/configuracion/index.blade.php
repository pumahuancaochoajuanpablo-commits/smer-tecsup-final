<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración de Parámetros de Riesgo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.config.guardar') }}">
                        @csrf
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2">Indicador</th>
                                    <th class="py-2">Peso (%)</th>
                                    <th class="py-2">Umbral Bajo</th>
                                    <th class="py-2">Umbral Medio</th>
                                    <th class="py-2">Umbral Alto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['acad_2' => 'Académico', 'emoc_2' => 'Emocional', 'soc_2' => 'Social', 'econ_2' => 'Económico', 'fam_2' => 'Familiar', 'salud_2' => 'Salud'] as $key => $label)
                                @php $p = $parametros[$key] ?? null; @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 font-medium">{{ $label }}</td>
                                    <td><input type="number" name="peso[{{ $key }}]" value="{{ $p->peso ?? 0 }}" class="w-20 border-gray-300 rounded" min="0" max="100" step="0.01"></td>
                                    <td><input type="number" name="umbral_bajo[{{ $key }}]" value="{{ $p->umbral_bajo ?? 3 }}" class="w-20 border-gray-300 rounded" min="1"></td>
                                    <td><input type="number" name="umbral_medio[{{ $key }}]" value="{{ $p->umbral_medio ?? 5 }}" class="w-20 border-gray-300 rounded" min="1"></td>
                                    <td><input type="number" name="umbral_alto[{{ $key }}]" value="{{ $p->umbral_alto ?? 7 }}" class="w-20 border-gray-300 rounded" min="1"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar Configuración</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
