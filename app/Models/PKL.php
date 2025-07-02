<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PKL extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pkls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'supervisor_id',
        'field_supervisor_id',
        'start_date',
        'end_date',
        'status',
        'description',
        'documents',
        'rejection_reason',
        'final_score',
        'defense_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'defense_date' => 'date',
            'documents' => 'array',
            'final_score' => 'decimal:2',
        ];
    }

    /**
     * Check if PKL is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if PKL is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if PKL is ongoing
     */
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    /**
     * Check if PKL is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if PKL is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get PKL duration in days
     */
    public function getDurationInDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get remaining days
     */
    public function getRemainingDays(): int
    {
        if (!$this->end_date || $this->isCompleted()) {
            return 0;
        }

        $today = Carbon::today();
        if ($today->gt($this->end_date)) {
            return 0;
        }

        return $today->diffInDays($this->end_date);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentage(): float
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $today = Carbon::today();
        $totalDays = $this->getDurationInDays();

        if ($totalDays <= 0) {
            return 0;
        }

        if ($today->lt($this->start_date)) {
            return 0;
        }

        if ($today->gt($this->end_date)) {
            return 100;
        }

        $elapsedDays = $this->start_date->diffInDays($today);
        return min(100, ($elapsedDays / $totalDays) * 100);
    }

    /**
     * Mahasiswa yang melakukan PKL
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Perusahaan tempat PKL
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Dosen pembimbing
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Pembimbing lapangan
     */
    public function fieldSupervisor()
    {
        return $this->belongsTo(User::class, 'field_supervisor_id');
    }

    /**
     * Laporan PKL
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Evaluasi PKL
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Scope untuk PKL berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk PKL yang sedang berlangsung
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope untuk PKL yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'ongoing' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            default => 'Unknown'
        };
    }

    /**
     * Get status color class for UI
     */
    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'pending' => 'text-warning',
            'approved' => 'text-info',
            'rejected' => 'text-danger',
            'ongoing' => 'text-primary',
            'completed' => 'text-success',
            default => 'text-secondary'
        };
    }

    /**
     * Check if PKL can be started
     */
    public function canBeStarted(): bool
    {
        return $this->isApproved() &&
               $this->start_date &&
               Carbon::today()->gte($this->start_date);
    }

    /**
     * Check if PKL is overdue
     */
    public function isOverdue(): bool
    {
        return $this->isOngoing() &&
               $this->end_date &&
               Carbon::today()->gt($this->end_date);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration(): string
    {
        $days = $this->getDurationInDays();

        if ($days < 30) {
            return "{$days} hari";
        } elseif ($days < 365) {
            $months = round($days / 30);
            return "{$months} bulan";
        } else {
            $years = round($days / 365, 1);
            return "{$years} tahun";
        }
    }

    /**
     * Get documents count
     */
    public function getDocumentsCount(): int
    {
        return is_array($this->documents) ? count($this->documents) : 0;
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        $requiredDocs = ['proposal', 'surat_pengantar', 'cv'];
        $uploadedDocs = array_keys($this->documents ?? []);

        return empty(array_diff($requiredDocs, $uploadedDocs));
    }
}
