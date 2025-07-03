@extends('layouts.app')

@section('title', 'Pendaftaran PKL')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Pendaftaran PKL</h2>
            <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah ini untuk mendaftar program Praktik Kerja Lapangan</p>
        </div>

        <form action="{{ route('pkl.pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Data Siswa -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Data Siswa</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" readonly>
                        </div>
                        
                        <div>
                            <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                            <input type="text" name="nis" id="nis" value="{{ Auth::user()->nis }}" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" readonly>
                        </div>
                    </div>
                </div>
                
                <!-- Data PKL -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Data PKL</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Perusahaan/Instansi</label>
                            <select name="company_id" id="company_id" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach(\App\Models\Company::where('status', 'active')->get() as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Jika perusahaan tidak ada dalam daftar, silahkan hubungi admin</p>
                            @error('company_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                                @error('end_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Posisi/Bidang PKL</label>
                            <input type="text" name="position" id="position" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Mis. Web Developer, Teknisi Jaringan, dll">
                            @error('position')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Dokumen Pendukung -->
                <div>
                    <h3 class="text-base font-medium text-gray-900 mb-4">Dokumen Pendukung</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="motivation_letter" class="block text-sm font-medium text-gray-700 mb-1">Surat Motivasi</label>
                            <input type="file" name="motivation_letter" id="motivation_letter" accept=".pdf,.doc,.docx" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, atau DOCX (Maks. 2MB)</p>
                            @error('motivation_letter')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="parent_approval" class="block text-sm font-medium text-gray-700 mb-1">Surat Persetujuan Orang Tua</label>
                            <input type="file" name="parent_approval" id="parent_approval" accept=".pdf,.jpg,.jpeg,.png" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, atau PNG (Maks. 2MB)</p>
                            @error('parent_approval')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="additional_document" class="block text-sm font-medium text-gray-700 mb-1">Dokumen Tambahan <span class="text-gray-400">(opsional)</span></label>
                            <input type="file" name="additional_document" id="additional_document" accept=".pdf,.doc,.docx,.zip,.rar" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, ZIP, atau RAR (Maks. 5MB)</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between pt-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 