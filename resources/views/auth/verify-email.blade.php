@extends('layouts.auth')

@section('title', 'Verifikasi Email')
@section('subtitle', 'Silakan verifikasi email Anda untuk melanjutkan')

@section('content')
@if (session('message'))
    <x-alert type="success" :message="session('message')" />
@endif

<div class="text-center space-y-6">
    <div class="mx-auto w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
        <x-icon name="mail" class="w-8 h-8 text-primary-600" />
    </div>
    
    <div>
        <p class="text-neutral-600 dark:text-neutral-400">
            Kami telah mengirimkan link verifikasi ke email Anda. 
            Silakan cek email dan klik link untuk memverifikasi akun.
        </p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <x-button type="submit" variant="outline" size="lg">
            Kirim Ulang Email Verifikasi
        </x-button>
    </form>

    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm font-medium text-neutral-600 hover:text-neutral-500 dark:text-neutral-400 dark:hover:text-neutral-300">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
