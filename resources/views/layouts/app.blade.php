<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPEKTRA') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="h-full bg-neutral-50 dark:bg-neutral-900 transition-colors duration-300">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-neutral-800 shadow-medium transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">S</span>
                    </div>
                    <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">SPEKTRA</h1>
                </div>
                <!-- Mobile close button -->
                <button id="sidebar-close" class="lg:hidden p-2 rounded-md text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:bg-neutral-700">
                    <x-icon name="x-mark" size="sm" />
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-3">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-400' : 'text-neutral-700 hover:bg-neutral-50 dark:text-neutral-300 dark:hover:bg-neutral-700' }} transition-colors duration-200">
                        <x-icon name="home" size="sm" class="mr-3 {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-neutral-400 group-hover:text-neutral-500' }}" />
                        Dashboard
                    </a>

                    <!-- PKL Management -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">PKL Management</h3>
                        <div class="mt-2 space-y-1">
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-neutral-50 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors duration-200">
                                <x-icon name="user" size="sm" class="mr-3 text-neutral-400 group-hover:text-neutral-500" />
                                Profil
                            </a>
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-neutral-50 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors duration-200">
                                <x-icon name="pencil" size="sm" class="mr-3 text-neutral-400 group-hover:text-neutral-500" />
                                Pendaftaran PKL
                            </a>
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-neutral-50 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors duration-200">
                                <x-icon name="eye" size="sm" class="mr-3 text-neutral-400 group-hover:text-neutral-500" />
                                Monitoring
                            </a>
                        </div>
                    </div>

                    <!-- Reports -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Laporan</h3>
                        <div class="mt-2 space-y-1">
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-neutral-50 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors duration-200">
                                <x-icon name="pencil" size="sm" class="mr-3 text-neutral-400 group-hover:text-neutral-500" />
                                Laporan Harian
                            </a>
                            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg hover:bg-neutral-50 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-colors duration-200">
                                <x-icon name="pencil" size="sm" class="mr-3 text-neutral-400 group-hover:text-neutral-500" />
                                Laporan Akhir
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-neutral-300 dark:bg-neutral-600 rounded-full flex items-center justify-center">
                        <x-icon name="user" size="sm" class="text-neutral-600 dark:text-neutral-300" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                            {{ Auth::user()->name ?? 'User' }}
                        </p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                            {{ Auth::user()->email ?? 'user@example.com' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-neutral-600 bg-opacity-75 z-40 lg:hidden hidden"></div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Header -->
            <header class="bg-white dark:bg-neutral-800 shadow-soft border-b border-neutral-200 dark:border-neutral-700">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Mobile menu button -->
                        <button id="sidebar-open" class="lg:hidden p-2 rounded-md text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:bg-neutral-700">
                            <x-icon name="bars-3" size="md" />
                        </button>

                        <!-- Breadcrumb -->
                        <div class="flex-1 min-w-0 lg:ml-0 ml-4">
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2">
                                    @if(isset($breadcrumbs))
                                        @foreach($breadcrumbs as $breadcrumb)
                                            <li class="flex items-center">
                                                @if(!$loop->first)
                                                    <x-icon name="chevron-down" size="xs" class="mr-2 text-neutral-400 rotate-[-90deg]" />
                                                @endif
                                                @if($loop->last)
                                                    <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ $breadcrumb['name'] }}</span>
                                                @else
                                                    <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">{{ $breadcrumb['name'] }}</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <span class="text-sm font-medium text-neutral-900 dark:text-white">@yield('title', 'Dashboard')</span>
                                        </li>
                                    @endif
                                </ol>
                            </nav>
                        </div>

                        <!-- Right side -->
                        <div class="flex items-center space-x-4">
                            <!-- Dark mode toggle -->
                            <button id="theme-toggle" class="p-2 rounded-lg text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:bg-neutral-700 transition-colors duration-200">
                                <x-icon name="cog-6-tooth" size="sm" />
                            </button>

                            <!-- Notifications -->
                            <button class="p-2 rounded-lg text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:bg-neutral-700 transition-colors duration-200 relative">
                                <x-icon name="bell" size="sm" />
                                <span class="absolute top-1 right-1 w-2 h-2 bg-error-500 rounded-full"></span>
                            </button>

                            <!-- User menu -->
                            <div class="relative">
                                <button id="user-menu-button" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-neutral-300 dark:bg-neutral-600 rounded-full flex items-center justify-center">
                                        <x-icon name="user" size="sm" class="text-neutral-600 dark:text-neutral-300" />
                                    </div>
                                    <x-icon name="chevron-down" size="xs" class="text-neutral-400" />
                                </button>
                                
                                <!-- User dropdown menu (hidden by default) -->
                                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-800 rounded-lg shadow-large border border-neutral-200 dark:border-neutral-700 py-1 z-50">
                                    <a href="#" class="block px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700">Profil Saya</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700">Pengaturan</a>
                                    <div class="border-t border-neutral-200 dark:border-neutral-700 my-1"></div>
                                    <form method="POST" action="{{ route('logout') ?? '#' }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    
    <script>
        // Mobile sidebar toggle
        const sidebarOpen = document.getElementById('sidebar-open');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }

        sidebarOpen?.addEventListener('click', openSidebar);
        sidebarClose?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // User menu toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        userMenuButton?.addEventListener('click', () => {
            userMenu.classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!userMenuButton?.contains(e.target) && !userMenu?.contains(e.target)) {
                userMenu?.classList.add('hidden');
            }
        });

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
