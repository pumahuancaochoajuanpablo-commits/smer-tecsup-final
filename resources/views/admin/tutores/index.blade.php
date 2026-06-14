<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Tutores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Registrar Tutor</h3>
                    <form method="POST" action="{{ route('admin.tutores.guardar') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <input type="text" name="name" placeholder="Nombre completo" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <input type="email" name="email" placeholder="Correo electrónico" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <input type="text" name="codigo" placeholder="Código (ej: TUT004)" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <input type="text" name="especialidad" placeholder="Especialidad" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <div class="md:col-span-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Tutores Registrados</h3>
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Nombre</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Código</th>
                                <th class="py-2">Especialidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tutores as $tutor)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2">{{ $tutor->user->name }}</td>
                                <td class="py-2">{{ $tutor->user->email }}</td>
                                <td class="py-2">{{ $tutor->codigo }}</td>
                                <td class="py-2">{{ $tutor->especialidad }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-4 text-center text-gray-500">No hay tutores registrados</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
