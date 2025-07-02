@extends('layouts.auth')

@section('title', 'Reset Password')
@section('subtitle', 'Masukkan password baru untuk akun Anda')

@section('content')
<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf

    <!-- Hidden Token -->
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email -->
    <x-input 
        type="email" 
        name="email" 
        label="Email" 
        placeholder="Masukkan email Anda"
        icon="user"
        required 
        :error="$errors->first('email')"
        value="{{ old('email', $email) }}"
        readonly
    />

    <!-- Password -->
    <x-input 
        type="password" 
        name="password" 
        label="Password Baru" 
        placeholder="Masukkan password baru"
        icon="lock"
        required 
        :error="$errors->first('password')"
    />

    <!-- Confirm Password -->
    <x-input 
        type="password" 
        name="password_confirmation" 
        label="Konfirmasi Password" 
        placeholder="Konfirmasi password baru"
        icon="lock"
        required 
        :error="$errors->first('password_confirmation')"
    />

    <!-- Submit Button -->
    <x-button type="submit" variant="primary" size="lg" class="w-full">
        Reset Password
    </x-button>

    <!-- Back to Login -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
            Kembali ke halaman login
        </a>
    </div>
</form>
@endsection
