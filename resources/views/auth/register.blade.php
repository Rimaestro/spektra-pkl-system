@extends('layouts.auth')

@section('title', 'Daftar Akun SPEKTRA')
@section('subtitle', 'Buat akun baru untuk mengakses dashboard PKL Anda')

@section('content')
    <style>
    .fade-in {
      animation: fadeIn 0.4s ease-in;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(16px); }
      to { opacity: 1; transform: none; }
    }
    .caps-lock-indicator {
      font-size: 0.85em;
      color: #dc2626;
      margin-top: 0.25rem;
      display: none;
    }
    .caps-lock-indicator.active {
      display: block;
    }
    </style>
    <form method="POST" action="{{ route('register') }}" class="space-y-6 fade-in" id="register-form">
    @csrf
        <div>
            <x-input
                id="name"
                name="name"
                type="text"
                label="Nama Lengkap"
                icon="user"
                required
                autofocus
                :error="$errors->first('name')"
                value="{{ old('name') }}"
            />
        </div>
        <div>
            <x-input
                id="email"
                name="email"
                type="email"
                label="Email"
                icon="envelope"
                required
                autocomplete="email"
                :error="$errors->first('email')"
                value="{{ old('email') }}"
            />
        </div>
        <div>
            <x-input
                id="nis"
                name="nis"
                type="text"
                label="NIS"
                icon="key"
                :error="$errors->first('nis')"
                value="{{ old('nis') }}"
            />
        </div>
        <div>
            <x-input
                id="password"
                name="password"
                type="password"
                label="Password"
                icon="lock"
                required
                autocomplete="new-password"
                :error="$errors->first('password')"
                onkeyup="checkCapsLock(event)"
            />
            <span id="caps-lock-indicator" class="caps-lock-indicator">Caps Lock aktif</span>
        </div>
        <div>
            <x-input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                label="Konfirmasi Password"
                icon="lock"
                required
                autocomplete="new-password"
            />
        </div>
        <div>
            <button type="submit" id="register-btn" class="flex w-full justify-center rounded-md bg-gray-900 px-5 py-3 text-base font-semibold text-white shadow-xs hover:bg-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 transition-all duration-200 disabled:opacity-60">
                <span id="register-btn-text">Daftar</span>
                <svg id="register-spinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
    </form>
    <p class="mt-10 text-center text-sm text-gray-500">
    Sudah punya akun? 
        <a href="{{ route('login') }}" class="font-semibold text-gray-900 hover:text-gray-700">Masuk</a>
    </p>
    <script>
    function checkCapsLock(e) {
      const indicator = document.getElementById('caps-lock-indicator');
      if (e.getModifierState && e.getModifierState('CapsLock')) {
        indicator.classList.add('active');
      } else {
        indicator.classList.remove('active');
      }
    }
    const form = document.getElementById('register-form');
    const btn = document.getElementById('register-btn');
    const btnText = document.getElementById('register-btn-text');
    const spinner = document.getElementById('register-spinner');
    form.addEventListener('submit', function() {
      btn.disabled = true;
      btnText.style.display = 'none';
      spinner.classList.remove('hidden');
    });
    </script>
@endsection
