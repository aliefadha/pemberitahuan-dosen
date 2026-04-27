<x-guest-layout title="Forgot Password">
    <p class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-5">
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" placeholder="Enter Email Address..." required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <button type="submit" class="btn-primary w-full">
            Email Password Reset Link
        </button>
    </form>

    <hr class="my-6 border-gray-200">
    <div class="text-center text-sm">
        <a class="text-primary-600 hover:text-primary-800" href="{{ route('login') }}">Back to Login</a>
    </div>
</x-guest-layout>
