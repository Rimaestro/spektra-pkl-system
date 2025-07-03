@extends('layouts.app')

@section('title', 'Monitoring PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Monitoring PKL</h1>
            <p class="mt-1 text-sm text-gray-500">Pantau dan kelola progres PKL siswa</p>
        </div>
    </div>

    <!-- Filter and Search -->
    <x-card variant="default" padding="md">
        <form action="{{ route('koordinator.monitoring') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Siswa/Perusahaan</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Nama siswa atau perusahaan">
                    </div>
                </div>
                
                <div>
                    <label for="progress" class="block text-sm font-medium text-gray-700">Progress</label>
                    <select name="progress" id="progress" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Semua Progress</option>
                        <option value="0-25" {{ request('progress') === '0-25' ? 'selected' : '' }}>0-25%</option>
                        <option value="25-50" {{ request('progress') === '25-50' ? 'selected' : '' }}>25-50%</option>
                        <option value="50-75" {{ request('progress') === '50-75' ? 'selected' : '' }}>50-75%</option>
                        <option value="75-100" {{ request('progress') === '75-100' ? 'selected' : '' }}>75-100%</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <x-icon name="magnifying-glass" class="mr-2 -ml-1 h-5 w-5" />
                        Filter
                    </button>
                    <a href="{{ route('koordinator.monitoring') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <x-icon name="x-mark" class="mr-2 -ml-1 h-5 w-5" />
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </x-card>

    <!-- PKL Monitoring Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($pkls as $pkl)
        <x-card variant="default" padding="md" class="overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full" src="{{ $pkl->user->avatar_url }}" alt="{{ $pkl->user->name }}">
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $pkl->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $pkl->user->nis ?? 'NIS tidak tersedia' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-sm font-medium text-gray-900">{{ $pkl->company->name }}</p>
                        <p class="text-xs text-gray-500">{{ $pkl->position }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <div class="flex items-center text-xs text-gray-500 mb-1">
                            <span>Progress PKL:</span>
                            <span class="ml-auto">{{ number_format($pkl->getProgressPercentage(), 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $pkl->getProgressPercentage() }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-500">Tanggal Mulai:</span>
                            <span class="text-gray-900">{{ $pkl->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Tanggal Selesai:</span>
                            <span class="text-gray-900">{{ $pkl->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Durasi:</span>
                            <span class="text-gray-900">{{ $pkl->getDurationInDays() }} hari</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Sisa Waktu:</span>
                            <span class="text-gray-900">{{ $pkl->getRemainingDays() }} hari</span>
                        </div>
                    </div>
                    
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Dosen Pembimbing</p>
                            <p class="text-sm text-gray-900">{{ $pkl->supervisor->name ?? 'Belum ditentukan' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pembimbing Lapangan</p>
                            <p class="text-sm text-gray-900">{{ $pkl->fieldSupervisor->name ?? 'Belum ditentukan' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-between items-center text-sm">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $pkl->reports->count() }} Laporan
                            </span>
                        </div>
                        <a href="{{ route('koordinator.monitoring.show', $pkl->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Detail Monitoring
                        </a>
                    </div>
                </div>
            </div>
        </x-card>
        @empty
        <div class="lg:col-span-2">
            <x-card variant="default" padding="md" class="text-center py-8">
                <div class="flex flex-col items-center">
                    <x-icon name="information-circle" class="h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada data PKL aktif</h3>
                    <p class="mt-1 text-sm text-gray-500">Saat ini tidak ada siswa yang sedang melaksanakan PKL.</p>
                </div>
            </x-card>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $pkls->links() }}
    </div>
</div>
@endsection 