<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
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
        'nis',
        'nip',
        'phone',
        'address',
        'avatar',
        'status',
        'last_login_at',
        'last_login_ip',
        'login_attempts',
        'locked_until',
        'password_changed_at',
        'force_password_change',
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
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'password_changed_at' => 'datetime',
            'force_password_change' => 'boolean',
            'login_attempts' => 'integer',
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
            'siswa' => 'Siswa',
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
            'siswa' => 'Siswa',
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
     * Scope untuk siswa
     */
    public function scopeSiswa($query)
    {
        return $query->where('role', 'siswa');
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
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
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
     * Check if user is siswa
     */
    public function isSiswa(): bool
    {
        return $this->hasRole('siswa');
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
     * Check if user account is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Check if user needs to change password
     */
    public function needsPasswordChange(): bool
    {
        return (bool) $this->force_password_change;
    }

    /**
     * Check if password is expired (older than 90 days)
     */
    public function isPasswordExpired(): bool
    {
        if (!$this->password_changed_at) {
            return true; // Force change if never changed
        }

        return $this->password_changed_at->diffInDays(now()) > 90;
    }

    /**
     * Lock user account for specified minutes
     */
    public function lockAccount(int $minutes = 30): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'login_attempts' => 0, // Reset attempts after locking
        ]);
    }

    /**
     * Unlock user account
     */
    public function unlockAccount(): void
    {
        $this->update([
            'locked_until' => null,
            'login_attempts' => 0,
        ]);
    }

    /**
     * Increment login attempts
     */
    public function incrementLoginAttempts(): void
    {
        $this->increment('login_attempts');

        // Lock account after 5 failed attempts
        if ($this->login_attempts >= 5) {
            $this->lockAccount(30); // Lock for 30 minutes
        }
    }

    /**
     * Reset login attempts
     */
    public function resetLoginAttempts(): void
    {
        $this->update(['login_attempts' => 0]);
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(string $ipAddress): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
            'login_attempts' => 0, // Reset on successful login
        ]);
    }

    /**
     * Force password change on next login
     */
    public function forcePasswordChange(): void
    {
        $this->update(['force_password_change' => true]);
    }

    /**
     * Mark password as changed
     */
    public function markPasswordChanged(): void
    {
        $this->update([
            'password_changed_at' => now(),
            'force_password_change' => false,
        ]);
    }

    /**
     * Get avatar URL
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->avatar) {
                    return asset('storage/' . $this->avatar);
                }

                // Default gravatar
                $hash = md5(strtolower(trim($this->email)));
                return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=150";
            }
        );
    }

    /**
     * Get user initials for avatar placeholder
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $names = explode(' ', $this->name);
                $initials = '';

                foreach ($names as $name) {
                    $initials .= strtoupper(substr($name, 0, 1));
                    if (strlen($initials) >= 2) break;
                }

                return $initials ?: 'U';
            }
        );
    }

    /**
     * PKL yang dimiliki user (untuk siswa)
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

    /**
     * Login attempts for this user
     */
    public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class, 'email', 'email');
    }

    /**
     * Active sessions for this user
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get recent login attempts (last 24 hours)
     */
    public function recentLoginAttempts()
    {
        return $this->loginAttempts()
            ->where('attempted_at', '>=', now()->subDay())
            ->orderBy('attempted_at', 'desc');
    }

    /**
     * Get failed login attempts (last 24 hours)
     */
    public function failedLoginAttempts()
    {
        return $this->recentLoginAttempts()
            ->where('successful', false);
    }

    /**
     * Get active sessions
     */
    public function activeSessions()
    {
        return $this->sessions()
            ->where('is_active', true)
            ->where('last_activity', '>=', now()->subMinutes(30));
    }

    /**
     * Get companies where user is pembimbing lapangan
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_supervisors', 'user_id', 'company_id')
            ->withTimestamps();
    }

    /**
     * Get all reports from supervised PKLs
     */
    public function supervisedReports()
    {
        return $this->hasManyThrough(
            Report::class,
            PKL::class,
            'supervisor_id', // Foreign key on PKL table
            'pkl_id',        // Foreign key on Report table
            'id',            // Local key on User table
            'id'             // Local key on PKL table
        );
    }

    /**
     * Get all reports from field supervised PKLs
     */
    public function fieldSupervisedReports()
    {
        return $this->hasManyThrough(
            Report::class,
            PKL::class,
            'field_supervisor_id', // Foreign key on PKL table
            'pkl_id',              // Foreign key on Report table
            'id',                  // Local key on User table
            'id'                   // Local key on PKL table
        );
    }

    /**
     * Scope for verified users
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope for users who need password change
     */
    public function scopeNeedsPasswordChange($query)
    {
        return $query->where('force_password_change', true)
            ->orWhere(function ($q) {
                $q->whereNull('password_changed_at')
                  ->orWhere('password_changed_at', '<', now()->subDays(90));
            });
    }

    /**
     * Scope for locked users
     */
    public function scopeLocked($query)
    {
        return $query->whereNotNull('locked_until')
            ->where('locked_until', '>', now());
    }
}
