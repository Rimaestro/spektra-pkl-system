@extends('layouts.auth')

@section('title', 'Siap Eksplor Dunia Kerja? Login Dulu!')
@section('subtitle', 'Silakan login untuk mengakses dashboard PKL Anda')

@section('content')
    <style>
    .fade-in {
      animation: fadeIn 0.4s ease-in;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
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
    @if (session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3500)"
            x-show="show"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition duration-300 ease-in"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-2 bg-white border border-gray-200 text-gray-800 px-4 py-3 rounded-md shadow-sm mb-4"
        >
            <x-icon name="check" size="md" class="text-green-400" />
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('login_error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition duration-300 ease-in"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-2 bg-white border border-gray-200 text-gray-800 px-4 py-3 rounded-md shadow-sm mb-4"
        >
            <x-icon name="x-mark" size="md" class="text-red-400" />
            <span>{{ session('login_error') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition duration-300 ease-in"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-2 bg-white border border-gray-200 text-gray-800 px-4 py-3 rounded-md shadow-sm mb-4"
        >
            <x-icon name="x-mark" size="md" class="text-red-400" />
            <span>{{ $errors->first() }}</span>
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="space-y-6 fade-in" id="login-form">
        @csrf
        <div>
            <x-input
                id="email"
                name="email"
                type="email"
                label="Email"
                icon="envelope"
                required
                autocomplete="email"
                autofocus
                :error="null"
                value="{{ old('email') }}"
            />
        </div>
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-semibold text-gray-900 hover:text-gray-700">Lupa password?</a>
                </div>
            </div>
            <div class="relative mt-2">
                <x-input
                    id="password"
                    name="password"
                    type="password"
                    icon="lock"
                    required
                    autocomplete="current-password"
                    :error="$errors->first('password')"
                    class="pr-12"
                    onkeyup="checkCapsLock(event)"
                />
                <button type="button" id="togglePassword" tabindex="-1" class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 focus:outline-none" onclick="togglePasswordVisibility()">
                    <x-icon name="eye" size="md" />
                </button>
            </div>
            <span id="caps-lock-indicator" class="caps-lock-indicator">Caps Lock aktif</span>
        </div>
        <div>
            <button type="submit" id="login-btn" class="flex w-full justify-center rounded-md bg-gray-900 px-5 py-3 text-base font-semibold text-white shadow-xs hover:bg-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 transition-all duration-200 disabled:opacity-60">
                <span id="login-btn-text">Masuk</span>
                <svg id="login-spinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
    </form>
    <p class="mt-10 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-gray-900 hover:text-gray-700">Daftar</a>
    </p>
@endsection

<script>
function togglePasswordVisibility() {
    const input = document.getElementById('password');
    const btn = document.getElementById('togglePassword');
    const icon = btn.querySelector('svg');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12.07c-.07.207-.07.431 0 .639C3.423 16.49 7.36 19.5 12 19.5c2.042 0 3.977-.492 5.657-1.357m2.362-2.06A10.45 10.45 0 0022.066 12.07c.07-.208.07-.432 0-.639C20.577 7.51 16.64 4.5 12 4.5c-1.306 0-2.563.186-3.738.533M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
    }
}

function checkCapsLock(e) {
  const indicator = document.getElementById('caps-lock-indicator');
  if (e.getModifierState && e.getModifierState('CapsLock')) {
    indicator.classList.add('active');
  } else {
    indicator.classList.remove('active');
  }
}

const form = document.getElementById('login-form');
const btn = document.getElementById('login-btn');
const btnText = document.getElementById('login-btn-text');
const spinner = document.getElementById('login-spinner');
if (form) {
  form.addEventListener('submit', function() {
    btn.disabled = true;
    btnText.style.display = 'none';
    spinner.classList.remove('hidden');
  });
}
</script>
