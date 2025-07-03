@extends('layouts.app')

@section('title', 'Dashboard Koordinator PKL')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-indigo-900 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name ?? 'Koordinator' }}!</h1>
                <p class="text-indigo-200 mt-1">Kelola program PKL melalui dashboard koordinator SPEKTRA.</p>
            </div>
            <div class="hidden md:block">
                <x-icon name="user-circle" size="xl" class="text-indigo-400" />
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Siswa -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-indigo-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <x-icon name="user-group" size="md" class="text-indigo-700" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                </div>
            </div>
        </x-card>

        <!-- PKL Aktif -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-green-600">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <x-icon name="academic-cap" size="md" class="text-green-600" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">PKL Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPKLAktif }}</p>
                </div>
            </div>
        </x-card>

        <!-- PKL Pending -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-amber-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <x-icon name="clock" size="md" class="text-amber-500" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">PKL Menunggu</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPKLPending }}</p>
                </div>
            </div>
        </x-card>

        <!-- Total Perusahaan -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-sky-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                        <x-icon name="building-office" size="md" class="text-sky-500" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Perusahaan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPerusahaan }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pendaftaran PKL yang Perlu Diverifikasi -->
        <div class="lg:col-span-2">
            <x-card variant="default" padding="none" class="overflow-hidden">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Pendaftaran PKL Menunggu Verifikasi</h3>
                        <x-button variant="ghost" size="sm" href="{{ route('koordinator.registrations') }}" class="text-gray-500 hover:text-gray-700">
                            Lihat Semua
                        </x-button>
                    </div>
                </x-slot>

                <div class="divide-y divide-gray-200">
                    @forelse($pendingPKLs as $pkl)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="clock" size="xs" class="text-amber-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $pkl->user->name }}</span> mendaftar PKL di 
                                    <span class="font-medium">{{ $pkl->company->name }}</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $pkl->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('koordinator.registration.show', $pkl->id) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    Verifikasi
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center">
                        <p class="text-gray-500">Tidak ada pendaftaran PKL yang menunggu verifikasi.</p>
                    </div>
                    @endforelse
                </div>
            </x-card>

            <!-- PKL Aktif -->
            <div class="mt-6">
                <x-card variant="default" padding="none" class="overflow-hidden">
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">PKL Aktif</h3>
                            <x-button variant="ghost" size="sm" href="{{ route('koordinator.monitoring') }}" class="text-gray-500 hover:text-gray-700">
                                Lihat Semua
                            </x-button>
                        </div>
                    </x-slot>

                    <div class="divide-y divide-gray-200">
                        @forelse($ongoingPKLs as $pkl)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <x-icon name="academic-cap" size="xs" class="text-green-600" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $pkl->user->name }}</span> 
                                        sedang melaksanakan PKL di 
                                        <span class="font-medium">{{ $pkl->company->name }}</span>
                                    </p>
                                    <div class="flex items-center mt-1">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $pkl->getProgressPercentage() }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ number_format($pkl->getProgressPercentage(), 0) }}%</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('koordinator.monitoring.show', $pkl->id) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Monitor
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center">
                            <p class="text-gray-500">Tidak ada PKL aktif saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Quick Actions & Aktivitas -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <x-card variant="default" padding="md" class="bg-indigo-50 border-0">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Menu Cepat</h3>
                </x-slot>

                <div class="space-y-3">
                    <x-button variant="primary" size="sm" class="w-full justify-start bg-indigo-700 hover:bg-indigo-800" href="{{ route('koordinator.registrations') }}" icon="clipboard-check">
                        Verifikasi Pendaftaran
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" href="{{ route('koordinator.students') }}" icon="user-group">
                        Kelola Data Siswa
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" href="{{ route('koordinator.companies') }}" icon="building-office">
                        Kelola Perusahaan
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" href="{{ route('koordinator.monitoring') }}" icon="chart-bar">
                        Monitoring PKL
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" href="{{ route('koordinator.reports') }}" icon="document-text">
                        Laporan PKL
                    </x-button>
                </div>
            </x-card>

            <!-- Aktivitas Card -->
            <x-card variant="default" padding="md" class="bg-white border border-gray-200">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                    </div>
                </x-slot>

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($activities as $activity)
                    <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-indigo-500">
                        <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity->date->diffForHumans() }}</p>
                    </div>
                    @empty
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">Tidak ada aktivitas terbaru.</p>
                    </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection 