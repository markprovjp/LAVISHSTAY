<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasProfilePhoto, HasTeams, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'current_team_id',
        'profile_photo_path',
        'identity_code',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'current_team_id' => 'integer',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    

    /**
     * Đường dẫn ảnh đại diện
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }
    /**
     * Quan hệ: user thuộc nhiều role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Lấy danh sách permission từ tất cả vai trò
     */
    public function permissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    }

    /**
     * Kiểm tra user có vai trò cụ thể không
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Kiểm tra user có bất kỳ role nào trong danh sách
     */
    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', (array) $roles)->exists();
    }

    /**
     * Kiểm tra user có permission cụ thể không
     */
    // public function hasPermission($permissionName)
    // {
    //     return $this->permissions()->contains('name', $permissionName);
    // }

    public function hasPermission($permissionName)
{
    foreach ($this->roles as $role) {
        if ($role->permissions->contains('name', $permissionName)) {
            return true;
        }
    }
    return false;
}

    /**
     * Gán vai trò cho user (nếu chưa có)
     */
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Gỡ vai trò ra khỏi user
     */
    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Thay thế toàn bộ vai trò
     */
    public function syncRoles(array $roleIds)
    {
        $this->roles()->sync($roleIds);
    }

    /**
     * Nếu bạn dùng team Jetstream
     */
    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Override notifications table to use user_notifications
     */
    public function notifications()
    {
        return $this->morphMany(UserNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    
   

    /**
     * Get unread notifications for the user
     */
    public function unreadNotifications()
    {
        return $this->morphMany(UserNotification::class, 'notifiable')
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get read notifications for the user
     */
    public function readNotifications()
    {
        return $this->morphMany(UserNotification::class, 'notifiable')
            ->whereNotNull('read_at')
            ->orderBy('read_at', 'desc');
    }

    /**
     * Get user's notification settings
     */
    public function notificationSettings()
    {
        return $this->hasMany(\App\Models\UserNotificationSetting::class);
    }

    /**
     * Mark all notifications as read
     */
    public function markNotificationsAsRead()
    {
        return $this->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Check if user can receive specific notification type
     */
    public function canReceiveNotificationType($notificationType)
    {
        // If notification type has no target roles, everyone can receive it
        if (empty($notificationType->target_roles)) {
            return true;
        }

        // Check if user has any of the target roles
        $userRoles = $this->roles->pluck('name')->toArray();
        return !empty(array_intersect($userRoles, $notificationType->target_roles));
    }

    /**
     * Get notification setting for specific type
     */
    public function getNotificationSetting($notificationTypeId)
    {
        return $this->notificationSettings()
            ->where('notification_type_id', $notificationTypeId)
            ->first();
    }

    /**
     * Check if user has notification type enabled
     */
    public function hasNotificationTypeEnabled($notificationTypeId, $channel = 'push')
    {
        $setting = $this->getNotificationSetting($notificationTypeId);
        
        if (!$setting) {
            return true; // Default to enabled if no setting exists
        }

        if (!$setting->is_enabled) {
            return false;
        }

        return match($channel) {
            'email' => $setting->email_enabled,
            'push' => $setting->push_enabled,
            default => true
        };
    }

    /**
     * Create default notification settings for user
     */
    public function createDefaultNotificationSettings()
    {
        $notificationTypes = \App\Models\NotificationType::where('is_active', true)->get();
        
        foreach ($notificationTypes as $type) {
            if (!$this->canReceiveNotificationType($type)) {
                continue;
            }

            $this->notificationSettings()->firstOrCreate([
                'notification_type_id' => $type->id,
            ], [
                'is_enabled' => true,
                'email_enabled' => in_array($type->priority, ['high', 'urgent']),
                'push_enabled' => true,
            ]);
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Create default notification settings when user is created
        static::created(function ($user) {
            // Delay this to ensure roles are assigned first
            dispatch(function () use ($user) {
                $user->createDefaultNotificationSettings();
            })->afterResponse();
        });
    }

    /**
     * Get user's preferred timezone for notifications
     */
    public function getTimezoneAttribute()
    {
        return $this->attributes['timezone'] ?? config('app.timezone', 'UTC');
    }

    /**
     * Scope to get users who can receive specific notification type
     */
    public function scopeCanReceiveNotificationType($query, $notificationType)
    {
        if (empty($notificationType->target_roles)) {
            return $query; // All users can receive
        }

        return $query->whereHas('roles', function ($roleQuery) use ($notificationType) {
            $roleQuery->whereIn('name', $notificationType->target_roles);
        });
    }

    /**
     * Get user's display name for notifications
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?: $this->email;
    }

    /**
     * Get user's avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Generate avatar using initials or use default
        $initials = collect(explode(' ', $this->name))->map(function ($name) {
            return strtoupper(substr($name, 0, 1));
        })->take(2)->implode('');

        return "https://ui-avatars.com/api/?name={$initials}&background=3B82F6&color=ffffff&size=40";
    }
    
}
