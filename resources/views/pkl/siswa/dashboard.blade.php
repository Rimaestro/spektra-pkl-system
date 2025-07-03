@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <!-- Status PKL -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Status PKL</h3>
        <div class="flex items-center">
            @if(isset($pkl) && $pkl)
                <span class="px-3 py-1 text-sm font-medium rounded-full 
                    {{ $pkl->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                       ($pkl->status === 'completed' ? 'bg-green-100 text-green-800' : 
                        ($pkl->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                         ($pkl->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                    {{ $pkl->getStatusLabel() }}
                </span>
            @else
                <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">Belum Mendaftar</span>
                <a href="{{ route('pkl.pendaftaran.create') }}" class="ml-3 text-sm text-blue-600 hover:text-blue-800">Daftar Sekarang</a>
            @endif
        </div>
    </div>

    <!-- Periode PKL -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Periode PKL</h3>
        <div class="text-sm text-gray-700">
            @if(isset($pkl) && $pkl && $pkl->start_date && $pkl->end_date)
                <div class="flex items-center">
                    <span class="text-sm">Mulai:</span>
                    <span class="ml-2 font-medium">{{ \Carbon\Carbon::parse($pkl->start_date)->format('d M Y') }}</span>
                </div>
                <div class="flex items-center mt-1">
                    <span class="text-sm">Selesai:</span>
                    <span class="ml-2 font-medium">{{ \Carbon\Carbon::parse($pkl->end_date)->format('d M Y') }}</span>
                </div>
                <div class="flex items-center mt-1">
                    <span class="text-sm">Durasi:</span>
                    <span class="ml-2 font-medium">{{ \Carbon\Carbon::parse($pkl->start_date)->diffInDays($pkl->end_date) + 1 }} Hari</span>
                </div>
            @else
                <p class="text-gray-500">Belum ada periode PKL yang terdaftar.</p>
            @endif
        </div>
    </div>

    <!-- Lokasi PKL -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Lokasi PKL</h3>
        @if(isset($pkl) && $pkl && $pkl->company)
            <div class="text-sm text-gray-700">
                <div class="font-semibold">{{ $pkl->company->name }}</div>
                <div class="text-gray-500 mt-1">{{ $pkl->company->address }}</div>
                <div class="mt-2">
                    <a href="{{ route('pkl.company', $pkl->company_id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Detail</a>
                </div>
            </div>
        @else
            <p class="text-sm text-gray-500">Belum ada lokasi PKL yang terdaftar.</p>
        @endif
    </div>
</div>

<!-- Progress and Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Progress -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Progress PKL</h3>
            @if(isset($pkl) && $pkl && $pkl->start_date && $pkl->end_date)
                @php
                    $startDate = \Carbon\Carbon::parse($pkl->start_date);
                    $endDate = \Carbon\Carbon::parse($pkl->end_date);
                    $today = \Carbon\Carbon::today();
                    $totalDays = $startDate->diffInDays($endDate) + 1;
                    $daysElapsed = $startDate->diffInDays($today) + 1;
                    $progress = $today >= $startDate ? min(100, max(0, round(($daysElapsed / $totalDays) * 100))) : 0;
                @endphp
                <span class="text-sm font-medium text-gray-700">{{ $progress }}% Selesai</span>
            @endif
        </div>

        @if(isset($pkl) && $pkl && $pkl->start_date && $pkl->end_date)
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
            </div>

            <div class="flex justify-between text-xs text-gray-500">
                <span>{{ \Carbon\Carbon::parse($pkl->start_date)->format('d M Y') }}</span>
                <span>{{ \Carbon\Carbon::parse($pkl->end_date)->format('d M Y') }}</span>
            </div>

            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Aktivitas Terakhir</h4>
                @if(isset($recentActivities) && count($recentActivities) > 0)
                    <ul class="space-y-3">
                        @foreach($recentActivities as $activity)
                            <li class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <x-icon name="{{ $activity->icon ?? 'document-text' }}" size="sm" class="text-blue-600" />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">Belum ada aktivitas yang tercatat.</p>
                @endif
            </div>
        @else
            <div class="text-center py-8">
                <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                    <x-icon name="exclamation-circle" size="md" class="text-gray-400" />
                </div>
                <h3 class="text-gray-900 font-medium">Belum Ada Data PKL</h3>
                <p class="text-gray-500 text-sm mt-1">Daftar PKL terlebih dahulu untuk melihat progress</p>
                <div class="mt-4">
                    <a href="{{ route('pkl.pendaftaran.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Daftar PKL
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="space-y-3">
            <a href="{{ route('laporan.harian.create') }}" class="flex items-center p-3 rounded-md border border-gray-200 hover:bg-gray-50">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                    <x-icon name="document-plus" size="sm" class="text-blue-600" />
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Buat Laporan Harian</span>
                    <p class="text-xs text-gray-500">Catat kegiatan hari ini</p>
                </div>
            </a>

            <a href="{{ route('laporan.harian.index') }}" class="flex items-center p-3 rounded-md border border-gray-200 hover:bg-gray-50">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                    <x-icon name="document-text" size="sm" class="text-indigo-600" />
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Laporan Harian</span>
                    <p class="text-xs text-gray-500">Lihat riwayat laporan harian</p>
                </div>
            </a>

            <a href="{{ route('laporan.akhir.index') }}" class="flex items-center p-3 rounded-md border border-gray-200 hover:bg-gray-50">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                    <x-icon name="document-check" size="sm" class="text-green-600" />
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Laporan Akhir</span>
                    <p class="text-xs text-gray-500">Laporan lengkap kegiatan PKL</p>
                </div>
            </a>

            <a href="{{ route('profile.show') }}" class="flex items-center p-3 rounded-md border border-gray-200 hover:bg-gray-50">
                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                    <x-icon name="user" size="sm" class="text-yellow-600" />
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-900">Profil Saya</span>
                    <p class="text-xs text-gray-500">Lihat dan ubah profil</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Upcoming Activities and Notifications -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Upcoming Activities -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal Mendatang</h3>
        @if(isset($upcomingActivities) && count($upcomingActivities) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($upcomingActivities as $activity)
                    <li class="py-3">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->description }}</p>
                            </div>
                            <div class="text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-6">
                <p class="text-gray-500 text-sm">Tidak ada jadwal mendatang.</p>
            </div>
        @endif
    </div>

    <!-- Notifications -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Notifikasi</h3>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
        </div>
        
        @if(isset($notifications) && count($notifications) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <li class="py-3">
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-{{ $notification->read ? 'gray' : 'blue' }}-100 flex items-center justify-center">
                                    <x-icon name="{{ $notification->icon ?? 'bell' }}" size="sm" class="text-{{ $notification->read ? 'gray' : 'blue' }}-600" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-6">
                <p class="text-gray-500 text-sm">Tidak ada notifikasi baru.</p>
            </div>
        @endif
    </div>
</div>
@endsection 