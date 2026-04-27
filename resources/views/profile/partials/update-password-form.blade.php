<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-5">
        <x-input-label for="update_password_current_password" value="Current Password" />
        <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
    </div>

    <div class="mb-5">
        <x-input-label for="update_password_password" value="New Password" />
        <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
    </div>

    <div class="mb-5">
        <x-input-label for="update_password_password_confirmation" value="Confirm Password" />
        <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn-primary">Save</button>
        @if (session('status') === 'password-updated')
            <span class="text-sm text-emerald-600 font-medium">Saved.</span>
        @endif
    </div>
</form>
