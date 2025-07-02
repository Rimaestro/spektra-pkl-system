<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'phone',
        'email',
        'description',
        'website',
        'status',
        'max_students',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'max_students' => 'integer',
        ];
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            default => 'Unknown'
        };
    }

    /**
     * Get status color class for UI
     */
    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'active' => 'text-success',
            'inactive' => 'text-danger',
            default => 'text-secondary'
        };
    }

    /**
     * Get company type from name
     */
    public function getCompanyType(): string
    {
        if (str_starts_with($this->name, 'PT.')) {
            return 'PT';
        } elseif (str_starts_with($this->name, 'CV.')) {
            return 'CV';
        } elseif (str_contains($this->name, 'Startup')) {
            return 'Startup';
        } else {
            return 'Lainnya';
        }
    }

    /**
     * Scope untuk company yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if company is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get available slots for students
     */
    public function getAvailableSlots(): int
    {
        $currentStudents = $this->pkls()->where('status', 'ongoing')->count();
        return max(0, $this->max_students - $currentStudents);
    }

    /**
     * Check if company has available slots
     */
    public function hasAvailableSlots(): bool
    {
        return $this->getAvailableSlots() > 0;
    }

    /**
     * PKL yang ada di perusahaan ini
     */
    public function pkls()
    {
        return $this->hasMany(PKL::class);
    }

    /**
     * PKL yang sedang berlangsung
     */
    public function ongoingPkls()
    {
        return $this->pkls()->where('status', 'ongoing');
    }

    /**
     * PKL yang sudah selesai
     */
    public function completedPkls()
    {
        return $this->pkls()->where('status', 'completed');
    }
}
