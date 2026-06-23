<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Antes de continuar, verifica tu correo institucional con el enlace que enviamos.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Enviamos un nuevo enlace de verificacion a tu correo.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Reenviar correo
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tecsup-cyan">
                Cerrar sesion
            </button>
        </form>
    </div>
</x-guest-layout>
