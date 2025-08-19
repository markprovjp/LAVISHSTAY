<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'title',
        'message_template',
        'priority',
        'icon',
        'color',
        'target_roles',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_roles' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the notifications for the notification type.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user notification settings for the notification type.
     */
    public function userNotificationSettings(): HasMany
    {
        return $this->hasMany(UserNotificationSetting::class);
    }

    /**
     * Scope a query to only include active notification types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to filter by target roles.
     */
    public function scopeForRoles($query, array $roles)
    {
        return $query->where(function ($q) use ($roles) {
            foreach ($roles as $role) {
                $q->orWhereJsonContains('target_roles', $role);
            }
        });
    }

    /**
     * Generate message from template with data.
     */
    public function generateMessage(array $data = []): string
    {
        $message = $this->message_template;

        // Replace placeholders with actual data
        foreach ($data as $key => $value) {
            $placeholder = '{' . $key . '}';
            $message = str_replace($placeholder, $value, $message);
        }

        // Remove any remaining placeholders
        $message = preg_replace('/\{[^}]+\}/', '', $message);

        return trim($message);
    }

    /**
     * Check if notification type can be sent to specific roles.
     */
    public function canSendToRoles(array $userRoles): bool
    {
        // If no target roles specified, can send to anyone
        if (empty($this->target_roles)) {
            return true;
        }

        // Check if user has any of the target roles
        return !empty(array_intersect($userRoles, $this->target_roles));
    }

    /**
     * Get users who can receive this notification type.
     */
    public function getTargetUsers()
    {
        if (empty($this->target_roles)) {
            return User::all();
        }

        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', $this->target_roles);
        })->get();
    }

    /**
     * Get the priority color.
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
     * Get the priority weight for sorting.
     */
    public function getPriorityWeightAttribute(): int
    {
        return match($this->priority) {
            'urgent' => 4,
            'high' => 3,
            'normal' => 2,
            'low' => 1,
            default => 2
        };
    }

    /**
     * Get notification types by priority.
     */
    public static function getByPriority(): array
    {
        return [
            'urgent' => static::where('priority', 'urgent')->active()->get(),
            'high' => static::where('priority', 'high')->active()->get(),
            'normal' => static::where('priority', 'normal')->active()->get(),
            'low' => static::where('priority', 'low')->active()->get(),
        ];
    }

    /**
     * Create default notification types.
     */
    public static function createDefaults(): void
    {
        $defaults = [
            [
                'name' => 'booking_new',
                'title' => 'New Booking Created',
                'message_template' => 'New booking #{booking_id} has been created for room {room_number}',
                'priority' => 'normal',
                'icon' => '📅',
                'color' => '#10B981',
                'target_roles' => ['admin', 'hotel_manager', 'receptionist'],
            ],
            [
                'name' => 'payment_success',
                'title' => 'Payment Successful',
                'message_template' => 'Payment of {amount} has been received for booking #{booking_id}',
                'priority' => 'normal',
                'icon' => '💰',
                'color' => '#10B981',
                'target_roles' => ['admin', 'hotel_manager', 'receptionist'],
            ],
            [
                'name' => 'system_error',
                'title' => 'System Error',
                'message_template' => 'System error occurred: {error_message}',
                'priority' => 'urgent',
                'icon' => '🚨',
                'color' => '#EF4444',
                'target_roles' => ['admin'],
            ],
        ];

        foreach ($defaults as $default) {
            static::updateOrCreate(
                ['name' => $default['name']],
                $default
            );
        }
    }

    /**
     * Get statistics for notification type.
     */
    public function getStatistics(): array
    {
        $total = $this->notifications()->count();
        $unread = $this->notifications()->unread()->count();
        $today = $this->notifications()->whereDate('created_at', today())->count();

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $total - $unread,
            'today' => $today,
            'read_rate' => $total > 0 ? round((($total - $unread) / $total) * 100, 2) : 0,
        ];
    }
}