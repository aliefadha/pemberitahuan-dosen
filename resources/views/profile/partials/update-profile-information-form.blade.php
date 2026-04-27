<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-5">
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="mb-5">
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 text-sm text-gray-600">
                Your email address is unverified.
                <button form="send-verification" class="text-primary-600 hover:text-primary-800 underline">Click here to re-send the verification email.</button>
            </div>
            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-sm text-emerald-600">A new verification link has been sent to your email address.</p>
            @endif
        @endif
    </div>

    <div class="mb-5">
        <x-input-label for="no_telepon" value="Nomor WhatsApp" />
        <x-text-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" :value="old('no_telepon', $user->no_telepon)" placeholder="08xxxxxxxxx" autocomplete="tel" />
        <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
        <p class="mt-1 text-xs text-gray-400">Contoh: 081234567890</p>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save</button>
        @if (session('status') === 'profile-updated')
            <span class="text-sm text-emerald-600 font-medium">Saved.</span>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
    @csrf
</form>
