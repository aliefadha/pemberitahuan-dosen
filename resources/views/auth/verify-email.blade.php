<x-guest-layout title="Verify Email">
    <p class="mb-4 text-sm text-gray-600">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="mb-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full">
                Resend Verification Email
            </button>
        </form>
    </div>

    <hr class="my-6 border-gray-200">

    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
