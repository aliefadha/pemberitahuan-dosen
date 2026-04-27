<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-edit mr-2"></i>{{ __('Edit User') }}
    </x-slot>

    <div class="card">
        <div class="card-header">Form Edit User</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <x-input-label for="name" value="Nama" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="mb-5">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="mb-5">
                    <x-input-label for="no_telepon" value="No. HP (WhatsApp)" />
                    <x-text-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" :value="old('no_telepon', $user->no_telepon)" placeholder="08xxxxxxxxx" />
                    <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
                    <p class="mt-1 text-xs text-gray-400">Contoh: 081234567890</p>
                </div>

                <div class="mb-5">
                    <x-input-label for="role" value="Role" />
                    <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('role') border-red-300 text-red-900 @enderror">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div class="mb-5 rounded-lg bg-cyan-50 border border-cyan-200 px-4 py-3 text-sm text-cyan-700">
                    <i class="fas fa-info-circle mr-1"></i> Kosongkan password jika tidak ingin mengubahnya.
                </div>

                <div class="mb-5">
                    <x-input-label for="password" value="Password Baru" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="mb-5">
                    <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <hr class="my-6 border-gray-200">

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
