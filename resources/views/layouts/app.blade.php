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
<body class="h-full bg-white transition-colors duration-300">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-sm transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gray-900 rounded-md flex items-center justify-center">
                        <span class="text-white font-bold text-sm">S</span>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-900">SPEKTRA</h1>
                </div>
                <!-- Mobile close button -->
                <button id="sidebar-close" class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                    <x-icon name="x-mark" size="sm" />
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-3 overflow-y-auto" style="height: calc(100% - 128px)">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                        <x-icon name="home" size="sm" class="mr-3 {{ request()->routeIs('dashboard') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                        Dashboard
                    </a>

                    <!-- Notifications section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Notifikasi</h3>
                        <div class="mt-2">
                            <button id="notifications-btn" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                                <div class="flex items-center">
                                    <x-icon name="bell" size="sm" class="mr-3 text-gray-500 group-hover:text-gray-700" />
                                    <span>Notifikasi</span>
                                </div>
                                <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium text-white bg-gray-900 rounded-full">3</span>
                            </button>
                            <!-- Notification dropdown (hidden by default) -->
                            <div id="notifications-dropdown" class="hidden mt-2 bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <p class="text-xs font-medium text-gray-500">Notifikasi Terbaru</p>
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                                        <p class="text-sm font-medium text-gray-900">Pendaftaran PKL Baru</p>
                                        <p class="text-xs text-gray-500">Siswa baru telah mendaftar PKL</p>
                                        <p class="text-xs text-gray-400 mt-1">5 menit yang lalu</p>
                                    </a>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                                        <p class="text-sm font-medium text-gray-900">Laporan Harian</p>
                                        <p class="text-xs text-gray-500">5 laporan harian baru menunggu persetujuan</p>
                                        <p class="text-xs text-gray-400 mt-1">1 jam yang lalu</p>
                                    </a>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                                        <p class="text-sm font-medium text-gray-900">Jadwal Monitoring</p>
                                        <p class="text-xs text-gray-500">Monitoring PKL dijadwalkan besok</p>
                                        <p class="text-xs text-gray-400 mt-1">3 jam yang lalu</p>
                                    </a>
                                </div>
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <a href="#" class="text-xs font-medium text-gray-700 hover:text-gray-900">Lihat Semua Notifikasi</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!Auth::user()->isSiswa())
                    <!-- PKL Management -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">PKL Management</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('pkl.siswa') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('pkl.siswa*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="user" size="sm" class="mr-3 {{ request()->routeIs('pkl.siswa*') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Siswa
                            </a>
                            <a href="{{ route('pkl.pendaftaran') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('pkl.pendaftaran*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="pencil" size="sm" class="mr-3 {{ request()->routeIs('pkl.pendaftaran*') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Pendaftaran PKL
                            </a>
                            <a href="{{ route('pkl.monitoring') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('pkl.monitoring*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="eye" size="sm" class="mr-3 {{ request()->routeIs('pkl.monitoring*') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Monitoring
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->isSiswa())
                    <!-- PKL Siswa -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">PKL Saya</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('pkl.pendaftaran.create') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('pkl.pendaftaran.create') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="pencil" size="sm" class="mr-3 {{ request()->routeIs('pkl.pendaftaran.create') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Pendaftaran PKL
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Reports -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('laporan.harian.index') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('laporan.harian*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="document-text" size="sm" class="mr-3 {{ request()->routeIs('laporan.harian*') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Laporan Harian
                            </a>
                            <a href="{{ route('laporan.akhir.index') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('laporan.akhir*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="document-check" size="sm" class="mr-3 {{ request()->routeIs('laporan.akhir*') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Laporan Akhir
                            </a>
                        </div>
                    </div>
                    
                    @if(!Auth::user()->isSiswa())
                    <!-- Settings -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengaturan</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('settings.system') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('settings.system*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="cog-6-tooth" size="sm" class="mr-3 {{ request()->routeIs('settings.system*') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Sistem
                            </a>
                            <a href="{{ route('settings.users') ?? '#' }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('settings.users*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} transition-colors duration-200">
                                <x-icon name="user" size="sm" class="mr-3 {{ request()->routeIs('settings.users*') ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700' }}" />
                                Pengguna
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </nav>

            <!-- Sidebar Footer with enhanced profile menu -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                <div class="relative">
                    <button id="profile-menu-button" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <x-icon name="user" size="sm" class="text-gray-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ Auth::user()->name ?? 'User' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ Auth::user()->email ?? 'user@example.com' }}
                                </p>
                            </div>
                        </div>
                        <x-icon name="chevron-up" size="xs" class="text-gray-400 transition-transform" id="profile-chevron" />
                    </button>
                    
                    <!-- Profile dropdown menu (hidden by default) -->
                    <div id="profile-menu" class="hidden absolute bottom-16 left-0 right-0 mb-1 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <a href="{{ route('profile.show') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                        <a href="{{ route('profile.settings') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden hidden"></div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Mobile menu button (top left for mobile) -->
            <div class="lg:hidden fixed top-4 left-4 z-30">
                <button id="sidebar-open" class="p-2 rounded-md text-gray-500 hover:text-gray-700 bg-white shadow-md hover:bg-gray-100">
                    <x-icon name="bars-3" size="md" />
                </button>
            </div>
            
            <!-- Page Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Page Title -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                        @if(isset($breadcrumbs))
                            <nav class="flex mt-2" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2">
                                    @foreach($breadcrumbs as $breadcrumb)
                                        <li class="flex items-center">
                                            @if(!$loop->first)
                                                <x-icon name="chevron-down" size="xs" class="mx-2 text-gray-400 rotate-[-90deg]" />
                                            @endif
                                            @if($loop->last)
                                                <span class="text-sm font-medium text-gray-900">{{ $breadcrumb['name'] }}</span>
                                            @else
                                                <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">{{ $breadcrumb['name'] }}</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                        @endif
                    </div>
                    
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

        // Profile menu toggle
        const profileMenuButton = document.getElementById('profile-menu-button');
        const profileMenu = document.getElementById('profile-menu');
        const profileChevron = document.getElementById('profile-chevron');

        profileMenuButton?.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            profileChevron.classList.toggle('rotate-180');
        });

        // Notifications dropdown toggle
        const notificationsBtn = document.getElementById('notifications-btn');
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        notificationsBtn?.addEventListener('click', () => {
            notificationsDropdown.classList.toggle('hidden');
        });

        // Close menus when clicking outside
        document.addEventListener('click', (e) => {
            // Close profile menu
            if (!profileMenuButton?.contains(e.target) && !profileMenu?.contains(e.target)) {
                profileMenu?.classList.add('hidden');
                profileChevron?.classList.remove('rotate-180');
            }

            // Close notifications dropdown
            if (!notificationsBtn?.contains(e.target) && !notificationsDropdown?.contains(e.target)) {
                notificationsDropdown?.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
