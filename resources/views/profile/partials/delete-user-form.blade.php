<div x-data="{ show: false }">
    <p class="text-sm text-gray-600 mb-4">
        Once your account is deleted, all of its resources and data will be permanently deleted.
    </p>

    <button type="button" class="btn-danger" x-on:click="show = true">
        Delete Account
    </button>

    {{-- Delete Modal --}}
    <div x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
        <div x-show="show" class="fixed inset-0 bg-gray-500/75" x-on:click="show = false"></div>
        <div x-show="show" class="relative z-50 mx-auto max-w-md rounded-xl bg-white shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Delete Account?</h3>
                <button class="text-gray-400 hover:text-gray-600" x-on:click="show = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600 mb-4">Please enter your password to confirm you would like to permanently delete your account.</p>
                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Password" autocomplete="current-password" />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" x-on:click="show = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
