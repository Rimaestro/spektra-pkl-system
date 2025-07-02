<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'nip',
        'phone',
        'address',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's full name with role
     */
    public function getFullNameWithRoleAttribute(): string
    {
        $roleLabels = [
            'admin' => 'Administrator',
            'koordinator' => 'Koordinator PKL',
            'dosen' => 'Dosen Pembimbing',
            'mahasiswa' => 'Mahasiswa',
            'pembimbing_lapangan' => 'Pembimbing Lapangan'
        ];

        $roleLabel = $roleLabels[$this->role] ?? $this->role;
        return "{$this->name} ({$roleLabel})";
    }

    /**
     * Get role label in Indonesian
     */
    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'koordinator' => 'Koordinator PKL',
            'dosen' => 'Dosen Pembimbing',
            'mahasiswa' => 'Mahasiswa',
            'pembimbing_lapangan' => 'Pembimbing Lapangan',
            default => 'Unknown'
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'pending' => 'Menunggu Verifikasi',
            default => 'Unknown'
        };
    }

    /**
     * Scope untuk user berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk user yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk mahasiswa
     */
    public function scopeMahasiswa($query)
    {
        return $query->where('role', 'mahasiswa');
    }

    /**
     * Scope untuk dosen
     */
    public function scopeDosen($query)
    {
        return $query->where('role', 'dosen');
    }

    /**
     * Scope untuk pembimbing lapangan
     */
    public function scopePembimbingLapangan($query)
    {
        return $query->where('role', 'pembimbing_lapangan');
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is koordinator
     */
    public function isKoordinator(): bool
    {
        return $this->hasRole('koordinator');
    }

    /**
     * Check if user is dosen
     */
    public function isDosen(): bool
    {
        return $this->hasRole('dosen');
    }

    /**
     * Check if user is mahasiswa
     */
    public function isMahasiswa(): bool
    {
        return $this->hasRole('mahasiswa');
    }

    /**
     * Check if user is pembimbing lapangan
     */
    public function isPembimbingLapangan(): bool
    {
        return $this->hasRole('pembimbing_lapangan');
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * PKL yang dimiliki user (untuk mahasiswa)
     */
    public function pkls()
    {
        return $this->hasMany(PKL::class);
    }

    /**
     * PKL yang dibimbing sebagai dosen pembimbing
     */
    public function supervisedPkls()
    {
        return $this->hasMany(PKL::class, 'supervisor_id');
    }

    /**
     * PKL yang dibimbing sebagai pembimbing lapangan
     */
    public function fieldSupervisedPkls()
    {
        return $this->hasMany(PKL::class, 'field_supervisor_id');
    }

    /**
     * Pesan yang dikirim
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Pesan yang diterima
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Evaluasi yang dibuat
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }
}
