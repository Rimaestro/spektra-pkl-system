@extends('layouts.app')

@section('title', 'Data Table Example')

@php
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('dashboard') ?? '#'],
    ['name' => 'Examples', 'url' => '#'],
    ['name' => 'Data Table', 'url' => '#'],
];
@endphp

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Data Siswa PKL</h1>
            <p class="text-neutral-600 dark:text-neutral-400 mt-1">Kelola data siswa yang sedang melaksanakan PKL</p>
        </div>
        <x-button variant="primary" size="md" icon="plus">
            Tambah Siswa
        </x-button>
    </div>

    <!-- Filters and Search -->
    <x-card variant="default" padding="md">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <x-input 
                    type="search"
                    placeholder="Cari mahasiswa..."
                    icon="user"
                />
            </div>
            
            <!-- Filters -->
            <div class="flex space-x-3">
                <x-select 
                    placeholder="Status"
                    :options="[
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'pending' => 'Pending',
                    ]"
                />
                
                <x-select 
                    placeholder="Periode"
                    :options="[
                        '2024-1' => '2024 Semester 1',
                        '2024-2' => '2024 Semester 2',
                        '2023-2' => '2023 Semester 2',
                    ]"
                />
                
                <x-button variant="outline" size="md" icon="cog-6-tooth">
                    Filter
                </x-button>
            </div>
        </div>
    </x-card>

    <!-- Data Table -->
    <x-card variant="default" padding="none">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Mahasiswa</span>
                                <x-icon name="chevron-down" size="xs" class="text-neutral-400" />
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            NIM
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            Perusahaan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            Progress
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            Periode
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                    <!-- Row 1 -->
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-600 dark:text-primary-400">JD</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">John Doe</div>
                                    <div class="text-sm text-neutral-500 dark:text-neutral-400">john.doe@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                            2021001234
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-900 dark:text-white">PT. Tech Indonesia</div>
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">Software Development</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900/20 dark:text-success-200">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                                <span class="ml-2 text-sm text-neutral-600 dark:text-neutral-400">75%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                            2024 Semester 1
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <x-button variant="ghost" size="sm" icon="eye">
                                    <span class="sr-only">View</span>
                                </x-button>
                                <x-button variant="ghost" size="sm" icon="pencil">
                                    <span class="sr-only">Edit</span>
                                </x-button>
                                <x-button variant="ghost" size="sm" icon="trash">
                                    <span class="sr-only">Delete</span>
                                </x-button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-warning-100 dark:bg-warning-900/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-warning-600 dark:text-warning-400">JS</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">Jane Smith</div>
                                    <div class="text-sm text-neutral-500 dark:text-neutral-400">jane.smith@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                            2021001235
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-900 dark:text-white">PT. Digital Solutions</div>
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">UI/UX Design</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-warning-100 text-warning-800 dark:bg-warning-900/20 dark:text-warning-200">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                    <div class="bg-warning-500 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                                <span class="ml-2 text-sm text-neutral-600 dark:text-neutral-400">45%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                            2024 Semester 1
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <x-button variant="ghost" size="sm" icon="eye">
                                    <span class="sr-only">View</span>
                                </x-button>
                                <x-button variant="ghost" size="sm" icon="pencil">
                                    <span class="sr-only">Edit</span>
                                </x-button>
                                <x-button variant="ghost" size="sm" icon="trash">
                                    <span class="sr-only">Delete</span>
                                </x-button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-success-100 dark:bg-success-900/20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-success-600 dark:text-success-400">AB</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-white">Alice Brown</div>
                                    <div class="text-sm text-neutral-500 dark:text-neutral-400">alice.brown@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-white">
                            2021001236
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-900 dark:text-white">PT. Data Analytics</div>
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">Data Science</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-neutral-100 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200">
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                    <div class="bg-success-600 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="ml-2 text-sm text-neutral-600 dark:text-neutral-400">100%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                            2023 Semester 2
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <x-button variant="ghost" size="sm" icon="eye">
                                    <span class="sr-only">View</span>
                                </x-button>
                                <x-button variant="ghost" size="sm" icon="pencil">
                                    <span class="sr-only">Edit</span>
                                </x-button>
                                <x-button variant="ghost" size="sm" icon="trash">
                                    <span class="sr-only">Delete</span>
                                </x-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-slot name="footer">
            <div class="flex items-center justify-between">
                <div class="text-sm text-neutral-700 dark:text-neutral-300">
                    Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">3</span> dari <span class="font-medium">12</span> hasil
                </div>
                
                <div class="flex items-center space-x-2">
                    <x-button variant="outline" size="sm" disabled>
                        Previous
                    </x-button>
                    <x-button variant="primary" size="sm">
                        1
                    </x-button>
                    <x-button variant="outline" size="sm">
                        2
                    </x-button>
                    <x-button variant="outline" size="sm">
                        3
                    </x-button>
                    <x-button variant="outline" size="sm">
                        Next
                    </x-button>
                </div>
            </div>
        </x-slot>
    </x-card>
</div>
@endsection
