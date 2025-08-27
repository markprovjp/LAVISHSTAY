<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Send notification by type
     */
    public function sendByType(string $typeName, array $data = [], string $url = '#'): bool
    {
        try {
            Log::info('Sending notification by type', [
                'type' => $typeName,
                'data' => $data
            ]);

            // Get notification type
            $notificationType = NotificationType::where('name', $typeName)
                ->where('is_active', true)
                ->first();

            if (!$notificationType) {
                Log::warning('Notification type not found or inactive', ['type' => $typeName]);
                return false;
            }

            // Get users who should receive this notification type
            $targetUsers = NotificationUser::getUsersForNotificationType($typeName);

            if ($targetUsers->isEmpty()) {
                Log::warning('No target users found for notification type', [
                    'type' => $typeName,
                    'notification_type_id' => $notificationType->id
                ]);
                return false;
            }

            Log::info('Found target users for notification', [
                'type' => $typeName,
                'user_count' => $targetUsers->count(),
                'user_ids' => $targetUsers->pluck('id')->toArray()
            ]);

            // Replace placeholders in message template
            $message = $this->replacePlaceholders($notificationType->message_template, $data);
            $title = $this->replacePlaceholders($notificationType->title, $data);

            // Send to each user
            $sentCount = 0;
            foreach ($targetUsers as $user) {
                if ($this->sendToUser($user, $title, $message, $data, $url, $notificationType)) {
                    $sentCount++;
                }
            }

            Log::info('Notification sent by type', [
                'type' => $typeName,
                'target_users' => $targetUsers->count(),
                'sent_count' => $sentCount
            ]);

            return $sentCount > 0;

        } catch (\Exception $e) {
            Log::error('Error sending notification by type', [
                'type' => $typeName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send notification to specific users
     */
    public function sendToUsers(array $userIds, string $title, string $message, array $data = [], string $url = '#'): bool
    {
        try {
            Log::info('Sending notification to specific users', [
                'user_ids' => $userIds,
                'title' => $title
            ]);

            $users = User::whereIn('id', $userIds)->get();
            
            if ($users->isEmpty()) {
                Log::warning('No users found for notification', ['user_ids' => $userIds]);
                return false;
            }

            $sentCount = 0;
            foreach ($users as $user) {
                if ($this->sendToUser($user, $title, $message, $data, $url)) {
                    $sentCount++;
                }
            }

            Log::info('Notification sent to users', [
                'target_users' => count($userIds),
                'sent_count' => $sentCount
            ]);

            return $sentCount > 0;

        } catch (\Exception $e) {
            Log::error('Error sending notification to users', [
                'user_ids' => $userIds,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send notification to a single user
     */
    public function sendToUser(User $user, string $title, string $message, array $data = [], string $url = '#', NotificationType $notificationType = null): bool
    {
        try {
            $notification = Notification::create([
                'id' => (string) Str::uuid(),
                'notification_type_id' => $notificationType?->id,
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'priority' => $notificationType?->priority ?? 'normal',
                'icon' => $notificationType?->icon ?? '🔔',
                'color' => $notificationType?->color ?? '#3B82F6',
                'url' => $url,
                'status' => 'sent',
            ]);

            Log::info('Notification created for user', [
                'notification_id' => $notification->id,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'title' => $title
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error creating notification for user', [
                'user_id' => $user->id,
                'title' => $title,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send notification to users with specific roles
     */
    public function sendToRoles(array $roles, string $title, string $message, array $data = [], string $url = '#'): bool
    {
        try {
            $users = User::whereIn('role', $roles)->get();
            
            if ($users->isEmpty()) {
                Log::warning('No users found with specified roles', ['roles' => $roles]);
                return false;
            }

            return $this->sendToUsers($users->pluck('id')->toArray(), $title, $message, $data, $url);

        } catch (\Exception $e) {
            Log::error('Error sending notification to roles', [
                'roles' => $roles,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Replace placeholders in message template
     */
    private function replacePlaceholders(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $placeholder = '{' . $key . '}';
            $template = str_replace($placeholder, (string) $value, $template);
        }
        
        return $template;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(string $notificationId): bool
    {
        try {
            $notification = Notification::find($notificationId);
            
            if (!$notification) {
                return false;
            }

            $notification->markAsRead();
            return true;

        } catch (\Exception $e) {
            Log::error('Error marking notification as read', [
                'notification_id' => $notificationId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Mark multiple notifications as read
     */
    public function markMultipleAsRead(array $notificationIds): bool
    {
        try {
            Notification::whereIn('id', $notificationIds)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error marking multiple notifications as read', [
                'notification_ids' => $notificationIds,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Delete notification
     */
    public function deleteNotification(string $notificationId): bool
    {
        try {
            $notification = Notification::find($notificationId);
            
            if (!$notification) {
                return false;
            }

            $notification->delete();
            return true;

        } catch (\Exception $e) {
            Log::error('Error deleting notification', [
                'notification_id' => $notificationId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get notifications for user
     */
    public function getNotificationsForUser(int $userId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $userId)
            ->with('notificationType')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread count for user
     */
    public function getUnreadCountForUser(int $userId): int
    {
        return Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get notification statistics
     */
    public function getStatistics(): array
    {
        $total = Notification::count();
        $unread = Notification::whereNull('read_at')->count();
        $today = Notification::whereDate('created_at', today())->count();
        $thisWeek = Notification::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        $byPriority = Notification::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        $byStatus = Notification::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'total' => $total,
            'unread' => $unread,
            'today' => $today,
            'this_week' => $thisWeek,
            'by_priority' => $byPriority,
            'by_status' => $byStatus,
        ];
    }

    /**
     * Clean up old notifications
     */
    public function cleanup(int $days = 30): int
    {
        try {
            $count = Notification::where('created_at', '<', now()->subDays($days))->count();
            Notification::where('created_at', '<', now()->subDays($days))->delete();
            
            Log::info('Cleaned up old notifications', ['deleted_count' => $count, 'days' => $days]);
            
            return $count;

        } catch (\Exception $e) {
            Log::error('Error cleaning up notifications', [
                'days' => $days,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Setup default notification settings for user
     */
    public function setupDefaultNotificationsForUser(int $userId, array $notificationTypes = []): bool
    {
        try {
            if (empty($notificationTypes)) {
                // Default notification types for all users
                $notificationTypes = [
                    'booking_new',
                    'booking_cancelled',
                    'payment_success',
                    'payment_failed',
                ];
            }

            NotificationUser::bulkEnableNotifications([$userId], $notificationTypes);

            Log::info('Setup default notifications for user', [
                'user_id' => $userId,
                'notification_types' => $notificationTypes
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error setting up default notifications for user', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get users configured for a notification type
     */
    public function getUsersForNotificationType(string $notificationType): \Illuminate\Database\Eloquent\Collection
    {
        return NotificationUser::getUsersForNotificationType($notificationType);
    }
}