<x-app-layout>
    <x-slot name="header">Gestion de Tutores</x-slot>

    @if(session('success'))
        <div class="tecsup-alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="tecsup-alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold mb-4">Registrar tutor</h3>
            <form method="POST" action="{{ route('admin.tutores.guardar') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="tecsup-label">Nombres</label>
                    <input type="text" name="nombres" value="{{ old('nombres') }}" class="tecsup-input" required>
                </div>
                <div>
                    <label class="tecsup-label">Apellidos</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos') }}" class="tecsup-input" required>
                </div>
                <div>
                    <label class="tecsup-label">Correo institucional</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="tecsup-input" required>
                </div>
                <div>
                    <label class="tecsup-label">Codigo</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}" placeholder="TUT004" class="tecsup-input" required>
                </div>
                <div>
                    <label class="tecsup-label">Especialidad</label>
                    <input type="text" name="especialidad" value="{{ old('especialidad') }}" class="tecsup-input" required>
                </div>
                <button type="submit" class="btn-tecsup-primary w-full justify-center">Registrar tutor</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Tutores registrados</h3>
            <div class="overflow-x-auto">
                <table class="tecsup-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Codigo</th>
                            <th>Especialidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tutores as $tutor)
                        <tr>
                            <td>{{ $tutor->user->name }}</td>
                            <td>{{ $tutor->user->email }}</td>
                            <td>{{ $tutor->codigo }}</td>
                            <td>{{ $tutor->especialidad }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-8">No hay tutores registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
