<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-bell mr-2"></i>{{ __('Notifikasi') }}
        </h2>
    </x-slot>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Semua Notifikasi</h6>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm">Mark all as read</button>
                </form>
            @endif
        </div>
        <div class="card-body">
            <div class="list-group">
                @forelse($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ is_null($notification->read_at) ? 'active' : '' }}">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="mb-1">
                                <i class="fas fa-{{ isset($notification->data['submission_status']) ? ($notification->data['submission_status'] == 'accepted' ? 'check-circle text-success' : 'times-circle text-danger') : 'bell' }} mr-2"></i>
                                {{ $notification->data['message'] ?? 'Notification' }}
                            </div>
                            <small>{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        @if(isset($notification->data['catatan']) && $notification->data['catatan'])
                            <p class="mb-1 mt-2"><strong>Catatan:</strong> {{ $notification->data['catatan'] }}</p>
                        @endif
                        @if(is_null($notification->read_at))
                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">Tandai sudah dibaca</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-4">Tidak ada notifikasi.</div>
                @endforelse
            </div>
            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
