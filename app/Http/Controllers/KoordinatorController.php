<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PKL;
use App\Models\Company;
use App\Models\Report;
use App\Models\Evaluation;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class KoordinatorController extends Controller
{
    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isKoordinator()) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan dashboard koordinator
     */
    public function dashboard()
    {
        // Statistik dashboard
        $totalSiswa = User::siswa()->count();
        $totalPKLAktif = PKL::ongoing()->count();
        $totalPKLPending = PKL::byStatus('pending')->count();
        $totalPerusahaan = Company::count();

        // PKL yang perlu diverifikasi
        $pendingPKLs = PKL::byStatus('pending')
            ->with(['user', 'company'])
            ->latest()
            ->take(5)
            ->get();

        // PKL aktif
        $ongoingPKLs = PKL::ongoing()
            ->with(['user', 'company'])
            ->latest()
            ->take(5)
            ->get();
        
        // Aktivitas terbaru
        $activities = Activity::with(['user', 'pkl', 'pkl.company'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('koordinator.dashboard', compact(
            'totalSiswa', 
            'totalPKLAktif',
            'totalPKLPending',
            'totalPerusahaan',
            'pendingPKLs',
            'ongoingPKLs',
            'activities'
        ));
    }

    /**
     * Menampilkan daftar siswa PKL
     */
    public function students()
    {
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
                    return $query->whereDoesntHave('pkls');
                } elseif (request('status')) {
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

        return view('koordinator.students', compact('students'));
    }

    /**
     * Menampilkan daftar pendaftaran PKL
     */
    public function registrations()
    {
        $registrations = PKL::with(['user', 'company'])
            ->when(request('status'), function($query) {
                return $query->where('status', request('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('koordinator.registrations', compact('registrations'));
    }

    /**
     * Menampilkan detail pendaftaran PKL
     */
    public function showRegistration(PKL $pkl)
    {
        $pkl->load(['user', 'company', 'supervisor', 'fieldSupervisor']);
        return view('koordinator.registration-detail', compact('pkl'));
    }

    /**
     * Menyetujui pendaftaran PKL
     */
    public function approveRegistration(Request $request, PKL $pkl)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
            'field_supervisor_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $pkl->status = 'approved';
        $pkl->supervisor_id = $request->supervisor_id;
        $pkl->field_supervisor_id = $request->field_supervisor_id;
        $pkl->notes = $request->notes;
        $pkl->approved_by = Auth::id();
        $pkl->approved_at = now();
        $pkl->save();

        // Catat aktivitas
        Activity::create([
            'user_id' => Auth::id(),
            'pkl_id' => $pkl->id,
            'type' => 'approval',
            'description' => 'Menyetujui pendaftaran PKL ' . $pkl->user->name . ' di ' . $pkl->company->name,
            'date' => now(),
            'icon' => 'check-circle'
        ]);

        return redirect()->route('koordinator.registrations')
            ->with('success', 'Pendaftaran PKL berhasil disetujui.');
    }

    /**
     * Menolak pendaftaran PKL
     */
    public function rejectRegistration(Request $request, PKL $pkl)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $pkl->status = 'rejected';
        $pkl->rejection_reason = $request->rejection_reason;
        $pkl->rejected_by = Auth::id();
        $pkl->rejected_at = now();
        $pkl->save();

        // Catat aktivitas
        Activity::create([
            'user_id' => Auth::id(),
            'pkl_id' => $pkl->id,
            'type' => 'rejection',
            'description' => 'Menolak pendaftaran PKL ' . $pkl->user->name . ' di ' . $pkl->company->name,
            'date' => now(),
            'icon' => 'x-circle'
        ]);

        return redirect()->route('koordinator.registrations')
            ->with('success', 'Pendaftaran PKL berhasil ditolak.');
    }

    /**
     * Menampilkan daftar perusahaan
     */
    public function companies()
    {
        $companies = Company::when(request('search'), function($query) {
            $search = request('search');
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
            });
        })
        ->paginate(10)
        ->withQueryString();

        return view('koordinator.companies', compact('companies'));
    }

    /**
     * Menampilkan detail perusahaan
     */
    public function showCompany(Company $company)
    {
        $company->load(['pkls' => function($query) {
            $query->with('user');
        }]);
        
        return view('koordinator.company-detail', compact('company'));
    }

    /**
     * Menampilkan halaman monitoring
     */
    public function monitoring()
    {
        $pkls = PKL::ongoing()
            ->with(['user', 'company', 'supervisor', 'fieldSupervisor', 'reports' => function($query) {
                $query->latest();
            }])
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        return view('koordinator.monitoring', compact('pkls'));
    }

    /**
     * Menampilkan detail monitoring PKL
     */
    public function showMonitoring(PKL $pkl)
    {
        $pkl->load([
            'user', 
            'company', 
            'supervisor', 
            'fieldSupervisor',
            'reports' => function($query) {
                $query->latest();
            },
            'evaluations' => function($query) {
                $query->latest();
            }
        ]);
        
        return view('koordinator.monitoring-detail', compact('pkl'));
    }

    /**
     * Menampilkan laporan yang perlu direview
     */
    public function reports()
    {
        $reports = Report::with(['pkl', 'pkl.user', 'pkl.company'])
            ->when(request('status'), function($query) {
                return $query->where('status', request('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        return view('koordinator.reports', compact('reports'));
    }
} 