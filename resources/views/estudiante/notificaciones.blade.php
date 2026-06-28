<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Notificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($notificaciones->isEmpty())
                        <p class="text-center text-gray-500 py-8">No tienes notificaciones</p>
                    @else
                        <div class="space-y-3">
                            @foreach($notificaciones as $n)
                                <div class="border rounded-lg p-4 {{ $n->leido ? 'bg-gray-50' : 'bg-blue-50 border-blue-200' }}" id="notif-{{ $n->id }}">
                                    <div class="flex justify-between">
                                        <div class="flex-1">
                                            <p class="{{ $n->leido ? 'text-gray-600' : 'font-semibold text-gray-800' }}">{{ $n->mensaje }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if(!$n->leido)
                                            <button onclick="marcarLeido({{ $n->id }})" class="text-blue-600 text-sm hover:underline ml-4">
                                                Marcar leido
                                            </button>
                                        @else
                                            <span class="text-green-600 text-sm ml-4">Leido</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function marcarLeido(id) {
                fetch('{{ route("estudiante.notificaciones.leer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id })
                }).then(() => {
                    document.getElementById('notif-' + id).classList.remove('bg-blue-50', 'border-blue-200');
                    document.getElementById('notif-' + id).classList.add('bg-gray-50');
                    document.querySelector('#notif-' + id + ' button').remove();
                    document.querySelector('#notif-' + id + ' .flex-1 p').classList.remove('font-semibold');
                    document.querySelector('#notif-' + id + ' .flex-1 p').classList.add('text-gray-600');

                    const span = document.createElement('span');
                    span.className = 'text-green-600 text-sm ml-4';
                    span.textContent = 'Leido';
                    document.querySelector('#notif-' + id + ' .flex.justify-between').appendChild(span);
                });
            }
        </script>
    @endpush
</x-app-layout>
