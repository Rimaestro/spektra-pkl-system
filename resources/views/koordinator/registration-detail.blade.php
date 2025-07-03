@extends('layouts.app')

@section('title', 'Detail Pendaftaran PKL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pendaftaran PKL</h1>
            <p class="mt-1 text-sm text-gray-500">Detail dan verifikasi pendaftaran PKL</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('koordinator.registrations') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <x-icon name="arrow-left" class="mr-2 -ml-1 h-5 w-5" />
                Kembali
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex justify-between items-center">
        <div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium 
                @if($pkl->status === 'pending') bg-amber-100 text-amber-800
                @elseif($pkl->status === 'approved') bg-blue-100 text-blue-800
                @elseif($pkl->status === 'ongoing') bg-green-100 text-green-800
                @elseif($pkl->status === 'completed') bg-indigo-100 text-indigo-800
                @elseif($pkl->status === 'rejected') bg-red-100 text-red-800
                @elseif($pkl->status === 'cancelled') bg-gray-100 text-gray-800
                @endif">
                <x-icon name="{{ $pkl->status === 'pending' ? 'clock' : 
                                 ($pkl->status === 'approved' ? 'check' : 
                                 ($pkl->status === 'ongoing' ? 'academic-cap' : 
                                 ($pkl->status === 'completed' ? 'check-badge' : 
                                 ($pkl->status === 'rejected' ? 'x-mark' : 
                                 ($pkl->status === 'cancelled' ? 'x-circle' : 'question-mark-circle'))))) }}" 
                        class="mr-1.5 h-4 w-4" />
                {{ $pkl->getStatusLabel() }}
            </span>
            <span class="ml-2 text-sm text-gray-500">Diajukan {{ $pkl->created_at->diffForHumans() }}</span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Student & Company Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Details -->
            <x-card variant="default" padding="md">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Siswa</h3>
                </x-slot>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <img class="h-14 w-14 rounded-full" src="{{ $pkl->user->avatar_url }}" alt="{{ $pkl->user->name }}">
                    </div>
                    <div class="ml-4 space-y-2">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $pkl->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $pkl->user->email }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">NIS</p>
                                <p class="text-sm text-gray-900">{{ $pkl->user->nis ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Nomor Telepon</p>
                                <p class="text-sm text-gray-900">{{ $pkl->user->phone ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Company Details -->
            <x-card variant="default" padding="md">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Perusahaan</h3>
                </x-slot>

                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $pkl->company->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $pkl->position }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Alamat</p>
                            <p class="text-sm text-gray-900">{{ $pkl->company->address ?? 'Tidak tersedia' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kontak</p>
                            <p class="text-sm text-gray-900">{{ $pkl->company->contact_person ?? 'Tidak tersedia' }} ({{ $pkl->company->phone ?? 'Tidak tersedia' }})</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="text-sm text-gray-900">{{ $pkl->company->email ?? 'Tidak tersedia' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Industri</p>
                            <p class="text-sm text-gray-900">{{ $pkl->company->industry ?? 'Tidak tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- PKL Details -->
            <x-card variant="default" padding="md">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Detail PKL</h3>
                </x-slot>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Mulai</p>
                            <p class="text-sm text-gray-900">{{ $pkl->start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Selesai</p>
                            <p class="text-sm text-gray-900">{{ $pkl->end_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Durasi</p>
                            <p class="text-sm text-gray-900">{{ $pkl->getDurationInDays() }} hari</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Posisi/Jabatan</p>
                            <p class="text-sm text-gray-900">{{ $pkl->position }}</p>
                        </div>
                    </div>

                    @if($pkl->description)
                    <div>
                        <p class="text-xs text-gray-500">Deskripsi Kegiatan</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $pkl->description }}</p>
                    </div>
                    @endif

                    <!-- Documents -->
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Dokumen Pendaftaran</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @if($pkl->motivation_letter_path)
                            <a href="{{ route('pkl.pendaftaran.download', ['pkl' => $pkl->id, 'document' => 'motivation_letter']) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <x-icon name="document-text" class="mr-2 -ml-0.5 h-4 w-4" />
                                Surat Motivasi
                            </a>
                            @endif
                            
                            @if($pkl->parent_approval_path)
                            <a href="{{ route('pkl.pendaftaran.download', ['pkl' => $pkl->id, 'document' => 'parent_approval']) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <x-icon name="document-text" class="mr-2 -ml-0.5 h-4 w-4" />
                                Persetujuan Orang Tua
                            </a>
                            @endif
                            
                            @if($pkl->additional_document_path)
                            <a href="{{ route('pkl.pendaftaran.download', ['pkl' => $pkl->id, 'document' => 'additional_document']) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <x-icon name="document-text" class="mr-2 -ml-0.5 h-4 w-4" />
                                Dokumen Tambahan
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Right Column - Action Cards -->
        <div class="space-y-6">
            <!-- Verify Registration Card -->
            @if($pkl->status === 'pending')
            <x-card variant="default" padding="md" class="bg-white border border-gray-200">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Pendaftaran</h3>
                </x-slot>
                
                <!-- Approve Form -->
                <form action="{{ route('koordinator.registration.approve', $pkl->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="supervisor_id" class="block text-sm font-medium text-gray-700">Dosen Pembimbing <span class="text-red-500">*</span></label>
                        <select name="supervisor_id" id="supervisor_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Pilih Dosen Pembimbing</option>
                            @foreach(\App\Models\User::dosen()->active()->get() as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="field_supervisor_id" class="block text-sm font-medium text-gray-700">Pembimbing Lapangan</label>
                        <select name="field_supervisor_id" id="field_supervisor_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Pilih Pembimbing Lapangan</option>
                            @foreach(\App\Models\User::pembimbingLapangan()->active()->get() as $pembimbing)
                                <option value="{{ $pembimbing->id }}">{{ $pembimbing->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <x-icon name="check" class="mr-2 -ml-1 h-5 w-5" />
                        Setujui Pendaftaran
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-4 flex items-center">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <p class="mx-3 text-sm text-gray-500">atau</p>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <!-- Reject Form -->
                <form action="{{ route('koordinator.registration.reject', $pkl->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <x-icon name="x-mark" class="mr-2 -ml-1 h-5 w-5" />
                        Tolak Pendaftaran
                    </button>
                </form>
            </x-card>
            @endif

            <!-- Status Information Card -->
            @if($pkl->status !== 'pending')
            <x-card variant="default" padding="md" class="bg-white border border-gray-200">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Status</h3>
                </x-slot>

                <div class="space-y-4">
                    @if($pkl->status === 'approved' || $pkl->status === 'ongoing' || $pkl->status === 'completed')
                        <div>
                            <p class="text-xs text-gray-500">Dosen Pembimbing</p>
                            <div class="mt-1 flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-8 w-8 rounded-full" src="{{ $pkl->supervisor->avatar_url ?? '' }}" alt="{{ $pkl->supervisor->name ?? 'Dosen Pembimbing' }}">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $pkl->supervisor->name ?? 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                        </div>

                        @if($pkl->field_supervisor)
                        <div>
                            <p class="text-xs text-gray-500">Pembimbing Lapangan</p>
                            <div class="mt-1 flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-8 w-8 rounded-full" src="{{ $pkl->fieldSupervisor->avatar_url ?? '' }}" alt="{{ $pkl->fieldSupervisor->name ?? 'Pembimbing Lapangan' }}">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $pkl->fieldSupervisor->name }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($pkl->approved_by)
                        <div>
                            <p class="text-xs text-gray-500">Disetujui Oleh</p>
                            <p class="text-sm text-gray-900">{{ \App\Models\User::find($pkl->approved_by)->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $pkl->approved_at ? $pkl->approved_at->format('d M Y H:i') : '' }}</p>
                        </div>
                        @endif
                    @endif

                    @if($pkl->status === 'rejected' && $pkl->rejection_reason)
                    <div>
                        <p class="text-xs text-gray-500">Alasan Penolakan</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $pkl->rejection_reason }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500">Ditolak Oleh</p>
                        <p class="text-sm text-gray-900">{{ \App\Models\User::find($pkl->rejected_by)->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $pkl->rejected_at ? $pkl->rejected_at->format('d M Y H:i') : '' }}</p>
                    </div>
                    @endif

                    @if($pkl->status === 'cancelled')
                    <div>
                        <p class="text-xs text-gray-500">Dibatalkan Oleh</p>
                        <p class="text-sm text-gray-900">{{ $pkl->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $pkl->updated_at->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </x-card>
            @endif

            <!-- Quick Actions Card -->
            <x-card variant="default" padding="md" class="bg-white border border-gray-200">
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Menu Cepat</h3>
                </x-slot>

                <div class="space-y-2">
                    <a href="{{ route('pkl.siswa.show', $pkl->user_id) }}" class="block w-full text-left px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Lihat Profil Siswa
                    </a>
                    <a href="{{ route('koordinator.company.show', $pkl->company_id) }}" class="block w-full text-left px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Detail Perusahaan
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection 