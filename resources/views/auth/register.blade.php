<x-guest-layout title="Daftar Akun Baru">
    <form method="POST" action="{{ route('register') }}" class="user">
        @csrf

        <div class="form-group">
            <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror"
                id="exampleFirstName" placeholder="Nama Lengkap" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                id="exampleInputEmail" placeholder="Alamat Email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="text" class="form-control form-control-user @error('no_telepon') is-invalid @enderror"
                id="exampleInputPhone" placeholder="No. HP (WhatsApp)" name="no_telepon" value="{{ old('no_telepon') }}" autocomplete="tel">
            @error('no_telepon')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Contoh: 081234567890</small>
        </div>

        @if(auth()->check() && auth()->user()->isAdmin())
        <div class="form-group">
            <select class="form-control form-control-user @error('role') is-invalid @enderror" name="role">
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @endif

        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                    id="exampleInputPassword" placeholder="Password" name="password" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm-6">
                <input type="password" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror"
                    id="exampleRepeatPassword" placeholder="Ulangi Password" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            Daftar Akun
        </button>
    </form>
    <hr>
    <div class="text-center">
        <a class="small" href="{{ route('login') }}">Sudah punya akun? Masuk!</a>
    </div>
</x-guest-layout>
