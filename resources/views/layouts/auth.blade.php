<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPEKTRA') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
    
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        .dark .glassmorphism {
            background: rgba(38, 38, 38, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .theme-transition {
            transition: all 0.5s ease;
        }
        
        .theme-icon-light, .theme-icon-dark {
            transition: opacity 0.3s ease, transform 0.5s ease;
        }
    </style>
</head>
<body class="h-full bg-neutral-50 dark:bg-neutral-900 theme-transition">
    <div class="min-h-full flex">
        <!-- Left side - Branding/Image -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 to-primary-800 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 py-12">
                <div class="max-w-md">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">S</span>
                        </div>
                        <h1 class="text-3xl font-bold text-white">SPEKTRA</h1>
                    </div>
                    
                    <!-- Description -->
                    <h2 class="text-2xl font-semibold text-white mb-4">
                        Sistem Pengelolaan PKL Terpadu
                    </h2>
                    <p class="text-primary-100 text-lg leading-relaxed mb-8">
                        Platform digital yang memudahkan pengelolaan Praktik Kerja Lapangan (PKL) dengan fitur monitoring, pelaporan, dan komunikasi yang terintegrasi.
                    </p>
                    
                    <!-- Features -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                <x-icon name="eye" size="xs" class="text-white" />
                            </div>
                            <span class="text-primary-100">Monitoring real-time progress PKL</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                <x-icon name="pencil" size="xs" class="text-white" />
                            </div>
                            <span class="text-primary-100">Pelaporan digital terintegrasi</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                <x-icon name="user" size="xs" class="text-white" />
                            </div>
                            <span class="text-primary-100">Manajemen profil dan dokumen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Auth Form -->
        <div class="flex-1 flex flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">S</span>
                    </div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">SPEKTRA</h1>
                </div>

                <!-- Page Title -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-neutral-900 dark:text-white theme-transition">
                        @yield('title', 'Masuk ke Akun')
                    </h2>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 theme-transition">
                        @yield('subtitle', 'Silakan masuk untuk mengakses dashboard PKL Anda')
                    </p>
                </div>

                <!-- Auth Form Content -->
                <div class="glassmorphism py-8 px-6 rounded-xl theme-transition">
                    @yield('content')
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center">
                    @yield('footer-links')
                </div>

                <!-- Dark Mode Toggle -->
                <div class="mt-8 flex justify-center">
                    <button id="theme-toggle" class="p-2 rounded-lg text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:bg-neutral-700 transition-colors duration-200">
                        <div class="theme-icon-light block dark:hidden">
                            <x-icon name="sun" size="sm" />
                        </div>
                        <div class="theme-icon-dark hidden dark:block">
                            <x-icon name="moon" size="sm" />
                        </div>
                        <span class="sr-only">Toggle theme</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    
    <script>
        // Dark mode toggle
        const themeToggle = document.getElementById('theme-toggle');
        
        themeToggle?.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });

        // Initialize theme
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>
