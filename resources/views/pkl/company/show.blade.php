@extends('layouts.app')

@section('title', 'Detail Perusahaan')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $company->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Detail perusahaan tempat PKL</p>
            </div>
            <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <x-icon name="arrow-left" size="sm" class="mr-1" />
                <span>Kembali</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Company Info -->
            <div class="md:col-span-2">
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Perusahaan</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Alamat:</p>
                            <p class="text-base text-gray-900">{{ $company->address }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Telepon:</p>
                            <p class="text-base text-gray-900">{{ $company->phone ?? 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email:</p>
                            <p class="text-base text-gray-900">{{ $company->email ?? 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Website:</p>
                            @if($company->website)
                                <a href="{{ $company->website }}" target="_blank" class="text-base text-blue-600 hover:text-blue-800 hover:underline">{{ $company->website }}</a>
                            @else
                                <p class="text-base text-gray-900">Tidak ada</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Bidang Usaha:</p>
                            <p class="text-base text-gray-900">{{ $company->industry ?? 'Tidak ada' }}</p>
                        </div>
                        @if($company->description)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Deskripsi:</p>
                                <p class="text-base text-gray-900">{{ $company->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if(isset($company->contact_person) && $company->contact_person)
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kontak Person</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama:</p>
                                <p class="text-base text-gray-900">{{ $company->contact_person }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Jabatan:</p>
                                <p class="text-base text-gray-900">{{ $company->contact_position ?? 'Tidak ada' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Telepon:</p>
                                <p class="text-base text-gray-900">{{ $company->contact_phone ?? 'Tidak ada' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email:</p>
                                <p class="text-base text-gray-900">{{ $company->contact_email ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <div>
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Status PKL</h3>
                    
                    @if(isset($pkl) && $pkl)
                        <div class="space-y-3">
                            <div>
                                <span class="px-3 py-1 text-sm font-medium rounded-full 
                                    {{ $pkl->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                       ($pkl->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($pkl->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                         ($pkl->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                    {{ $pkl->getStatusLabel() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Periode:</p>
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($pkl->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pkl->end_date)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pembimbing:</p>
                                <p class="text-sm text-gray-900">{{ $pkl->fieldSupervisor->name ?? 'Belum ditentukan' }}</p>
                            </div>
                            
                            <div class="pt-2">
                                <a href="{{ route('pkl.pendaftaran.show', $pkl->id) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Detail PKL</a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">Anda belum terdaftar PKL di perusahaan ini</p>
                            
                            <div class="mt-3">
                                <a href="{{ route('pkl.pendaftaran.create', ['company_id' => $company->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Daftar PKL
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Map/Location -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Lokasi</h3>
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg">
                        <div class="w-full h-full bg-gray-300 flex items-center justify-center rounded-lg">
                            <span class="text-sm text-gray-500">Peta tidak tersedia</span>
                        </div>
                    </div>
                </div>
                
                <!-- Rating -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Rating & Review</h3>
                    <div class="flex items-center mb-3">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <x-icon name="{{ $i <= ($company->rating ?? 0) ? 'star' : 'star-outline' }}" size="sm" class="text-yellow-400 mr-1" />
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2">{{ $company->rating ?? 0 }} / 5</span>
                    </div>
                    <p class="text-sm text-gray-500">Berdasarkan {{ $company->reviews_count ?? 0 }} ulasan</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 