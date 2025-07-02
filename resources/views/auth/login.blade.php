@extends('layouts.auth')

@section('title', 'Masuk ke Akun')
@section('subtitle', 'Silakan masuk untuk mengakses dashboard PKL Anda')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
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

    <!-- Password -->
    <x-input 
        type="password" 
        name="password" 
        label="Password" 
        placeholder="Masukkan password Anda"
        icon="lock"
        required 
        :error="$errors->first('password')"
    />

    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input 
                id="remember-me" 
                name="remember" 
                type="checkbox" 
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded"
            >
            <label for="remember-me" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                Ingat saya
            </label>
        </div>

        <div class="text-sm">
            <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                Lupa password?
            </a>
        </div>
    </div>

    <!-- Submit Button -->
    <x-button type="submit" variant="primary" size="lg" class="w-full">
        Masuk
    </x-button>
</form>
@endsection

@section('footer-links')
<p class="text-sm text-neutral-600 dark:text-neutral-400">
    Belum punya akun? 
    <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
        Daftar sekarang
    </a>
</p>
@endsection
