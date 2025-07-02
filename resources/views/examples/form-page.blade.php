@extends('layouts.app')

@section('title', 'Form Example')

@php
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('dashboard') ?? '#'],
    ['name' => 'Examples', 'url' => '#'],
    ['name' => 'Form Page', 'url' => '#'],
];
@endphp

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-soft border border-neutral-200 dark:border-neutral-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Form Example</h1>
                <p class="text-neutral-600 dark:text-neutral-400 mt-1">Contoh implementasi form dengan komponen UI yang konsisten</p>
            </div>
            <x-button variant="outline" size="sm" href="#" icon="eye">
                Preview
            </x-button>
        </div>
    </div>

    <!-- Form Card -->
    <x-card variant="default" padding="none">
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Informasi Pribadi</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Lengkapi informasi pribadi Anda dengan benar</p>
        </x-slot>

        <form action="#" method="POST" class="space-y-6">
            @csrf
            
            <!-- Personal Information Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input 
                    name="first_name" 
                    label="Nama Depan" 
                    placeholder="Masukkan nama depan"
                    required 
                />
                
                <x-input 
                    name="last_name" 
                    label="Nama Belakang" 
                    placeholder="Masukkan nama belakang"
                    required 
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input 
                    type="email"
                    name="email" 
                    label="Email" 
                    placeholder="contoh@email.com"
                    icon="user"
                    required 
                />
                
                <x-input 
                    type="tel"
                    name="phone" 
                    label="Nomor Telepon" 
                    placeholder="+62 812 3456 7890"
                    help="Format: +62 xxx xxxx xxxx"
                />
            </div>

            <!-- Address Section -->
            <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Alamat</h3>
                
                <div class="space-y-6">
                    <x-textarea 
                        name="address" 
                        label="Alamat Lengkap" 
                        placeholder="Masukkan alamat lengkap"
                        rows="3"
                        required 
                    />
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-select 
                            name="province" 
                            label="Provinsi" 
                            placeholder="Pilih provinsi"
                            required 
                            :options="[
                                'jawa-barat' => 'Jawa Barat',
                                'jawa-tengah' => 'Jawa Tengah',
                                'jawa-timur' => 'Jawa Timur',
                                'dki-jakarta' => 'DKI Jakarta',
                            ]"
                        />
                        
                        <x-input 
                            name="city" 
                            label="Kota/Kabupaten" 
                            placeholder="Masukkan kota"
                            required 
                        />
                        
                        <x-input 
                            name="postal_code" 
                            label="Kode Pos" 
                            placeholder="12345"
                            maxlength="5"
                        />
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Informasi Tambahan</h3>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input 
                            type="date"
                            name="birth_date" 
                            label="Tanggal Lahir" 
                            required 
                        />
                        
                        <x-select 
                            name="gender" 
                            label="Jenis Kelamin" 
                            placeholder="Pilih jenis kelamin"
                            required 
                            :options="[
                                'male' => 'Laki-laki',
                                'female' => 'Perempuan',
                            ]"
                        />
                    </div>
                    
                    <x-textarea 
                        name="bio" 
                        label="Bio" 
                        placeholder="Ceritakan sedikit tentang diri Anda..."
                        rows="4"
                        help="Maksimal 500 karakter"
                    />
                </div>
            </div>

            <!-- File Upload Section -->
            <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Dokumen</h3>
                
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-neutral-300 dark:border-neutral-600 rounded-lg p-6 text-center hover:border-primary-400 transition-colors duration-200">
                        <x-icon name="plus" size="lg" class="mx-auto text-neutral-400 mb-2" />
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="font-medium text-primary-600 dark:text-primary-400 cursor-pointer">Klik untuk upload</span> 
                            atau drag and drop
                        </p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-500 mt-1">PNG, JPG, PDF hingga 10MB</p>
                        <input type="file" class="hidden" accept=".png,.jpg,.jpeg,.pdf" />
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Preferensi</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input 
                            id="newsletter" 
                            name="newsletter" 
                            type="checkbox" 
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded"
                        >
                        <label for="newsletter" class="ml-3 text-sm text-neutral-700 dark:text-neutral-300">
                            Saya ingin menerima newsletter dan update terbaru
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input 
                            id="notifications" 
                            name="notifications" 
                            type="checkbox" 
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded"
                        >
                        <label for="notifications" class="ml-3 text-sm text-neutral-700 dark:text-neutral-300">
                            Kirim notifikasi email untuk aktivitas penting
                        </label>
                    </div>
                </div>
            </div>
        </form>

        <x-slot name="footer">
            <div class="flex items-center justify-between">
                <x-button variant="ghost" size="md">
                    Batal
                </x-button>
                
                <div class="flex space-x-3">
                    <x-button variant="outline" size="md">
                        Simpan Draft
                    </x-button>
                    <x-button variant="primary" size="md" type="submit">
                        Simpan Perubahan
                    </x-button>
                </div>
            </div>
        </x-slot>
    </x-card>

    <!-- Help Section -->
    <x-alert type="info">
        <strong>Tips:</strong> Pastikan semua informasi yang Anda masukkan sudah benar sebelum menyimpan. 
        Anda dapat mengedit informasi ini kapan saja melalui halaman profil.
    </x-alert>
</div>
@endsection
