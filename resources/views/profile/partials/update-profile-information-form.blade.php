<form method="post" action="{{ route('profile.update') }}" class="mb-0">
    @csrf
    @method('patch')

    <div class="form-group">
        <label for="name" class="text-gray-800">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="email" class="text-gray-800">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="text-sm text-gray-800">
                    Your email address is unverified.
                    <button form="send-verification" class="btn btn-link p-0">Click here to re-send the verification email.</button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-success">A new verification link has been sent to your email address.</p>
                @endif
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="no_telepon" class="text-gray-800">Nomor WhatsApp</label>
        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="08xxxxxxxxx" autocomplete="tel">
        @error('no_telepon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Contoh: 081234567890</small>
    </div>

    <div class="form-group mb-0">
        <button type="submit" class="btn btn-primary">Save</button>
        @if (session('status') === 'profile-updated')
            <span class="ml-2 text-sm text-success">Saved.</span>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
    @csrf
</form>
