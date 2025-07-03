<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'icon',
        'read',
        'read_at',
        'action_url',
        'data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read' => 'boolean',
            'read_at' => 'datetime',
            'data' => 'array',
        ];
    }

    /**
     * User yang menerima notifikasi
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->read = true;
        $this->read_at = now();
        $this->save();
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Scope untuk notifikasi berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Create a new notification for user
     */
    public static function send(int $userId, string $title, string $message, string $type = 'info', string $icon = 'bell', string $actionUrl = null, array $data = []): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'read' => false,
            'action_url' => $actionUrl,
            'data' => $data
        ]);
    }

    /**
     * Broadcast notification to multiple users
     */
    public static function broadcast(array $userIds, string $title, string $message, string $type = 'info', string $icon = 'bell', string $actionUrl = null, array $data = []): void
    {
        foreach ($userIds as $userId) {
            self::send($userId, $title, $message, $type, $icon, $actionUrl, $data);
        }
    }
} 