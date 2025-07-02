@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('subtitle', 'Masukkan email Anda untuk menerima link reset password')

@section('content')
@if (session('status'))
    <x-alert type="success" :message="session('status')" />
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf

    <!-- Email -->
    <x-input 
        type="email" 
        name="email" 
        label="Email" 
        placeholder="Masukkan email Anda"
        icon="user"
        required 
        :error="$errors->first('email')"
        value="{{ old('email') }}"
    />

    <!-- Submit Button -->
    <x-button type="submit" variant="primary" size="lg" class="w-full">
        Kirim Link Reset Password
    </x-button>

    <!-- Back to Login -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
            Kembali ke halaman login
        </a>
    </div>
</form>
@endsection
