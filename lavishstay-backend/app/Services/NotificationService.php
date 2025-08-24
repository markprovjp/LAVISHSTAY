<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\User;
use App\Models\UserNotificationSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Send notification to multiple users
     */
    public function sendToUsers(
        array $userIds, 
        string $title, 
        string $message, 
        array $data = [], 
        string $url = '#',
        string $priority = 'normal',
        string $icon = '🔔',
        string $color = '#3B82F6'
    ): bool {
        try {
            $notifications = [];
            $timestamp = now();

            foreach ($userIds as $userId) {
                $notifications[] = [
                    'id' => (string) Str::uuid(),
                    'notification_type_id' => null, // Custom notifications don't have a type
                    'notifiable_type' => User::class,
                    'notifiable_id' => $userId,
                    'title' => $title,
                    'message' => $message,
                    'data' => json_encode($data),
                    'priority' => $priority,
                    'icon' => $icon,
                    'color' => $color,
                    'url' => $url,
                    'status' => 'sent',
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            // Batch insert for better performance
            $result = DB::table('notifications')->insert($notifications);

            if ($result) {
                Log::info("Sent notifications to " . count($userIds) . " users", [
                    'title' => $title,
                    'user_count' => count($userIds)
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Error sending notifications to users: ' . $e->getMessage(), [
                'user_ids' => $userIds,
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Send notification by type
     */
    public function sendByType(string $typeName, array $data = [], array $userIds = null): bool
    {
        try {
            $notificationType = NotificationType::where('name', $typeName)
                ->where('is_active', true)
                ->first();

            if (!$notificationType) {
                Log::warning("Notification type not found or inactive: {$typeName}");
                return false;
            }

            // Get target users
            if ($userIds === null) {
                $targetUsers = $notificationType->getTargetUsers();
                $userIds = $targetUsers->pluck('id')->toArray();
            }

            if (empty($userIds)) {
                Log::warning("No target users found for notification type: {$typeName}");
                return false;
            }

            // Generate message from template
            $message = $notificationType->generateMessage($data);

            return $this->sendToUsersWithType(
                $userIds,
                $notificationType,
                $notificationType->title,
                $message,
                $data
            );

        } catch (\Exception $e) {
            Log::error('Error sending notification by type: ' . $e->getMessage(), [
                'type' => $typeName,
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * Send notification to users with specific type
     */
    protected function sendToUsersWithType(
        array $userIds,
        NotificationType $notificationType,
        string $title,
        string $message,
        array $data = []
    ): bool {
        try {
            $notifications = [];
            $timestamp = now();

            foreach ($userIds as $userId) {
                // Check user notification settings
                $setting = UserNotificationSetting::where('user_id', $userId)
                    ->where('notification_type_id', $notificationType->id)
                    ->first();

                // Skip if user has disabled this notification type
                if ($setting && !$setting->is_enabled) {
                    continue;
                }

                $notifications[] = [
                    'id' => (string) Str::uuid(),
                    'notification_type_id' => $notificationType->id,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $userId,
                    'title' => $title,
                    'message' => $message,
                    'data' => json_encode($data),
                    'priority' => $notificationType->priority,
                    'icon' => $notificationType->icon,
                    'color' => $notificationType->color,
                    'url' => $data['url'] ?? '#',
                    'status' => 'sent',
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            if (empty($notifications)) {
                Log::info("No notifications to send - all users have disabled this type", [
                    'type' => $notificationType->name
                ]);
                return true;
            }

            // Batch insert
            $result = DB::table('notifications')->insert($notifications);

            if ($result) {
                Log::info("Sent {$notificationType->name} notifications", [
                    'type' => $notificationType->name,
                    'count' => count($notifications)
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Error sending notifications with type: ' . $e->getMessage(), [
                'type' => $notificationType->name,
                'user_ids' => $userIds
            ]);
            return false;
        }
    }

    /**
     * Send booking notification
     */
    public function sendBookingNotification(string $action, array $data): bool
    {
        $typeMap = [
            'new' => 'booking_new',
            'cancelled' => 'booking_cancelled',
            'modified' => 'booking_modified',
        ];

        $typeName = $typeMap[$action] ?? null;
        if (!$typeName) {
            Log::warning("Unknown booking action: {$action}");
            return false;
        }

        return $this->sendByType($typeName, $data);
    }

    /**
     * Send payment notification
     */
    public function sendPaymentNotification(string $status, array $data): bool
    {
        $typeMap = [
            'success' => 'payment_success',
            'failed' => 'payment_failed',
            'refund_requested' => 'refund_requested',
        ];

        $typeName = $typeMap[$status] ?? null;
        if (!$typeName) {
            Log::warning("Unknown payment status: {$status}");
            return false;
        }

        return $this->sendByType($typeName, $data);
    }

    /**
     * Send room notification
     */
    public function sendRoomNotification(string $type, array $data): bool
    {
        $typeMap = [
            'maintenance' => 'room_maintenance',
            'cleaning_urgent' => 'room_cleaning_urgent',
        ];

        $typeName = $typeMap[$type] ?? null;
        if (!$typeName) {
            Log::warning("Unknown room notification type: {$type}");
            return false;
        }

        return $this->sendByType($typeName, $data);
    }

    /**
     * Send review notification
     */
    public function sendReviewNotification(array $data): bool
    {
        $rating = $data['rating'] ?? 5;
        $typeName = $rating <= 2 ? 'review_negative' : 'review_new';

        return $this->sendByType($typeName, $data);
    }

    /**
     * Send system notification
     */
    public function sendSystemNotification(string $type, array $data): bool
    {
        $typeMap = [
            'error' => 'system_error',
            'maintenance' => 'system_maintenance',
        ];

        $typeName = $typeMap[$type] ?? null;
        if (!$typeName) {
            Log::warning("Unknown system notification type: {$type}");
            return false;
        }

        return $this->sendByType($typeName, $data);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(string $notificationId, int $userId): bool
    {
        try {
            $result = Notification::where('id', $notificationId)
                ->where('notifiable_id', $userId)
                ->where('notifiable_type', User::class)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return $result > 0;

        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage(), [
                'notification_id' => $notificationId,
                'user_id' => $userId
            ]);
            return false;
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        try {
            return Notification::where('notifiable_id', $userId)
                ->where('notifiable_type', User::class)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            return 0;
        }
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(int $userId, int $limit = 10, bool $unreadOnly = false)
    {
        try {
            $query = Notification::where('notifiable_id', $userId)
                ->where('notifiable_type', User::class)
                ->with('notificationType');

            if ($unreadOnly) {
                $query->whereNull('read_at');
            }

            return $query->latest()->limit($limit)->get();

        } catch (\Exception $e) {
            Log::error('Error getting user notifications: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            return collect();
        }
    }

    /**
     * Get unread count for user
     */
    public function getUnreadCount(int $userId): int
    {
        try {
            return Notification::where('notifiable_id', $userId)
                ->where('notifiable_type', User::class)
                ->whereNull('read_at')
                ->count();

        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            return 0;
        }
    }

    /**
     * Clean old notifications
     */
    public function cleanOldNotifications(int $days = 30): int
    {
        try {
            $cutoffDate = now()->subDays($days);
            
            return Notification::where('created_at', '<', $cutoffDate)->delete();

        } catch (\Exception $e) {
            Log::error('Error cleaning old notifications: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get notification statistics
     */
    public function getStatistics(): array
    {
        try {
            return [
                'total' => Notification::count(),
                'unread' => Notification::whereNull('read_at')->count(),
                'today' => Notification::whereDate('created_at', today())->count(),
                'this_week' => Notification::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'by_priority' => Notification::selectRaw('priority, COUNT(*) as count')
                    ->groupBy('priority')
                    ->pluck('count', 'priority')
                    ->toArray(),
                'by_status' => Notification::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
            ];

        } catch (\Exception $e) {
            Log::error('Error getting notification statistics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Test notification system
     */
    public function testSystem(): array
    {
        $results = [];

        try {
            // Test database connection
            $results['database'] = DB::connection()->getPdo() ? 'OK' : 'FAILED';

            // Test user retrieval
            $userCount = User::count();
            $results['users'] = $userCount > 0 ? "OK ({$userCount} users)" : 'NO USERS';

            // Test notification types
            $typeCount = NotificationType::active()->count();
            $results['notification_types'] = $typeCount > 0 ? "OK ({$typeCount} types)" : 'NO TYPES';

            // Test notification creation
            $testUser = User::first();
            if ($testUser) {
                $testResult = $this->sendToUsers(
                    [$testUser->id],
                    'System Test',
                    'This is a system test notification',
                    ['test' => true]
                );
                $results['notification_creation'] = $testResult ? 'OK' : 'FAILED';
            } else {
                $results['notification_creation'] = 'NO TEST USER';
            }

        } catch (\Exception $e) {
            $results['error'] = $e->getMessage();
        }

        return $results;
    }
}