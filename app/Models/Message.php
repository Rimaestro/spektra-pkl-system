<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
        'attachments',
        'read_at',
        'priority',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'attachments' => 'array',
        ];
    }

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if message is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Check if message has high priority
     */
    public function isHighPriority(): bool
    {
        return $this->priority === 'high';
    }

    /**
     * Check if message has normal priority
     */
    public function isNormalPriority(): bool
    {
        return $this->priority === 'normal';
    }

    /**
     * Check if message has low priority
     */
    public function isLowPriority(): bool
    {
        return $this->priority === 'low';
    }

    /**
     * Get priority label
     */
    public function getPriorityLabel(): string
    {
        return match($this->priority) {
            'high' => 'Tinggi',
            'normal' => 'Normal',
            'low' => 'Rendah',
            default => 'Normal'
        };
    }

    /**
     * Get priority color class
     */
    public function getPriorityColorClass(): string
    {
        return match($this->priority) {
            'high' => 'text-danger',
            'normal' => 'text-primary',
            'low' => 'text-secondary',
            default => 'text-primary'
        };
    }

    /**
     * User yang mengirim pesan
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * User yang menerima pesan
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Scope untuk pesan yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope untuk pesan yang sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope untuk pesan berdasarkan prioritas
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope untuk pesan dengan prioritas tinggi
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * Scope untuk pesan yang diterima user tertentu
     */
    public function scopeReceivedBy($query, $userId)
    {
        return $query->where('receiver_id', $userId);
    }

    /**
     * Scope untuk pesan yang dikirim user tertentu
     */
    public function scopeSentBy($query, $userId)
    {
        return $query->where('sender_id', $userId);
    }

    /**
     * Get priority badge class for UI
     */
    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'high' => 'badge-danger',
            'normal' => 'badge-primary',
            'low' => 'badge-secondary',
            default => 'badge-primary'
        };
    }

    /**
     * Get time ago formatted
     */
    public function getTimeAgo(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if message has attachments
     */
    public function hasAttachments(): bool
    {
        return is_array($this->attachments) && count($this->attachments) > 0;
    }

    /**
     * Get attachments count
     */
    public function getAttachmentsCount(): int
    {
        return is_array($this->attachments) ? count($this->attachments) : 0;
    }

    /**
     * Get message preview (first 100 characters)
     */
    public function getPreview(int $length = 100): string
    {
        return str_limit(strip_tags($this->message), $length);
    }

    /**
     * Check if message is from system
     */
    public function isSystemMessage(): bool
    {
        return $this->sender_id === null || $this->sender->email === 'system@spektra.ac.id';
    }

    /**
     * Scope untuk conversation antara dua user
     */
    public function scopeConversation($query, $userId1, $userId2)
    {
        return $query->where(function($q) use ($userId1, $userId2) {
            $q->where(function($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId1)
                     ->where('receiver_id', $userId2);
            })->orWhere(function($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId2)
                     ->where('receiver_id', $userId1);
            });
        })->orderBy('created_at', 'asc');
    }

    /**
     * Scope untuk pesan terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Mark conversation as read for specific user
     */
    public static function markConversationAsRead($userId1, $userId2)
    {
        return static::where('sender_id', $userId2)
                    ->where('receiver_id', $userId1)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
    }
}
