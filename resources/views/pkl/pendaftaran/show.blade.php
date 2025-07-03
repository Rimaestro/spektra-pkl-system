@extends('layouts.app')

@section('title', 'Detail Pendaftaran PKL')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Detail Pendaftaran PKL</h2>
            <div class="flex space-x-2">
                <a href="{{ route('pkl.pendaftaran') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Kembali
                </a>
                @if(isset($pkl) && $pkl->status === 'pending' && Auth::user()->id === $pkl->user_id)
                    <form action="{{ route('pkl.pendaftaran.cancel', $pkl->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                            Batalkan Pendaftaran
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(isset($pkl))
            <!-- Status Badge -->
            <div class="mb-6">
                <span class="px-3 py-1 text-sm font-medium rounded-full 
                    {{ $pkl->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                       ($pkl->status === 'approved' ? 'bg-blue-100 text-blue-800' : 
                        ($pkl->status === 'ongoing' ? 'bg-green-100 text-green-800' : 
                         ($pkl->status === 'completed' ? 'bg-gray-100 text-gray-800' : 
                          'bg-red-100 text-red-800'))) }}">
                    {{ $pkl->getStatusLabel() }}
                </span>
            </div>

            <!-- Data PKL -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Informasi Siswa -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Informasi Siswa</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Nama</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">NIS</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->user->nis }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Email</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Telepon</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->user->phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Perusahaan -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Informasi Perusahaan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Nama Perusahaan</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->company->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Alamat</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->company->address }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Kontak</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->company->contact_person }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Email</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->company->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail PKL -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-base font-medium text-gray-900 mb-4">Detail PKL</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Tanggal Mulai</span>
                            <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pkl->start_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Tanggal Selesai</span>
                            <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pkl->end_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Durasi</span>
                            <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pkl->start_date)->diffInDays($pkl->end_date) + 1 }} Hari</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Posisi/Bidang</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->position }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Tanggal Daftar</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Terakhir Diperbarui</span>
                            <span class="text-sm font-medium text-gray-900">{{ $pkl->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-base font-medium text-gray-900 mb-4">Dokumen Pendukung</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <x-icon name="document-text" size="sm" class="text-gray-500 mr-2" />
                            <span class="text-sm text-gray-900">Surat Motivasi</span>
                        </div>
                        @if($pkl->motivation_letter_path)
                            <a href="{{ route('pkl.pendaftaran.download', ['pkl' => $pkl->id, 'document' => 'motivation_letter']) }}" class="text-sm text-blue-600 hover:text-blue-800">Unduh</a>
                        @else
                            <span class="text-xs text-gray-500">Tidak ada dokumen</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <x-icon name="document-check" size="sm" class="text-gray-500 mr-2" />
                            <span class="text-sm text-gray-900">Surat Persetujuan Orang Tua</span>
                        </div>
                        @if($pkl->parent_approval_path)
                            <a href="{{ route('pkl.pendaftaran.download', ['pkl' => $pkl->id, 'document' => 'parent_approval']) }}" class="text-sm text-blue-600 hover:text-blue-800">Unduh</a>
                        @else
                            <span class="text-xs text-gray-500">Tidak ada dokumen</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <x-icon name="document" size="sm" class="text-gray-500 mr-2" />
                            <span class="text-sm text-gray-900">Dokumen Tambahan</span>
                        </div>
                        @if($pkl->additional_document_path)
                            <a href="{{ route('pkl.pendaftaran.download', ['pkl' => $pkl->id, 'document' => 'additional_document']) }}" class="text-sm text-blue-600 hover:text-blue-800">Unduh</a>
                        @else
                            <span class="text-xs text-gray-500">Tidak ada dokumen</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Riwayat Status -->
            @if(isset($statusHistory) && count($statusHistory) > 0)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Riwayat Status</h3>
                    <div class="space-y-4">
                        @foreach($statusHistory as $history)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <x-icon name="clock" size="sm" class="text-gray-600" />
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $history->status_label }}</p>
                                    <p class="text-xs text-gray-500">{{ $history->created_at->format('d M Y H:i') }}</p>
                                    @if($history->notes)
                                        <p class="text-sm text-gray-700 mt-1">{{ $history->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                    <x-icon name="exclamation-circle" size="md" class="text-gray-400" />
                </div>
                <h3 class="text-gray-900 font-medium">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 text-sm mt-1">Data pendaftaran PKL tidak ditemukan atau telah dihapus</p>
            </div>
        @endif
    </div>
</div>
@endsection 