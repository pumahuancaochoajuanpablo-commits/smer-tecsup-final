<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Correo institucional" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4" x-data="{ showPassword: false }">
            <x-input-label for="password" value="Contrasena" />
            <div class="relative mt-1">
                <x-text-input id="password"
                              class="block w-full pr-11"
                              x-bind:type="showPassword ? 'text' : 'password'"
                              name="password"
                              required
                              autocomplete="current-password" />
                <button type="button"
                        class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-500 hover:text-tecsup-dark focus:outline-none focus:ring-2 focus:ring-inset focus:ring-tecsup-cyan"
                        x-on:click="showPassword = !showPassword"
                        x-bind:aria-label="showPassword ? 'Ocultar contrasena' : 'Mostrar contrasena'">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.58 10.58a2 2 0 0 0 2.84 2.84" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 5.38A9.7 9.7 0 0 1 12 5.25c6 0 9.75 6.75 9.75 6.75a18.5 18.5 0 0 1-2.33 3.05" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.61 6.61C3.83 8.28 2.25 12 2.25 12s3.75 6.75 9.75 6.75c1.66 0 3.14-.52 4.4-1.24" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-tecsup-cyan shadow-sm focus:ring-tecsup-cyan" name="remember">
                <span class="ms-2 text-sm text-gray-600">Mantener sesion iniciada</span>
            </label>
        </div>

        <div class="flex items-center justify-between gap-4 mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tecsup-cyan" href="{{ route('password.request') }}">
                    Olvide mi contrasena
                </a>
            @endif

            <x-primary-button>
                Ingresar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
