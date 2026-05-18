<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Monitoring Pengumpulan RPS dan Soal Ujian | D-III Teknik Komputer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-700 bg-white">

    {{-- Navbar --}}
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600">
                        <i class="fas fa-bell text-white text-sm"></i>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Monitoring RPS & Soal Ujian</span>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    <a href="#tentang-prodi" class="text-sm font-medium text-gray-500 hover:text-primary-600 transition">Tentang Prodi</a>
                    <a href="#fitur" class="text-sm font-medium text-gray-500 hover:text-primary-600 transition">Fitur</a>
                    <a href="#cara-kerja" class="text-sm font-medium text-gray-500 hover:text-primary-600 transition">Cara Kerja</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 transition">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
                {{-- Mobile menu button --}}
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="#tentang-prodi" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-md">Tentang Prodi</a>
                <a href="#fitur" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-md">Fitur</a>
                <a href="#cara-kerja" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-md">Cara Kerja</a>
                @if (Route::has('login'))
                    @guest
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-md">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-primary-600 hover:bg-primary-50 rounded-md">Daftar</a>
                        @endif
                    @endguest
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <img src="{{ asset('hero.jpeg') }}" alt="Hero Background" class="w-full h-full object-cover blur-sm">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white text-sm font-semibold mb-6 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-400"></span>
                    </span>
                    Notifikasi WhatsApp Real-time
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                    Sistem Monitoring Pengumpulan<br>
                    <span class="text-white">RPS dan Soal Ujian</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-gray-200 leading-relaxed">
                    <span class="font-semibold text-white">Program Studi D-III Teknik Komputer</span><br>
                    <span class="text-white font-medium">Sekolah Tinggi Teknologi Payakumbuh</span><br class="hidden sm:block">
                    Pantau pengumpulan Rencana Pembelajaran Semester (RPS) dan soal ujian dosen dengan notifikasi WhatsApp otomatis.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary text-base px-8 py-3">
                                <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary text-base px-8 py-3">
                                <i class="fas fa-rocket mr-2"></i> Mulai Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="btn-secondary text-base px-8 py-3">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </a>
                        @endauth
                    @endif
                </div>
                <div class="mt-10 flex items-center justify-center gap-8 text-white">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-white"></i>
                        <span class="text-sm font-medium">Aman</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bolt text-white"></i>
                        <span class="text-sm font-medium">Cepat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-mobile-alt text-white"></i>
                        <span class="text-sm font-medium">Responsif</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About Prodi Section --}}
    <section id="tentang-prodi" class="py-20 bg-gray-50/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center items-center justify-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 border border-primary-100 text-primary-700 text-sm font-semibold mb-4">
                        <i class="fas fa-university text-xs"></i>
                        Tentang Program Studi
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        D-III Teknik Komputer<br>
                        <span class="text-primary-600">STT Payakumbuh</span>
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-6">
                        Program Studi D-III Teknik Komputer Sekolah Tinggi Teknologi Payakumbuh merupakan program studi dengan prospek cerah baik saat ini maupun di masa mendatang. Segala aspek dalam perkembangan teknologi tidak terlepas dari bidang komputerisasi.
                    </p>
                    <p class="text-gray-500 leading-relaxed mb-6">
                        Program studi ini bertujuan untuk menghasilkan ahli madya teknik yang mampu mengembangkan ilmu yang dimiliki, menyelesaikan permasalahan komputer, serta memiliki dasar ilmu yang cukup untuk melanjutkan studi pada jenjang yang lebih tinggi.
                    </p>
                    <div class="flex gap-3 w-full mx-auto justify-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200 text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt text-primary-500 text-xs"></i>
                            Payakumbuh, Sumatera Barat
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200 text-sm text-gray-600">
                            <i class="fas fa-globe text-primary-500 text-xs"></i>
                            sttpyk.ac.id
                        </span>
                </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Fitur Utama</h2>
                <p class="mt-4 text-gray-500">Semua yang Anda butuhkan untuk mengelola dokumen dan komunikasi dosen dalam satu platform.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-100 transition duration-300">
                    <div class="icon-circle bg-emerald-50 text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Notifikasi WhatsApp</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Kirim pengingat otomatis langsung ke WhatsApp dosen. Terhubung dengan WhatsApp Web untuk pengiriman real-time.
                    </p>
                </div>
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-100 transition duration-300">
                    <div class="icon-circle bg-amber-50 text-amber-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-pie text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Dashboard Terpadu</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Tampilan statistik dan overview berbeda untuk Admin dan Dosen. Pantau progres pengumpulan dokumen dengan mudah.
                    </p>
                </div>
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-100 transition duration-300">
                    <div class="icon-circle bg-violet-50 text-violet-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-history text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Riwayat Upload</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Lacak seluruh riwayat pengumpulan dokumen. Admin dapat menerima atau menolak submission dengan catatan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="cara-kerja" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Cara Kerja</h2>
                <p class="mt-4 text-gray-500">Proses sederhana untuk mengelola dokumen dan notifikasi dalam tiga langkah mudah.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8 relative">
                {{-- Connecting line for desktop --}}
                <div class="hidden md:block absolute top-16 left-[20%] right-[20%] h-0.5 bg-primary-100"></div>

                <div class="relative text-center">
                    <div class="relative z-10 mx-auto w-14 h-14 rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-primary-200">
                        1
                    </div>
                    <h3 class="mt-6 text-lg font-bold text-gray-900">Buat Dokumen</h3>
                    <p class="mt-2 text-gray-500">Admin membuat dokumen baru dengan judul, deskripsi, tipe, dan tanggal deadline.</p>
                </div>
                <div class="relative text-center">
                    <div class="relative z-10 mx-auto w-14 h-14 rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-primary-200">
                        2
                    </div>
                    <h3 class="mt-6 text-lg font-bold text-gray-900">Kumpulkan Submission</h3>
                    <p class="mt-2 text-gray-500">Dosen mengumpulkan dokumen yang diminta sebelum deadline yang ditentukan.</p>
                </div>
                <div class="relative text-center">
                    <div class="relative z-10 mx-auto w-14 h-14 rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-primary-200">
                        3
                    </div>
                    <h3 class="mt-6 text-lg font-bold text-gray-900">Kirim Notifikasi</h3>
                    <p class="mt-2 text-gray-500">Kirim pengingat via WhatsApp atau pantau status pengumpulan melalui dashboard.</p>
                </div>
            </div>
        </div>
    </section>


    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
