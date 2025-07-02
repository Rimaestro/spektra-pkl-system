<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:mahasiswa,dosen,pembimbing_lapangan'],
            'nim' => ['nullable', 'string', 'max:20', 'unique:users'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Validate role-specific fields
        if ($validated['role'] === 'mahasiswa' && empty($validated['nim'])) {
            return back()->withErrors(['nim' => 'NIS wajib diisi untuk siswa.'])->withInput();
        }

        if (in_array($validated['role'], ['dosen', 'pembimbing_lapangan']) && empty($validated['nip'])) {
            return back()->withErrors(['nip' => 'NIP wajib diisi untuk dosen/pembimbing lapangan.'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nim' => $validated['nim'],
            'nip' => $validated['nip'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'pending', // Default status
            'password_changed_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan admin.');
    }
}
