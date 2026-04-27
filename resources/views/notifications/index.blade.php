<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-bell mr-2"></i>{{ __('Notifikasi') }}
    </x-slot>

    <div class="card">
        <div class="card-header flex flex-wrap items-center justify-between gap-3">
            <span>Semua Notifikasi</span>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body !p-0">
            <div class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    @php
                        $notifStatus = $notification->data['submission_status'] ?? null;
                        $icon = $notifStatus === 'accepted' ? 'fa-check-circle text-emerald-500' : ($notifStatus === 'rejected' ? 'fa-times-circle text-red-500' : 'fa-bell text-primary-500');
                    @endphp
                    <div class="flex flex-col gap-2 px-6 py-4 {{ is_null($notification->read_at) ? 'bg-primary-50' : '' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0">
                                <i class="fas {{ $icon }} mt-0.5 shrink-0"></i>
                                <span class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'Notification' }}</span>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if(isset($notification->data['catatan']) && $notification->data['catatan'])
                            <p class="text-sm text-gray-500 ml-7"><strong>Catatan:</strong> {{ $notification->data['catatan'] }}</p>
                        @endif
                        @if(is_null($notification->read_at))
                            <div class="ml-7">
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-primary-600 hover:text-primary-800 font-medium">
                                        Tandai sudah dibaca
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-400">Tidak ada notifikasi.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</x-app-layout>
