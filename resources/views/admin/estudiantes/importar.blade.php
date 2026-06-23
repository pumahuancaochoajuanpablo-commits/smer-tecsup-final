<x-app-layout>
    <x-slot name="header">Importar Estudiantes</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="tecsup-alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    @if(session('errores'))
        <div class="tecsup-alert-danger mb-4">
            <p class="font-semibold mb-2">No se pudo importar todo el archivo.</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach(session('errores') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-2">Subir archivo CSV</h3>
            <p class="text-sm text-gray-600 mb-5">
                El archivo debe tener encabezados. Un archivo TXT solo funciona si internamente esta separado por comas como un CSV.
            </p>

            <form method="POST" action="{{ route('admin.importar.csv') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="tecsup-label">Archivo</label>
                    <input type="file" name="archivo" accept=".csv,.txt,text/csv,text/plain" class="tecsup-input" required>
                </div>

                <button type="submit" class="btn-tecsup-primary">Importar estudiantes</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold mb-3">Formato correcto</h3>
            <p class="text-sm text-gray-600 mb-3">Opcion recomendada:</p>
            <pre class="text-xs bg-gray-50 border border-gray-200 rounded-lg p-3 overflow-x-auto">nombre,email,codigo,carrera,ciclo,grupo,edad
Ana Torres,ana.torres@tecsup.edu.pe,EST001,Diseno y Desarrollo de Software,2,A,20</pre>

            <p class="text-sm text-gray-600 mt-4 mb-3">Tambien se acepta:</p>
            <pre class="text-xs bg-gray-50 border border-gray-200 rounded-lg p-3 overflow-x-auto">apellidos,nombres,email,especialidad,semestre,grupo,edad
Torres,Ana,ana.torres@tecsup.edu.pe,Diseno y Desarrollo de Software,2,A,20</pre>
        </div>
    </div>
</x-app-layout>
