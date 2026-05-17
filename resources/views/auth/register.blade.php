<x-guest-layout title="Daftar Akun Baru">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="Nama Lengkap" required autofocus autocomplete="off" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div class="mb-4">
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" placeholder="Alamat Email" required autocomplete="off" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mb-4">
            <x-text-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" :value="old('no_telepon')" placeholder="No. HP (WhatsApp)" autocomplete="off" />
            <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
            <p class="mt-1 text-xs text-gray-400">Contoh: 081234567890</p>
        </div>

        @if(auth()->check() && auth()->user()->isAdmin())
        <div class="mb-4">
            <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('role') border-red-300 text-red-900 @enderror">
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1" />
        </div>
        @endif

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Password" required autocomplete="off" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>
            <div>
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" placeholder="Ulangi Password" required autocomplete="off" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <button type="submit" class="btn-primary w-full">
            <i class="fas fa-user-plus mr-2"></i> Daftar Akun
        </button>
    </form>

    <hr class="my-6 border-gray-200">
    <div class="text-center text-sm">
        <a class="text-primary-600 hover:text-primary-800" href="{{ route('login') }}">Sudah punya akun? Masuk!</a>
    </div>
</x-guest-layout>
