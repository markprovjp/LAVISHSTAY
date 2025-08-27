<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationUser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'notification_type',
        'is_active',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification setting.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active notification settings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by notification type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Get users who should receive a specific notification type.
     */
    public static function getUsersForNotificationType($notificationType)
    {
        return static::with('user')
            ->active()
            ->ofType($notificationType)
            ->get()
            ->pluck('user')
            ->filter(); // Remove null users
    }

    /**
     * Check if a user should receive a specific notification type.
     */
    public static function shouldUserReceiveNotification($userId, $notificationType)
    {
        return static::where('user_id', $userId)
            ->where('notification_type', $notificationType)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Enable notification for a user and type.
     */
    public static function enableNotification($userId, $notificationType, $settings = [])
    {
        return static::updateOrCreate(
            [
                'user_id' => $userId,
                'notification_type' => $notificationType,
            ],
            [
                'is_active' => true,
                'settings' => $settings,
            ]
        );
    }

    /**
     * Disable notification for a user and type.
     */
    public static function disableNotification($userId, $notificationType)
    {
        return static::where('user_id', $userId)
            ->where('notification_type', $notificationType)
            ->update(['is_active' => false]);
    }

    /**
     * Get all notification types for a user.
     */
    public static function getUserNotificationTypes($userId)
    {
        return static::where('user_id', $userId)
            ->active()
            ->pluck('notification_type')
            ->toArray();
    }

    /**
     * Bulk enable notifications for multiple users.
     */
    public static function bulkEnableNotifications($userIds, $notificationTypes)
    {
        $data = [];
        $now = now();

        foreach ($userIds as $userId) {
            foreach ($notificationTypes as $type) {
                $data[] = [
                    'user_id' => $userId,
                    'notification_type' => $type,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Use upsert to handle duplicates
        return static::upsert(
            $data,
            ['user_id', 'notification_type'],
            ['is_active', 'updated_at']
        );
    }

    /**
     * Get notification statistics.
     */
    public static function getStatistics()
    {
        $total = static::count();
        $active = static::active()->count();
        $byType = static::active()
            ->selectRaw('notification_type, COUNT(*) as count')
            ->groupBy('notification_type')
            ->pluck('count', 'notification_type')
            ->toArray();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'by_type' => $byType,
        ];
    }
}