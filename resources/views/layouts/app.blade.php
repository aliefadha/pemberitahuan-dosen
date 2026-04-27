<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pemberitahuan Dosen') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50" x-data="{ sidebarOpen: false, notifOpen: false, userOpen: false }">
    <div class="flex h-full">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false">
            <div class="absolute inset-0 bg-gray-600/75"></div>
        </div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-primary-700 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto"
        >
            <div class="flex h-16 shrink-0 items-center justify-center border-b border-primary-600">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white tracking-wide">
                    Pemberitahuan Dosen
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ request()->routeIs('dashboard') ? 'bg-primary-800 text-white' : 'text-primary-100 hover:bg-primary-600 hover:text-white' }}">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                @if(auth()->user()->isAdmin())
                <div class="pt-4 pb-1">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-primary-300">Admin</p>
                </div>

                <a href="{{ route('admin.users.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.users.*') ? 'bg-primary-800 text-white' : 'text-primary-100 hover:bg-primary-600 hover:text-white' }}">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span>Kelola User</span>
                </a>

                <a href="{{ route('admin.dokumens.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.dokumens.*') ? 'bg-primary-800 text-white' : 'text-primary-100 hover:bg-primary-600 hover:text-white' }}">
                    <i class="fas fa-file-alt w-5 text-center"></i>
                    <span>Kelola Dokumen</span>
                </a>

                <a href="{{ route('admin.whatsapp.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.whatsapp.*') ? 'bg-primary-800 text-white' : 'text-primary-100 hover:bg-primary-600 hover:text-white' }}">
                    <i class="fab fa-whatsapp w-5 text-center"></i>
                    <span>WhatsApp</span>
                </a>

                <div class="pt-4 pb-1">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-primary-300">Notifikasi</p>
                </div>

                <a href="{{ route('notifications.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ request()->routeIs('notifications.*') ? 'bg-primary-800 text-white' : 'text-primary-100 hover:bg-primary-600 hover:text-white' }}">
                    <i class="fas fa-bell w-5 text-center"></i>
                    <span>Notifikasi</span>
                </a>
                @endif

                @if(auth()->user()->isDosen())
                <div class="pt-4 pb-1">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-primary-300">Menu</p>
                </div>

                <a href="{{ route('dokumens.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ request()->routeIs('dokumens.*') ? 'bg-primary-800 text-white' : 'text-primary-100 hover:bg-primary-600 hover:text-white' }}">
                    <i class="fas fa-file-alt w-5 text-center"></i>
                    <span>Dokumen</span>
                </a>
                @endif
            </nav>

            {{-- Sidebar footer --}}
            <div class="border-t border-primary-600 p-4">
                <p class="text-xs text-primary-300 text-center">&copy; {{ date('Y') }} Pemberitahuan Dosen</p>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex flex-1 flex-col overflow-hidden lg:ml-0">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 bg-white shadow-sm border-b border-gray-200">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                    {{-- Mobile menu button + page title --}}
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        @if(isset($header))
                            <div class="text-lg font-semibold text-gray-800">
                                {{ $header }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4">
                        {{-- Notification Bell --}}
                        @php
                            $unreadCount = Auth::user()->unreadNotifications()->count();
                            $topNotifications = Auth::user()->notifications()->latest()->limit(5)->get();
                        @endphp
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="relative rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                <i class="fas fa-bell text-lg"></i>
                                @if($unreadCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" x-cloak x-transition
                                 class="absolute right-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-black/5">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900">Notifikasi</p>
                                </div>
                                <div class="max-h-72 overflow-y-auto">
                                    @forelse($topNotifications as $notification)
                                        <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0">
                                            @php
                                                $notifType = $notification->data['type'] ?? null;
                                                $notifStatus = $notification->data['submission_status'] ?? null;
                                                $bgColor = $notifStatus === 'accepted' ? 'bg-emerald-500' : ($notifStatus === 'rejected' ? 'bg-red-500' : 'bg-primary-500');
                                                $icon = $notifStatus === 'accepted' ? 'fa-check' : ($notifStatus === 'rejected' ? 'fa-times' : 'fa-bell');
                                            @endphp
                                            <div class="icon-circle shrink-0 {{ $bgColor }}">
                                                <i class="fas {{ $icon }} text-white text-xs"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                                                <p class="text-sm text-gray-800 truncate">{{ $notification->data['message'] ?? 'Notifikasi baru' }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-sm text-gray-500">Tidak ada notifikasi</div>
                                    @endforelse
                                </div>
                                @if($topNotifications->count() > 0)
                                    <a href="{{ route('notifications.index') }}" class="block px-4 py-2.5 text-center text-sm text-primary-600 hover:bg-gray-50 border-t border-gray-100 font-medium">
                                        Lihat Semua
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- User Dropdown --}}
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 rounded-full p-1 hover:bg-gray-100">
                                <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <span class="badge {{ Auth::user()->isAdmin() ? 'badge-danger' : 'badge-info' }}">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </button>

                            <div x-show="open" x-cloak x-transition
                                 class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black/5">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user fa-sm text-gray-400 w-5 text-center"></i>
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-b-lg">
                                        <i class="fas fa-sign-out-alt fa-sm w-5 text-center"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-cloak class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 flex items-start gap-3">
                        <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                        <span class="flex-1 text-sm text-emerald-700">{{ session('success') }}</span>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-cloak class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <span class="flex-1 text-sm text-red-700">{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-400 hover:text-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
