@extends('layouts.app')

@section('title', 'Laporan Akhir PKL')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Laporan Akhir PKL</h2>
            <a href="{{ route('laporan.akhir.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                Buat Laporan Akhir
            </a>
        </div>

        @if(isset($finalReport) && $finalReport)
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Laporan Akhir PKL</h3>
                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                        {{ $finalReport->status === 'approved' ? 'bg-green-100 text-green-800' : 
                           ($finalReport->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($finalReport->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $finalReport->status === 'approved' ? 'Disetujui' : 
                           ($finalReport->status === 'pending' ? 'Menunggu' : 
                            ($finalReport->status === 'rejected' ? 'Ditolak' : $finalReport->status)) }}
                    </span>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Judul:</p>
                        <p class="text-base text-gray-900">{{ $finalReport->title }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Pengumpulan:</p>
                        <p class="text-base text-gray-900">{{ $finalReport->created_at->format('d M Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">File:</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <x-icon name="document-text" size="sm" class="text-gray-500" />
                            <a href="#" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $finalReport->filename }}</a>
                        </div>
                    </div>

                    @if($finalReport->status === 'rejected' && $finalReport->feedback)
                        <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-md">
                            <p class="text-sm font-medium text-red-800">Feedback Pembimbing:</p>
                            <p class="text-sm text-red-700 mt-1">{{ $finalReport->feedback }}</p>
                        </div>
                    @endif

                    <div class="flex justify-end mt-4">
                        <a href="{{ route('laporan.akhir.show', $finalReport->id) }}" class="px-4 py-2 text-sm font-medium bg-gray-100 border border-transparent rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <x-icon name="document-text" size="md" class="text-gray-400" />
                </div>
                <h3 class="text-gray-900 font-medium">Belum Ada Laporan Akhir</h3>
                <p class="text-gray-500 text-sm mt-1">Laporan akhir PKL belum dibuat</p>
                <div class="mt-4">
                    <a href="{{ route('laporan.akhir.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Buat Laporan Akhir
                    </a>
                </div>
            </div>
        @endif
        
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Panduan Penyusunan Laporan Akhir</h3>
            
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-icon name="information-circle" size="md" class="text-blue-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Laporan akhir merupakan dokumen penting yang berisi rangkuman seluruh kegiatan PKL yang telah dilaksanakan. Pastikan laporan memenuhi ketentuan format dan struktur yang ditetapkan.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <h4 class="text-base font-medium text-gray-900">Struktur Laporan</h4>
                    <ul class="mt-2 list-disc list-inside text-sm text-gray-700 space-y-1">
                        <li>Cover & Lembar Pengesahan</li>
                        <li>Kata Pengantar</li>
                        <li>Daftar Isi</li>
                        <li>BAB 1: Pendahuluan</li>
                        <li>BAB 2: Profil Perusahaan/Instansi</li>
                        <li>BAB 3: Kegiatan PKL</li>
                        <li>BAB 4: Hasil & Pembahasan</li>
                        <li>BAB 5: Penutup (Kesimpulan & Saran)</li>
                        <li>Daftar Pustaka</li>
                        <li>Lampiran</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-base font-medium text-gray-900">Format Penulisan</h4>
                    <ul class="mt-2 list-disc list-inside text-sm text-gray-700 space-y-1">
                        <li>Ukuran kertas A4</li>
                        <li>Margin: kiri 4cm, kanan 3cm, atas 3cm, bawah 3cm</li>
                        <li>Jenis huruf Times New Roman ukuran 12 pt</li>
                        <li>Spasi 1.5</li>
                        <li>Format file: PDF (max 10MB)</li>
                    </ul>
                </div>
                
                <div>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        <x-icon name="arrow-down-tray" size="sm" class="mr-1" />
                        <span>Download Template Laporan Akhir</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 