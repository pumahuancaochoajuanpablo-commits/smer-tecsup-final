<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Tutorías') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Nueva Asignación</h3>
                    <form method="POST" action="{{ route('admin.asignaciones.guardar') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tutor</label>
                                <select name="tutor_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Seleccionar tutor</option>
                                    @foreach($tutores as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->user->name }} ({{ $tutor->codigo }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estudiantes (Ctrl+click para múltiples)</label>
                                <select name="estudiantes[]" multiple class="w-full border-gray-300 rounded-md shadow-sm h-40" required>
                                    @foreach($estudiantes as $est)
                                    <option value="{{ $est->id }}">{{ $est->user->name }} ({{ $est->codigo }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Asignar</button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Asignaciones Actuales</h3>
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Tutor</th>
                                <th class="py-2">Estudiante</th>
                                <th class="py-2">Fecha Inicio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asignaciones as $a)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2">{{ $a->tutor->user->name }}</td>
                                <td class="py-2">{{ $a->estudiante->user->name }}</td>
                                <td class="py-2">{{ $a->fecha_inicio }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">Sin asignaciones</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
