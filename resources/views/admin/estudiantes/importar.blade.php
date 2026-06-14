<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Importar Estudiantes desde CSV') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            @if(session('errores'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                    @foreach(session('errores') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Subir archivo CSV</h3>
                    <p class="text-sm text-gray-600 mb-4">El archivo debe tener las columnas: <code>nombre,email,codigo,carrera</code></p>
                    <form method="POST" action="{{ route('admin.importar.csv') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="archivo" accept=".csv,.txt" class="mb-4" required>
                        <br>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Importar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
