<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KoordinatorController;
use App\Models\User;
use App\Models\PKL;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

// Guest routes (unauthenticated users)
Route::middleware(['web', 'guest'])->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Register routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password reset routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        // Redirect ke dashboard sesuai dengan role user
        $user = auth()->user();
        
        if ($user->isSiswa()) {
            return redirect()->route('pkl.siswa.dashboard');
        } elseif ($user->isKoordinator()) {
            return redirect()->route('koordinator.dashboard');
        }
        
        return view('dashboard');
    })->name('dashboard');

    // Authentication
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Koordinator routes
    Route::middleware('auth')->prefix('koordinator')->name('koordinator.')->group(function () {
        Route::get('/dashboard', [KoordinatorController::class, 'dashboard'])->name('dashboard');
        
        // Manajemen Siswa PKL
        Route::get('/siswa', [KoordinatorController::class, 'students'])->name('students');
        
        // Manajemen Pendaftaran PKL
        Route::get('/pendaftaran', [KoordinatorController::class, 'registrations'])->name('registrations');
        Route::get('/pendaftaran/{pkl}', [KoordinatorController::class, 'showRegistration'])->name('registration.show');
        Route::post('/pendaftaran/{pkl}/approve', [KoordinatorController::class, 'approveRegistration'])->name('registration.approve');
        Route::post('/pendaftaran/{pkl}/reject', [KoordinatorController::class, 'rejectRegistration'])->name('registration.reject');
        
        // Manajemen Perusahaan
        Route::get('/perusahaan', [KoordinatorController::class, 'companies'])->name('companies');
        Route::get('/perusahaan/{company}', [KoordinatorController::class, 'showCompany'])->name('company.show');
        
        // Monitoring PKL
        Route::get('/monitoring', [KoordinatorController::class, 'monitoring'])->name('monitoring');
        Route::get('/monitoring/{pkl}', [KoordinatorController::class, 'showMonitoring'])->name('monitoring.show');
        
        // Laporan
        Route::get('/laporan', [KoordinatorController::class, 'reports'])->name('reports');
    });

    // PKL Management
    Route::prefix('pkl')->name('pkl.')->group(function () {
        // Siswa
        Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
        
        Route::get('/siswa', function () {
            $students = User::siswa()
                ->when(request('search'), function($query) {
                    $search = request('search');
                    return $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('nis', 'like', "%{$search}%");
                    });
                })
                ->when(request('status'), function($query) {
                    if (request('status') === 'none') {
                        // Siswa yang belum pernah mendaftar PKL
                        return $query->whereDoesntHave('pkls');
                    } else if (request('status')) {
                        // Siswa dengan status PKL tertentu
                        return $query->whereHas('pkls', function($q) {
                            $q->where('status', request('status'));
                        });
                    }
                })
                ->with(['pkls' => function($query) {
                    $query->latest()->with('company');
                }])
                ->paginate(10)
                ->withQueryString();
                
            return view('pkl.siswa.index', compact('students'));
        })->name('siswa');

        Route::get('/siswa/create', function() {
            return view('pkl.siswa.create');
        })->name('siswa.create');

        Route::get('/siswa/{user}', function(User $user) {
            $user->load(['pkls' => function($query) {
                $query->with(['company', 'supervisor', 'fieldSupervisor', 'reports']);
            }]);
            return view('pkl.siswa.show', compact('user'));
        })->name('siswa.show');

        Route::get('/siswa/{user}/edit', function(User $user) {
            return view('pkl.siswa.edit', compact('user'));
        })->name('siswa.edit');

        // Pendaftaran PKL
        Route::get('/pendaftaran', function () {
            return view('pkl.pendaftaran.index');
        })->name('pendaftaran');

        Route::get('/pendaftaran/create', function() {
            $student_id = request('student_id');
            $student = null;
            
            if ($student_id) {
                $student = User::find($student_id);
            }
            
            return view('pkl.pendaftaran.create', compact('student'));
        })->name('pendaftaran.create');

        Route::post('/pendaftaran', function(Request $request) {
            // Validasi input
            $validated = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'position' => 'required|string|max:100',
                'motivation_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'parent_approval' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'additional_document' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:5120',
            ]);
            
            // Simpan data PKL
            $pkl = new PKL();
            $pkl->user_id = auth()->id();
            $pkl->company_id = $validated['company_id'];
            $pkl->start_date = $validated['start_date'];
            $pkl->end_date = $validated['end_date'];
            $pkl->position = $validated['position'];
            $pkl->status = 'pending';
            
            // Upload dokumen jika ada
            if ($request->hasFile('motivation_letter')) {
                $path = $request->file('motivation_letter')->store('pkl/documents', 'public');
                $pkl->motivation_letter_path = $path;
            }
            
            if ($request->hasFile('parent_approval')) {
                $path = $request->file('parent_approval')->store('pkl/documents', 'public');
                $pkl->parent_approval_path = $path;
            }
            
            if ($request->hasFile('additional_document')) {
                $path = $request->file('additional_document')->store('pkl/documents', 'public');
                $pkl->additional_document_path = $path;
            }
            
            $pkl->save();
            
            // Catat aktivitas
            if (class_exists('App\Models\Activity')) {
                \App\Models\Activity::create([
                    'user_id' => auth()->id(),
                    'pkl_id' => $pkl->id,
                    'type' => 'registration',
                    'description' => 'Mendaftar PKL di ' . $pkl->company->name,
                    'date' => now(),
                    'icon' => 'pencil'
                ]);
            }
            
            return redirect()->route('pkl.pendaftaran.show', $pkl->id)
                ->with('success', 'Pendaftaran PKL berhasil dikirim.');
        })->name('pendaftaran.store');

        Route::put('/pendaftaran/{pkl}/cancel', function(PKL $pkl) {
            // Cek apakah user yang login adalah pemilik PKL atau admin
            if (auth()->id() !== $pkl->user_id && !auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }
            
            // Cek apakah status masih pending
            if ($pkl->status !== 'pending') {
                return back()->with('error', 'Hanya pendaftaran dengan status menunggu yang dapat dibatalkan.');
            }
            
            // Update status menjadi cancelled
            $pkl->status = 'cancelled';
            $pkl->save();
            
            // Catat aktivitas
            if (class_exists('App\Models\Activity')) {
                \App\Models\Activity::create([
                    'user_id' => auth()->id(),
                    'pkl_id' => $pkl->id,
                    'type' => 'cancellation',
                    'description' => 'Membatalkan pendaftaran PKL di ' . $pkl->company->name,
                    'date' => now(),
                    'icon' => 'x-mark'
                ]);
            }
            
            return redirect()->route('pkl.pendaftaran')
                ->with('success', 'Pendaftaran PKL berhasil dibatalkan.');
        })->name('pendaftaran.cancel');

        Route::get('/pendaftaran/{pkl}/download/{document}', function(PKL $pkl, $document) {
            // Cek apakah user yang login adalah pemilik PKL, admin, atau pembimbing
            if (auth()->id() !== $pkl->user_id && 
                !auth()->user()->isAdmin() && 
                !auth()->user()->isDosen() && 
                auth()->id() !== $pkl->supervisor_id) {
                abort(403, 'Unauthorized action.');
            }
            
            // Tentukan path dokumen berdasarkan parameter
            $path = null;
            if ($document === 'motivation_letter') {
                $path = $pkl->motivation_letter_path;
            } elseif ($document === 'parent_approval') {
                $path = $pkl->parent_approval_path;
            } elseif ($document === 'additional_document') {
                $path = $pkl->additional_document_path;
            } else {
                abort(404, 'Document not found.');
            }
            
            // Cek apakah file ada
            if (!$path || !Storage::disk('public')->exists($path)) {
                abort(404, 'File not found.');
            }
            
            return Storage::disk('public')->download($path);
        })->name('pendaftaran.download');

        Route::get('/pendaftaran/{pkl}', function(PKL $pkl) {
            $pkl->load(['user', 'company', 'supervisor', 'fieldSupervisor']);
            return view('pkl.pendaftaran.show', compact('pkl'));
        })->name('pendaftaran.show');

        // Monitoring
        Route::get('/monitoring', function () {
            return view('pkl.monitoring.index');
        })->name('monitoring');

        // Companies/Perusahaan PKL
        Route::get('/company/{company}', function (Company $company) {
            return view('pkl.company.show', compact('company'));
        })->name('company');

        Route::get('/companies', function () {
            $companies = Company::paginate(10);
            return view('pkl.company.index', compact('companies'));
        })->name('companies');
    });

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Laporan Harian
        Route::get('/harian', function () {
            return view('laporan.harian.index');
        })->name('harian.index');

        Route::get('/harian/create', function () {
            return view('laporan.harian.create');
        })->name('harian.create');

        Route::post('/harian', function (Request $request) {
            // Logic untuk menyimpan laporan harian
            return redirect()->route('laporan.harian.index')->with('success', 'Laporan berhasil disimpan');
        })->name('harian.store');

        Route::get('/harian/{report}', function ($report) {
            // Logic untuk menampilkan detail laporan harian
            return view('laporan.harian.show', compact('report'));
        })->name('harian.show');

        // Laporan Akhir
        Route::get('/akhir', function () {
            return view('laporan.akhir.index');
        })->name('akhir.index');

        Route::get('/akhir/create', function () {
            return view('laporan.akhir.create');
        })->name('akhir.create');

        Route::get('/akhir/{report}', function ($report) {
            // Logic untuk menampilkan detail laporan akhir
            return view('laporan.akhir.show', compact('report'));
        })->name('akhir.show');
    });

    // Pengaturan
    Route::prefix('settings')->name('settings.')->group(function () {
        // Sistem
        Route::get('/system', function () {
            return view('settings.system');
        })->name('system');

        // Pengguna
        Route::get('/users', function () {
            return view('settings.users');
        })->name('users');
    });

    // Profil
    Route::prefix('profile')->name('profile.')->group(function () {
        // Profil Saya
        Route::get('/', function () {
            return view('profile.show');
        })->name('show');

        // Pengaturan Profil
        Route::get('/settings', function () {
            return view('profile.settings');
        })->name('settings');
    });

    // Email verification routes (optional)
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function () {
        // Email verification logic
        return redirect()->route('dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        // Resend verification email logic
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});
