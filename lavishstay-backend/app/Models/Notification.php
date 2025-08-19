<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'notification_type_id',
        'notifiable_type',
        'notifiable_id',
        'title',
        'message',
        'data',
        'priority',
        'icon',
        'color',
        'url',
        'read_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the notification type that owns the notification.
     */
    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(NotificationType::class);
    }

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by notification type.
     */
    public function scopeOfType($query, $typeName)
    {
        return $query->whereHas('notificationType', function ($q) use ($typeName) {
            $q->where('name', $typeName);
        });
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    /**
     * Determine if a notification has been read.
     */
    public function read(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if a notification has not been read.
     */
    public function unread(): bool
    {
        return $this->read_at === null;
    }

    /**
     * Get the time ago string for the notification.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the formatted created at date.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }

    /**
     * Get the is read attribute.
     */
    public function getIsReadAttribute(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Get the priority color based on priority level.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => '#EF4444',
            'high' => '#F59E0B',
            'normal' => '#3B82F6',
            'low' => '#6B7280',
            default => '#3B82F6'
        };
    }

    /**
     * Get the priority icon based on priority level.
     */
    public function getPriorityIconAttribute(): string
    {
        return match($this->priority) {
            'urgent' => '🚨',
            'high' => '⚠️',
            'normal' => '🔔',
            'low' => '📢',
            default => '🔔'
        };
    }

    /**
     * Convert the notification to an array for API responses.
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'color' => $this->color,
            'url' => $this->url,
            'priority' => $this->priority,
            'created_at' => $this->formatted_created_at,
            'time_ago' => $this->time_ago,
            'read_at' => $this->read_at?->format('M d, Y H:i'),
            'is_read' => $this->is_read,
            'data' => $this->data,
            'notification_type' => $this->notificationType ? [
                'id' => $this->notificationType->id,
                'name' => $this->notificationType->name,
                'title' => $this->notificationType->title,
            ] : null,
        ];
    }

    /**
     * Get notifications for a specific user.
     */
    public static function forUser($user, $limit = 10)
    {
        return static::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->with('notificationType')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread notifications count for a specific user.
     */
    public static function unreadCountForUser($user): int
    {
        return static::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->unread()
            ->count();
    }

    /**
     * Mark multiple notifications as read.
     */
    public static function markMultipleAsRead(array $notificationIds, $user = null)
    {
        $query = static::whereIn('id', $notificationIds);
        
        if ($user) {
            $query->where('notifiable_type', get_class($user))
                  ->where('notifiable_id', $user->id);
        }

        return $query->update(['read_at' => now()]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllAsReadForUser($user)
    {
        return static::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->unread()
            ->update(['read_at' => now()]);
    }

    /**
     * Delete old notifications.
     */
    public static function deleteOlderThan($days = 30)
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Get notifications statistics.
     */
    public static function getStatistics($user = null): array
    {
        $query = static::query();
        
        if ($user) {
            $query->where('notifiable_type', get_class($user))
                  ->where('notifiable_id', $user->id);
        }

        $total = $query->count();
        $unread = $query->clone()->unread()->count();
        $today = $query->clone()->whereDate('created_at', today())->count();
        $urgent = $query->clone()->where('priority', 'urgent')->unread()->count();

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $total - $unread,
            'today' => $today,
            'urgent' => $urgent,
            'read_rate' => $total > 0 ? round((($total - $unread) / $total) * 100, 2) : 0,
        ];
    }
}