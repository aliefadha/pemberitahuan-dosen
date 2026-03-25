<x-guest-layout title="Selamat Datang!">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="user">
        @csrf

        <div class="form-group">
            <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                id="exampleInputEmail" aria-describedby="emailHelp"
                placeholder="Masukkan Alamat Email..." name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                id="exampleInputPassword" placeholder="Password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                <label class="custom-control-label" for="customCheck">Ingat Saya</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            Masuk
        </button>
    </form>
    <hr>
    @if (Route::has('password.request'))
        <div class="text-center">
            <a class="small" href="{{ route('password.request') }}">Lupa Password?</a>
        </div>
    @endif
    @if (Route::has('register'))
        <div class="text-center">
            <a class="small" href="{{ route('register') }}">Belum punya akun? Daftar!</a>
        </div>
    @endif
</x-guest-layout>
