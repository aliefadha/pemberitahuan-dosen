<x-guest-layout title="Selamat Datang!">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-5">
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" placeholder="Masukkan Alamat Email..." required autofocus autocomplete="off" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mb-5">
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Password" required autocomplete="off" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <button type="submit" class="btn-primary w-full">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
        </button>
    </form>

    <hr class="my-6 border-gray-200">
    <div class="space-y-2 text-center text-sm">
        @if (Route::has('password.request'))
            <div>
                <a class="text-primary-600 hover:text-primary-800" href="{{ route('password.request') }}">Lupa Password?</a>
            </div>
        @endif
        @if (Route::has('register'))
            <div>
                <a class="text-primary-600 hover:text-primary-800" href="{{ route('register') }}">Belum punya akun? Daftar!</a>
            </div>
        @endif
    </div>
</x-guest-layout>
