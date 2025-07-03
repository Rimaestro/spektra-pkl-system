<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'pkl_id',
        'title',
        'description',
        'icon',
        'type',
        'date',
        'status',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * User yang terkait dengan aktivitas
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * PKL yang terkait dengan aktivitas
     */
    public function pkl()
    {
        return $this->belongsTo(PKL::class);
    }

    /**
     * Scope untuk aktivitas yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())
                     ->orderBy('date', 'asc');
    }

    /**
     * Scope untuk aktivitas terbaru
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    
    /**
     * Scope untuk aktivitas berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope untuk aktivitas yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk aktivitas yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    /**
     * Check jika aktivitas akan datang
     */
    public function isUpcoming(): bool
    {
        return $this->date->isFuture();
    }
    
    /**
     * Check jika aktivitas sudah lewat
     */
    public function isPast(): bool
    {
        return $this->date->isPast();
    }
    
    /**
     * Check jika aktivitas adalah hari ini
     */
    public function isToday(): bool
    {
        return $this->date->isToday();
    }
    
    /**
     * Get sisa waktu ke aktivitas
     */
    public function timeUntil(): string
    {
        if ($this->isPast()) {
            return 'Sudah lewat';
        }
        
        return $this->date->diffForHumans();
    }
} 