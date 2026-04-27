<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-user mr-2"></i>{{ __('Profile') }}
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Profile Information --}}
        <div class="card lg:col-span-2">
            <div class="card-header">Profile Information</div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Role Card --}}
        <div class="card">
            <div class="card-header">Role</div>
            <div class="card-body">
                <span class="badge {{ auth()->user()->isAdmin() ? 'badge-danger' : 'badge-info' }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
        {{-- Update Password --}}
        <div class="card">
            <div class="card-header">Update Password</div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="card border-red-200">
            <div class="card-header !text-red-600">Delete Account</div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
