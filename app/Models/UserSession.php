<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'last_activity',
        'is_active',
        'device_type',
        'browser',
        'platform',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'login_at' => 'datetime',
            'logout_at' => 'datetime',
            'last_activity' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the session
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark session as logged out
     */
    public function logout()
    {
        $this->update([
            'logout_at' => now(),
            'is_active' => false,
        ]);
    }

    /**
     * Update last activity
     */
    public function updateActivity()
    {
        $this->update(['last_activity' => now()]);
    }

    /**
     * Check if session is expired
     */
    public function isExpired($timeoutMinutes = 30)
    {
        return $this->last_activity->diffInMinutes(now()) > $timeoutMinutes;
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for expired sessions
     */
    public function scopeExpired($query, $timeoutMinutes = 30)
    {
        return $query->where('last_activity', '<', now()->subMinutes($timeoutMinutes));
    }

    /**
     * Scope for sessions from specific IP
     */
    public function scopeFromIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Get session duration in minutes
     */
    public function getDurationAttribute()
    {
        $endTime = $this->logout_at ?? $this->last_activity ?? now();
        return $this->login_at->diffInMinutes($endTime);
    }
}
