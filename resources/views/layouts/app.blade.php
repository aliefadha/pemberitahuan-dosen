<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pemberitahuan Dosen') }}</title>
    <link href="{{ asset('vendor/sb-admin-2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('vendor/sb-admin-2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-text mx-3">Pemberitahuan <sup>Dosen</sup></div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            @if(auth()->user()->isAdmin())
            <div class="sidebar-heading">
                Admin
            </div>
            <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Kelola User</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.dokumens.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dokumens.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Kelola Dokumen</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.whatsapp.index') }}">
                    <i class="fab fa-whatsapp fa-fw"></i>
                    <span>WhatsApp</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('notifications.index') }}">
                    <i class="fas fa-fw fa-bell"></i>
                    <span>Notifikasi</span>
                </a>
            </li>
            @endif
            @if(auth()->user()->isDosen())
            <div class="sidebar-heading">
                Menu
            </div>
            
            <li class="nav-item {{ request()->routeIs('dokumens.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dokumens.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Dokumen</span>
                </a>
            </li>
            @endif
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                @php
                                    $unreadCount = Auth::user()->unreadNotifications()->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge badge-danger badge-counter">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                @endif
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="notificationsDropdown">
                                <h6 class="dropdown-header">Notifications</h6>
                                @php
                                    $notifications = Auth::user()->notifications()->limit(5)->get();
                                @endphp
                                @forelse($notifications as $notification)
                                    <div class="dropdown-item d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-{{ isset($notification->data['type']) && $notification->data['type'] == 'accepted' ? 'success' : (isset($notification->data['type']) && $notification->data['type'] == 'rejected' ? 'danger' : 'primary') }}">
                                                <i class="fas fa-{{ isset($notification->data['submission_status']) ? ($notification->data['submission_status'] == 'accepted' ? 'check' : 'times') : 'bell' }} text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</div>
                                            <span>{{ $notification->data['message'] ?? $notification->data['dokumen_id'] ?? 'New notification' }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="dropdown-item text-center text-gray-500">No notifications</div>
                                @endforelse
                                @if($notifications->count() > 0)
                                    <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">Show All Alerts</a>
                                @endif
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <span class="badge badge-{{ Auth::user()->isAdmin() ? 'danger' : 'info' }}">{{ ucfirst(Auth::user()->role) }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Pemberitahuan Dosen {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/js/sb-admin-2.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
