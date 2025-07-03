<?php

namespace App\Http\Controllers;

use App\Models\Pkl;
use App\Models\User;
use App\Models\Report;
use App\Models\Activity;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    /**
     * Menampilkan dashboard untuk siswa
     */
    public function dashboard()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Ambil data PKL terbaru milik siswa
        $pkl = Pkl::where('user_id', $user->id)
                  ->with('company')
                  ->orderBy('created_at', 'desc')
                  ->first();

        // Ambil aktivitas terbaru
        $recentActivities = Activity::where('user_id', $user->id)
                                   ->orWhere(function($query) use ($pkl) {
                                       if ($pkl) {
                                           $query->where('pkl_id', $pkl->id);
                                       }
                                   })
                                   ->orderBy('created_at', 'desc')
                                   ->take(5)
                                   ->get();
        
        // Ambil notifikasi terbaru
        $notifications = Notification::where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->take(5)
                                   ->get();
        
        // Ambil jadwal kegiatan mendatang
        $upcomingActivities = Activity::where('user_id', $user->id)
                                    ->where('date', '>=', now())
                                    ->orderBy('date', 'asc')
                                    ->take(5)
                                    ->get();
        
        return view('pkl.siswa.dashboard', compact(
            'user', 
            'pkl', 
            'recentActivities', 
            'notifications', 
            'upcomingActivities'
        ));
    }

    /**
     * Menampilkan daftar siswa
     */
    public function index()
    {
        // Ambil daftar siswa
        $students = User::role('siswa')
                       ->with(['pkls' => function($query) {
                           $query->with('company');
                       }])
                       ->paginate(10);
        
        return view('pkl.siswa.index', compact('students'));
    }

    /**
     * Menampilkan detail siswa
     */
    public function show($id)
    {
        $student = User::findOrFail($id);
        
        // Ambil data PKL terbaru milik siswa
        $pkl = Pkl::where('user_id', $student->id)
                  ->with('company')
                  ->orderBy('created_at', 'desc')
                  ->first();
        
        // Ambil laporan siswa
        $reports = Report::where('user_id', $student->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('pkl.siswa.show', compact('student', 'pkl', 'reports'));
    }

    /**
     * Menampilkan form edit siswa
     */
    public function edit($id)
    {
        $student = User::findOrFail($id);
        
        return view('pkl.siswa.edit', compact('student'));
    }

    /**
     * Update data siswa
     */
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $student->id,
            'nis' => 'required|string|max:20|unique:users,nis,' . $student->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
        ]);
        
        $student->update($validated);
        
        return redirect()->route('pkl.siswa.show', $student->id)
            ->with('success', 'Data siswa berhasil diperbarui');
    }
} 