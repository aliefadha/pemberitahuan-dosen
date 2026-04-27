<x-guest-layout title="Confirm Password">
    <p class="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-5">
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <button type="submit" class="btn-primary w-full">
            Confirm
        </button>
    </form>
</x-guest-layout>
