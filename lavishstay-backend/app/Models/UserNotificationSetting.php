<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'notification_type_id',
        'is_enabled',
        'email_enabled',
        'push_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns the notification setting.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the notification type that owns the setting.
     */
    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(NotificationType::class);
    }

    /**
     * Scope a query to only include enabled settings.
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope a query to only include email enabled settings.
     */
    public function scopeEmailEnabled($query)
    {
        return $query->where('email_enabled', true);
    }

    /**
     * Scope a query to only include push enabled settings.
     */
    public function scopePushEnabled($query)
    {
        return $query->where('push_enabled', true);
    }

    /**
     * Check if user can receive notifications for this type.
     */
    public function canReceive(): bool
    {
        return $this->is_enabled;
    }

    /**
     * Check if user wants email notifications for this type.
     */
    public function wantsEmail(): bool
    {
        return $this->is_enabled && $this->email_enabled;
    }

    /**
     * Check if user wants push notifications for this type.
     */
    public function wantsPush(): bool
    {
        return $this->is_enabled && $this->push_enabled;
    }

    /**
     * Enable all notification channels for this setting.
     */
    public function enableAll(): void
    {
        $this->update([
            'is_enabled' => true,
            'email_enabled' => true,
            'push_enabled' => true,
        ]);
    }

    /**
     * Disable all notification channels for this setting.
     */
    public function disableAll(): void
    {
        $this->update([
            'is_enabled' => false,
            'email_enabled' => false,
            'push_enabled' => false,
        ]);
    }

    /**
     * Create default settings for a user.
     */
    public static function createDefaultsForUser(User $user): void
    {
        $notificationTypes = NotificationType::active()->get();
        
        foreach ($notificationTypes as $type) {
            // Check if user can receive this notification type based on roles
            if (!$user->canReceiveNotificationType($type)) {
                continue;
            }

            static::firstOrCreate([
                'user_id' => $user->id,
                'notification_type_id' => $type->id,
            ], [
                'is_enabled' => true,
                'email_enabled' => in_array($type->priority, ['high', 'urgent']),
                'push_enabled' => true,
            ]);
        }
    }

    /**
     * Get settings for a user as an array.
     */
    public static function getForUser(User $user): array
    {
        return static::where('user_id', $user->id)
            ->with('notificationType')
            ->get()
            ->map(function ($setting) {
                return [
                    'notification_type' => [
                        'id' => $setting->notificationType->id,
                        'name' => $setting->notificationType->name,
                        'title' => $setting->notificationType->title,
                        'priority' => $setting->notificationType->priority,
                        'icon' => $setting->notificationType->icon,
                        'color' => $setting->notificationType->color,
                    ],
                    'setting' => [
                        'is_enabled' => $setting->is_enabled,
                        'email_enabled' => $setting->email_enabled,
                        'push_enabled' => $setting->push_enabled,
                    ]
                ];
            })
            ->toArray();
    }

    /**
     * Update settings for a user.
     */
    public static function updateForUser(User $user, array $settings): void
    {
        foreach ($settings as $settingData) {
            static::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'notification_type_id' => $settingData['notification_type_id'],
                ],
                [
                    'is_enabled' => $settingData['is_enabled'],
                    'email_enabled' => $settingData['email_enabled'],
                    'push_enabled' => $settingData['push_enabled'],
                ]
            );
        }
    }

    /**
     * Get statistics for user notification settings.
     */
    public static function getStatistics(): array
    {
        $total = static::count();
        $enabled = static::enabled()->count();
        $emailEnabled = static::emailEnabled()->count();
        $pushEnabled = static::pushEnabled()->count();

        return [
            'total' => $total,
            'enabled' => $enabled,
            'disabled' => $total - $enabled,
            'email_enabled' => $emailEnabled,
            'push_enabled' => $pushEnabled,
            'enabled_rate' => $total > 0 ? round(($enabled / $total) * 100, 2) : 0,
        ];
    }
}