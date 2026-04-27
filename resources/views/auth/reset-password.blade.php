<x-guest-layout title="Reset Password">
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-4">
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $request->email)" placeholder="Email Address" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>
            <div>
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" placeholder="Repeat Password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <button type="submit" class="btn-primary w-full">
            Reset Password
        </button>
    </form>

    <hr class="my-6 border-gray-200">
    <div class="text-center text-sm">
        <a class="text-primary-600 hover:text-primary-800" href="{{ route('login') }}">Back to Login</a>
    </div>
</x-guest-layout>
