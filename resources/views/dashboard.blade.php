@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gray-900 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!</h1>
                <p class="text-gray-300 mt-1">Kelola sistem SPEKTRA melalui dashboard admin ini.</p>
            </div>
            <div class="hidden md:block">
                <x-icon name="cog-6-tooth" size="xl" class="text-gray-400" />
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Siswa -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-gray-900">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <x-icon name="user" size="md" class="text-gray-900" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-900">124</p>
                </div>
            </div>
        </x-card>

        <!-- Total PKL Aktif -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <x-icon name="pencil" size="md" class="text-gray-700" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">PKL Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">87</p>
                </div>
            </div>
        </x-card>

        <!-- Laporan Pending -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-gray-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <x-icon name="eye" size="md" class="text-gray-500" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Laporan Pending</p>
                    <p class="text-2xl font-bold text-gray-900">23</p>
                </div>
            </div>
        </x-card>

        <!-- Notifikasi -->
        <x-card variant="default" padding="md" class="border-l-4 border-l-gray-400">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <x-icon name="bell" size="md" class="text-gray-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Notifikasi</p>
                    <p class="text-2xl font-bold text-gray-900">8</p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2">
            <x-card variant="default" padding="none" class="overflow-hidden">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                        <x-button variant="ghost" size="sm" href="#" class="text-gray-500 hover:text-gray-700">
                            Lihat Semua
                        </x-button>
                    </div>
                </x-slot>

                <div class="divide-y divide-gray-200">
                    <!-- Activity Item -->
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="user" size="xs" class="text-gray-900" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">Siswa Baru</span> telah mendaftar
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Hari ini, 10:45</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Baru
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="pencil" size="xs" class="text-gray-700" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">Laporan Harian</span> membutuhkan persetujuan
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Hari ini, 09:32</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Pending
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="eye" size="xs" class="text-gray-500" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">Monitoring PKL</span> telah diperbarui
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Kemarin, 14:20</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Update
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-icon name="bell" size="xs" class="text-gray-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">Pengumuman</span> jadwal bimbingan telah diperbarui
                                </p>
                                <p class="text-xs text-gray-500 mt-1">2 hari yang lalu</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Info
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Quick Actions & Notifications -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <x-card variant="default" padding="md" class="bg-gray-50 border-0">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </x-slot>

                <div class="space-y-3">
                    <x-button variant="primary" size="sm" class="w-full justify-start bg-gray-900 hover:bg-gray-800" icon="plus">
                        Tambah Siswa Baru
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" icon="eye">
                        Kelola Data PKL
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" icon="pencil">
                        Review Laporan
                    </x-button>
                    <x-button variant="outline" size="sm" class="w-full justify-start" icon="cog-6-tooth">
                        Pengaturan Sistem
                    </x-button>
                </div>
            </x-card>

            <!-- Notifications Card -->
            <x-card variant="default" padding="md" class="bg-white border border-gray-200">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            8 Baru
                        </span>
                    </div>
                </x-slot>

                <div class="space-y-4">
                    <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-gray-900">
                        <p class="text-sm font-medium text-gray-900">Persetujuan Laporan</p>
                        <p class="text-xs text-gray-500 mt-1">5 laporan baru membutuhkan persetujuan</p>
                    </div>
                    
                    <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-gray-700">
                        <p class="text-sm font-medium text-gray-900">Pendaftaran PKL</p>
                        <p class="text-xs text-gray-500 mt-1">3 pendaftaran baru menunggu verifikasi</p>
                    </div>
                    
                    <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-gray-500">
                        <p class="text-sm font-medium text-gray-900">Jadwal Monitoring</p>
                        <p class="text-xs text-gray-500 mt-1">Jadwal monitoring minggu ini telah diperbarui</p>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <x-button variant="ghost" size="sm" class="text-gray-500 hover:text-gray-700">
                        Lihat Semua Notifikasi
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>
    
    <!-- Recent Data Table -->
    <x-card variant="default" padding="md" class="overflow-hidden">
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Data Siswa PKL Terbaru</h3>
                <div class="flex space-x-2">
                    <x-button variant="outline" size="sm" icon="plus">
                        Tambah
                    </x-button>
                    <x-button variant="ghost" size="sm" icon="eye">
                        Lihat Semua
                    </x-button>
                </div>
            </div>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat PKL</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">AS</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Andi Saputra</div>
                                    <div class="text-xs text-gray-500">XII RPL 1</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">PT Teknologi Maju</div>
                            <div class="text-xs text-gray-500">Jakarta</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-900 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-xs text-gray-500">75%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-button variant="ghost" size="xs" icon="eye" class="text-gray-500 hover:text-gray-700">
                                Detail
                            </x-button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">BP</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Budi Pratama</div>
                                    <div class="text-xs text-gray-500">XII RPL 2</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">CV Digital Kreatif</div>
                            <div class="text-xs text-gray-500">Bandung</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-900 h-2 rounded-full" style="width: 60%"></div>
                            </div>
                            <span class="text-xs text-gray-500">60%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-button variant="ghost" size="xs" icon="eye" class="text-gray-500 hover:text-gray-700">
                                Detail
                            </x-button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">CW</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Citra Wulandari</div>
                                    <div class="text-xs text-gray-500">XII RPL 1</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">PT Solusi Digital</div>
                            <div class="text-xs text-gray-500">Jakarta</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-900 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-xs text-gray-500">45%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-button variant="ghost" size="xs" icon="eye" class="text-gray-500 hover:text-gray-700">
                                Detail
                            </x-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
