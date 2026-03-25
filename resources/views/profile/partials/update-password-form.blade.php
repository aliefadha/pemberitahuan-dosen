<form method="post" action="{{ route('password.update') }}" class="mb-0">
    @csrf
    @method('put')

    <div class="form-group">
        <label for="update_password_current_password" class="text-gray-800">Current Password</label>
        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="update_password_current_password" name="current_password" autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="update_password_password" class="text-gray-800">New Password</label>
        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="update_password_password" name="password" autocomplete="new-password">
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-0">
        <label for="update_password_password_confirmation" class="text-gray-800">Confirm Password</label>
        <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <hr>

    <div class="form-group mb-0">
        <button type="submit" class="btn btn-primary">Save</button>
        @if (session('status') === 'password-updated')
            <span class="ml-2 text-sm text-success">Saved.</span>
        @endif
    </div>
</form>
