@extends('layouts.app')

@section('title', 'Buat Laporan Harian')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Buat Laporan Harian</h2>
            <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah ini untuk menambahkan laporan kegiatan harian PKL</p>
        </div>

        <form action="{{ route('laporan.harian.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan</label>
                    <input type="text" name="title" id="title" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Mis. Pembuatan Database Sistem">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea name="description" id="description" rows="5" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Jelaskan secara detail kegiatan yang dilakukan hari ini..."></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tasks_completed" class="block text-sm font-medium text-gray-700 mb-1">Tugas yang Diselesaikan</label>
                    <textarea name="tasks_completed" id="tasks_completed" rows="3" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Daftar tugas yang berhasil diselesaikan..."></textarea>
                    @error('tasks_completed')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="challenges" class="block text-sm font-medium text-gray-700 mb-1">Kendala yang Dihadapi <span class="text-gray-400">(opsional)</span></label>
                    <textarea name="challenges" id="challenges" rows="3" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Kendala atau tantangan yang dihadapi selama kegiatan..."></textarea>
                </div>

                <div>
                    <label for="solutions" class="block text-sm font-medium text-gray-700 mb-1">Solusi <span class="text-gray-400">(opsional)</span></label>
                    <textarea name="solutions" id="solutions" rows="3" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Solusi yang diterapkan untuk mengatasi kendala..."></textarea>
                </div>

                <div>
                    <label for="next_plan" class="block text-sm font-medium text-gray-700 mb-1">Rencana Selanjutnya <span class="text-gray-400">(opsional)</span></label>
                    <textarea name="next_plan" id="next_plan" rows="3" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Rencana kegiatan untuk hari berikutnya..."></textarea>
                </div>

                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Foto Kegiatan <span class="text-gray-400">(opsional)</span></label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                    <p class="text-gray-500 text-xs mt-1">Unggah foto dokumentasi kegiatan (maksimal 5 foto, maks. ukuran 2MB per foto)</p>
                </div>

                <div class="flex justify-between pt-4">
                    <a href="{{ route('laporan.harian.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Simpan Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 