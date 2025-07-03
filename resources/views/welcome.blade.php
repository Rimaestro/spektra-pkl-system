<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPEKTRA') }} - Sistem Praktik Kerja Lapangan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Header/Navbar -->
    <header class="fixed w-full bg-white dark:bg-gray-800 shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 bg-gray-900 dark:bg-white rounded-md flex items-center justify-center mr-2">
                            <span class="text-white dark:text-gray-900 font-bold text-sm">S</span>
                        </div>
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">SPEKTRA</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    @if (Route::has('login'))
                        <div class="flex space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-base bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-base bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-base bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-white to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen flex flex-col justify-center">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="w-full lg:w-1/2 animate-fade-in">
                    <div class="space-y-6">
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                            Sistem Praktik Kerja Lapangan Terintegrasi
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl">
                            Platform modern untuk mengelola seluruh proses PKL dengan mudah. Mulai dari pendaftaran, monitoring, hingga pelaporan dalam satu sistem terintegrasi.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="btn-base px-6 py-3 bg-gray-900 text-white hover:bg-gray-800 focus:ring-gray-500 dark:bg-gray-700 dark:hover:bg-gray-600">
                                Mulai Sekarang
                            </a>
                            <a href="#fitur" class="btn-base px-6 py-3 bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-500 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700">
                                Pelajari Fitur
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 animate-fade-in">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-large p-4 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-900 rounded-full opacity-50"></div>
                        <div class="relative z-10">
                            <img src="https://source.unsplash.com/random/800x600/?students" alt="Praktik Kerja Lapangan" class="w-full h-auto rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 animate-slide-up">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    SPEKTRA menyediakan berbagai fitur untuk memudahkan pengelolaan PKL
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in">
                <!-- Feature 1 -->
                <div class="card-base p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-medium">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Pendaftaran PKL Online</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Daftar PKL dengan mudah melalui platform online. Upload dokumen persyaratan dan pantau status persetujuan.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-base p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-medium">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Monitoring Kegiatan</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Koordinator dapat memantau kegiatan siswa selama PKL. Jadwalkan monitoring dan berikan umpan balik.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-base p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-medium">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Laporan Harian & Akhir</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Siswa dapat mengirimkan laporan harian dan laporan akhir PKL melalui platform. Pembimbing dapat memberikan review.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card-base p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-medium">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Manajemen Perusahaan</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Kelola database perusahaan mitra PKL dan distribusi siswa di berbagai perusahaan.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card-base p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-medium">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Notifikasi & Pengingat</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Dapatkan notifikasi untuk semua aktivitas penting. Pengingat jadwal monitoring dan tenggat laporan.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card-base p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-medium">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Manajemen Jadwal</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Atur jadwal PKL dengan kalender terintegrasi. Koordinasi antara sekolah, siswa dan perusahaan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 animate-slide-up">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Bagaimana Cara Kerjanya</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Proses PKL yang sederhana dan terstruktur
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 animate-fade-in">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-gray-900 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6 shadow-md">
                        <span class="text-xl text-white font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Pendaftaran</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Siswa mendaftar dan memilih perusahaan tempat PKL melalui platform.
                    </p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-gray-900 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6 shadow-md">
                        <span class="text-xl text-white font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Pelaksanaan</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Siswa menjalani PKL dan melaporkan kegiatan secara berkala.
                    </p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-gray-900 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6 shadow-md">
                        <span class="text-xl text-white font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Evaluasi</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Pembimbing mengevaluasi kinerja siswa dan memberikan nilai akhir.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-900 dark:bg-black">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-6 animate-slide-up">Mulai Gunakan SPEKTRA Sekarang</h2>
            <p class="text-xl text-gray-300 mb-8 animate-slide-up">
                Tingkatkan efisiensi pengelolaan PKL di institusi Anda dengan sistem terintegrasi
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in">
                <a href="{{ route('register') }}" class="btn-base px-8 py-3 bg-white text-gray-900 hover:bg-gray-100 focus:ring-gray-300">
                    Daftar Sekarang
                </a>
                @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn-base px-8 py-3 bg-transparent text-white border border-white hover:bg-gray-800 hover:border-gray-800 focus:ring-gray-700">
                    Masuk
                </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gray-900 dark:bg-white rounded-md flex items-center justify-center mr-2">
                            <span class="text-white dark:text-gray-900 font-bold text-sm">S</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">SPEKTRA</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Sistem manajemen PKL terintegrasi untuk meningkatkan efisiensi dan efektivitas proses praktik kerja lapangan.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="#fitur" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">Fitur</a>
                        </li>
                        @if (Route::has('login'))
                        <li>
                            <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">Masuk</a>
                        </li>
                        @endif
                        @if (Route::has('register'))
                        <li>
                            <a href="{{ route('register') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">Daftar</a>
                        </li>
                        @endif
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@spektra.id
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            (021) 12345678
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-700 mt-10 pt-6">
                <p class="text-center text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} SPEKTRA. Seluruh hak cipta dilindungi.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
