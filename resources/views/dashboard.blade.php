@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name ?? 'User' }}!</h1>
                <p class="text-primary-100 mt-1">Kelola aktivitas PKL Anda dengan mudah melalui dashboard ini.</p>
            </div>
            <div class="hidden md:block">
                <x-icon name="home" size="xl" class="text-primary-200" />
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total PKL -->
        <x-card variant="default" padding="md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/20 rounded-lg flex items-center justify-center">
                        <x-icon name="user" size="md" class="text-primary-600 dark:text-primary-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total PKL</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">1</p>
                </div>
            </div>
        </x-card>

        <!-- Laporan Pending -->
        <x-card variant="default" padding="md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/20 rounded-lg flex items-center justify-center">
                        <x-icon name="pencil" size="md" class="text-warning-600 dark:text-warning-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Laporan Pending</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">3</p>
                </div>
            </div>
        </x-card>

        <!-- Progress -->
        <x-card variant="default" padding="md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 dark:bg-success-900/20 rounded-lg flex items-center justify-center">
                        <x-icon name="eye" size="md" class="text-success-600 dark:text-success-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Progress</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">75%</p>
                </div>
            </div>
        </x-card>

        <!-- Notifikasi -->
        <x-card variant="default" padding="md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-error-100 dark:bg-error-900/20 rounded-lg flex items-center justify-center">
                        <x-icon name="bell" size="md" class="text-error-600 dark:text-error-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Notifikasi</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">5</p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2">
            <x-card variant="default" padding="none">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Aktivitas Terbaru</h3>
                        <x-button variant="ghost" size="sm" href="#">
                            Lihat Semua
                        </x-button>
                    </div>
                </x-slot>

                <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    <!-- Activity Item -->
                    <div class="p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="pencil" size="xs" class="text-primary-600 dark:text-primary-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-neutral-900 dark:text-white">
                                    <span class="font-medium">Laporan Harian</span> telah disubmit
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">2 jam yang lalu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-success-100 dark:bg-success-900/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="eye" size="xs" class="text-success-600 dark:text-success-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-neutral-900 dark:text-white">
                                    <span class="font-medium">Progress PKL</span> diupdate menjadi 75%
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">5 jam yang lalu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-warning-100 dark:bg-warning-900/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="bell" size="xs" class="text-warning-600 dark:text-warning-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-neutral-900 dark:text-white">
                                    <span class="font-medium">Reminder</span> untuk submit laporan mingguan
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">1 hari yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <x-card variant="default" padding="md">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Quick Actions</h3>
                </x-slot>

                <div class="space-y-3">
                    <x-button variant="primary" size="sm" class="w-full justify-start" icon="plus">
                        Buat Laporan Harian
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" icon="eye">
                        Lihat Progress PKL
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" icon="user">
                        Update Profil
                    </x-button>
                </div>
            </x-card>

            <!-- Notifications Card -->
            <x-card variant="default" padding="md">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Notifikasi</h3>
                </x-slot>

                <div class="space-y-3">
                    <x-alert type="warning" :dismissible="true">
                        <strong>Deadline Reminder:</strong> Laporan mingguan harus disubmit dalam 2 hari.
                    </x-alert>
                    
                    <x-alert type="info" :dismissible="true">
                        <strong>Info:</strong> Jadwal bimbingan PKL telah diupdate.
                    </x-alert>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
