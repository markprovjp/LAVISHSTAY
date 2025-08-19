<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\UserNotificationSetting;
use App\Notifications\RealTimeNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Send notification by type name to target roles
     */
    public function sendByType(string $typeName, array $data = [], array $specificUserIds = [])
    {
        try {
            $notificationType = NotificationType::where('name', $typeName)
                ->where('is_active', true)
                ->first();

            if (!$notificationType) {
                Log::warning("Notification type '{$typeName}' not found or inactive");
                return false;
            }

            // Get target users
            $targetUsers = $this->getTargetUsers($notificationType, $specificUserIds);

            if ($targetUsers->isEmpty()) {
                Log::info("No target users found for notification type '{$typeName}'");
                return false;
            }

            // Generate message from template
            $message = $notificationType->generateMessage($data);
            $url = $this->generateUrl($typeName, $data);

            // Send to each user
            $sentCount = 0;
            foreach ($targetUsers as $user) {
                if ($this->canUserReceiveNotification($user, $notificationType)) {
                    $this->sendToUser($user, $notificationType, $message, $data, $url);
                    $sentCount++;
                }
            }

            Log::info("Sent notification '{$typeName}' to {$sentCount} users");
            return $sentCount > 0;

        } catch (\Exception $e) {
            Log::error("Error sending notification '{$typeName}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification directly to specific users
     */
    public function sendToUsers(array $userIds, string $title, string $message, array $data = [], string $url = '#')
    {
        try {
            Log::info("NotificationService: Attempting to send notification to users", [
                'user_ids' => $userIds,
                'title' => $title,
                'message' => $message
            ]);

            $users = User::whereIn('id', $userIds)->get();
            
            if ($users->isEmpty()) {
                Log::warning("NotificationService: No users found for IDs", ['user_ids' => $userIds]);
                return false;
            }

            Log::info("NotificationService: Found users", ['count' => $users->count()]);

            $sentCount = 0;

            foreach ($users as $user) {
                try {
                    $this->sendDirectToUser($user, $title, $message, $data, $url);
                    $sentCount++;
                    Log::info("NotificationService: Successfully sent notification to user", ['user_id' => $user->id]);
                } catch (\Exception $e) {
                    Log::error("NotificationService: Failed to send notification to user", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info("NotificationService: Sent direct notification to {$sentCount} users");
            return $sentCount > 0;

        } catch (\Exception $e) {
            Log::error("NotificationService: Error sending direct notification", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send booking related notifications
     */
    public function sendBookingNotification(string $type, array $bookingData)
    {
        $typeMap = [
            'new' => 'booking_new',
            'cancelled' => 'booking_cancelled',
            'modified' => 'booking_modified',
            'checkin_reminder' => 'checkin_reminder',
            'checkout_completed' => 'checkout_completed',
        ];

        $typeName = $typeMap[$type] ?? null;
        if (!$typeName) {
            Log::warning("Invalid booking notification type: {$type}");
            return false;
        }

        return $this->sendByType($typeName, $bookingData);
    }

    /**
     * Send payment related notifications
     */
    public function sendPaymentNotification(string $type, array $paymentData)
    {
        $typeMap = [
            'success' => 'payment_success',
            'failed' => 'payment_failed',
            'refund_requested' => 'refund_requested',
        ];

        $typeName = $typeMap[$type] ?? null;
        if (!$typeName) {
            Log::warning("Invalid payment notification type: {$type}");
            return false;
        }

        return $this->sendByType($typeName, $paymentData);
    }

    /**
     * Send room/housekeeping related notifications
     */
    public function sendRoomNotification(string $type, array $roomData)
    {
        $typeMap = [
            'maintenance' => 'room_maintenance',
            'cleaning_urgent' => 'room_cleaning_urgent',
        ];

        $typeName = $typeMap[$type] ?? null;
        if (!$typeName) {
            Log::warning("Invalid room notification type: {$type}");
            return false;
        }

        return $this->sendByType($typeName, $roomData);
    }

    /**
     * Send review related notifications
     */
    public function sendReviewNotification(string $type, array $reviewData)
    {
        $typeMap = [
            'new' => 'review_new',
            'negative' => 'review_negative',
        ];

        $typeName = $typeMap[$type] ?? null;
        if (!$typeName) {
            Log::warning("Invalid review notification type: {$type}");
            return false;
        }

        return $this->sendByType($typeName, $reviewData);
    }

    /**
     * Send system related notifications
     */
    public function sendSystemNotification(string $type, array $systemData)
    {
        $typeMap = [
            'error' => 'system_error',
            'maintenance' => 'system_maintenance',
        ];

        $typeName = $typeMap[$type] ?? null;
        if (!$typeName) {
            Log::warning("Invalid system notification type: {$type}");
            return false;
        }

        return $this->sendByType($typeName, $systemData);
    }

    /**
     * Get target users for notification type
     */
    private function getTargetUsers(NotificationType $notificationType, array $specificUserIds = []): Collection
    {
        // If specific user IDs provided, use those
        if (!empty($specificUserIds)) {
            return User::whereIn('id', $specificUserIds)->get();
        }

        // Get users by roles
        return User::whereHas('roles', function ($query) use ($notificationType) {
            $query->whereIn('name', $notificationType->target_roles);
        })->get();
    }

    /**
     * Check if user can receive notification based on their settings
     */
    private function canUserReceiveNotification(User $user, NotificationType $notificationType): bool
    {
        // Check if user has settings for this notification type
        $setting = UserNotificationSetting::where('user_id', $user->id)
            ->where('notification_type_id', $notificationType->id)
            ->first();

        // If no setting exists, create default (enabled)
        if (!$setting) {
            $setting = UserNotificationSetting::create([
                'user_id' => $user->id,
                'notification_type_id' => $notificationType->id,
                'is_enabled' => true,
                'email_enabled' => false,
                'push_enabled' => true,
            ]);
        }

        return $setting->canReceive();
    }

    /**
     * Send notification to specific user
     */
    private function sendToUser(User $user, NotificationType $notificationType, string $message, array $data, string $url)
    {
        try {
            // Create notification record
            $notification = Notification::create([
                'notification_type_id' => $notificationType->id,
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'title' => $notificationType->title,
                'message' => $message,
                'data' => $data,
                'priority' => $notificationType->priority,
                'icon' => $notificationType->icon,
                'color' => $notificationType->color,
                'url' => $url,
                'status' => 'sent',
            ]);

            Log::info("Created notification record", ['notification_id' => $notification->id]);

            // Send real-time notification via Laravel Notification (if RealTimeNotification exists)
            if (class_exists(\App\Notifications\RealTimeNotification::class)) {
                try {
                    $user->notify(new RealTimeNotification(
                        $notificationType->title,
                        $message,
                        $notificationType->icon,
                        $url,
                        $notificationType->priority,
                        $notificationType->color,
                        $notification->id
                    ));
                } catch (\Exception $e) {
                    Log::warning("Failed to send real-time notification", ['error' => $e->getMessage()]);
                }
            }

            // Check if user wants email notification
            $setting = UserNotificationSetting::where('user_id', $user->id)
                ->where('notification_type_id', $notificationType->id)
                ->first();

            if ($setting && $setting->wantsEmail()) {
                // TODO: Send email notification
                // $user->notify(new EmailNotification(...));
            }

        } catch (\Exception $e) {
            Log::error("Error in sendToUser", [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Send direct notification to user (without type)
     */
    private function sendDirectToUser(User $user, string $title, string $message, array $data, string $url)
    {
        try {
            Log::info("Creating direct notification for user", [
                'user_id' => $user->id,
                'title' => $title
            ]);

            // Create notification record
            $notification = Notification::create([
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'priority' => 'normal',
                'icon' => '📣',
                'color' => '#3B82F6',
                'url' => $url,
                'status' => 'sent',
            ]);

            Log::info("Successfully created notification", [
                'notification_id' => $notification->id,
                'user_id' => $user->id
            ]);

            // Send real-time notification (if class exists)
            if (class_exists(\App\Notifications\RealTimeNotification::class)) {
                try {
                    $user->notify(new RealTimeNotification(
                        $title,
                        $message,
                        '📣',
                        $url,
                        'normal',
                        '#3B82F6',
                        $notification->id
                    ));
                    Log::info("Sent real-time notification", ['notification_id' => $notification->id]);
                } catch (\Exception $e) {
                    Log::warning("Failed to send real-time notification", [
                        'error' => $e->getMessage(),
                        'notification_id' => $notification->id
                    ]);
                }
            } else {
                Log::info("RealTimeNotification class not found, skipping real-time notification");
            }

        } catch (\Exception $e) {
            Log::error("Error in sendDirectToUser", [
                'user_id' => $user->id,
                'title' => $title,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate URL based on notification type and data
     */
    private function generateUrl(string $typeName, array $data): string
    {
        $urlMap = [
            'booking_new' => '/admin/bookings/' . ($data['booking_id'] ?? ''),
            'booking_cancelled' => '/admin/bookings/' . ($data['booking_id'] ?? ''),
            'booking_modified' => '/admin/bookings/' . ($data['booking_id'] ?? ''),
            'checkin_reminder' => '/admin/bookings/' . ($data['booking_id'] ?? ''),
            'checkout_completed' => '/admin/bookings/' . ($data['booking_id'] ?? ''),
            'payment_success' => '/admin/payments/' . ($data['payment_id'] ?? ''),
            'payment_failed' => '/admin/payments/' . ($data['payment_id'] ?? ''),
            'refund_requested' => '/admin/refunds/' . ($data['refund_id'] ?? ''),
            'room_maintenance' => '/admin/rooms/' . ($data['room_id'] ?? ''),
            'room_cleaning_urgent' => '/admin/rooms/' . ($data['room_id'] ?? ''),
            'review_new' => '/admin/reviews/' . ($data['review_id'] ?? ''),
            'review_negative' => '/admin/reviews/' . ($data['review_id'] ?? ''),
            'system_error' => '/admin/system/logs',
            'system_maintenance' => '/admin/system/maintenance',
            'staff_shift_reminder' => '/admin/staff/schedule',
        ];

        return $urlMap[$typeName] ?? '#';
    }

    /**
     * Get notification statistics for dashboard
     */
    public function getStatistics(User $user = null): array
    {
        $query = Notification::query();
        
        if ($user) {
            $query->where('notifiable_id', $user->id)
                  ->where('notifiable_type', User::class);
        }

        return [
            'total' => $query->count(),
            'unread' => $query->clone()->unread()->count(),
            'today' => $query->clone()->whereDate('created_at', today())->count(),
            'urgent' => $query->clone()->where('priority', 'urgent')->unread()->count(),
            'by_priority' => [
                'low' => $query->clone()->where('priority', 'low')->count(),
                'normal' => $query->clone()->where('priority', 'normal')->count(),
                'high' => $query->clone()->where('priority', 'high')->count(),
                'urgent' => $query->clone()->where('priority', 'urgent')->count(),
            ],
        ];
    }

    /**
     * Mark notifications as read
     */
    public function markAsRead(array $notificationIds, User $user = null): bool
    {
        try {
            $query = Notification::whereIn('id', $notificationIds);
            
            if ($user) {
                $query->where('notifiable_id', $user->id)
                      ->where('notifiable_type', User::class);
            }

            $query->update(['read_at' => now()]);
            return true;

        } catch (\Exception $e) {
            Log::error("Error marking notifications as read: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(User $user): bool
    {
        try {
            Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', User::class)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            
            return true;

        } catch (\Exception $e) {
            Log::error("Error marking all notifications as read: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clean old notifications (older than specified days)
     */
    public function cleanOldNotifications(int $days = 30): int
    {
        try {
            $count = Notification::where('created_at', '<', now()->subDays($days))
                ->delete();
            
            Log::info("Cleaned {$count} old notifications");
            return $count;

        } catch (\Exception $e) {
            Log::error("Error cleaning old notifications: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Debug method to test basic functionality
     */
    public function debugTest(): array
    {
        $results = [];
        
        try {
            // Test database connection
            $results['database'] = [
                'status' => 'success',
                'message' => 'Database connection working',
                'notification_count' => Notification::count(),
                'user_count' => User::count(),
                'notification_type_count' => NotificationType::count()
            ];
        } catch (\Exception $e) {
            $results['database'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        try {
            // Test user retrieval
            $testUser = User::first();
            $results['user_retrieval'] = [
                'status' => 'success',
                'message' => 'Can retrieve users',
                'test_user_id' => $testUser ? $testUser->id : null,
                'test_user_name' => $testUser ? $testUser->name : null
            ];
        } catch (\Exception $e) {
            $results['user_retrieval'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        try {
            // Test notification creation
            $testUser = User::first();
            if ($testUser) {
                $notification = Notification::create([
                    'notifiable_type' => User::class,
                    'notifiable_id' => $testUser->id,
                    'title' => 'Debug Test',
                    'message' => 'This is a debug test notification',
                    'priority' => 'normal',
                    'icon' => '🔧',
                    'color' => '#3B82F6',
                    'url' => '#debug',
                    'status' => 'sent',
                ]);

                $results['notification_creation'] = [
                    'status' => 'success',
                    'message' => 'Can create notifications',
                    'notification_id' => $notification->id
                ];

                // Clean up test notification
                $notification->delete();
            } else {
                $results['notification_creation'] = [
                    'status' => 'error',
                    'message' => 'No test user available'
                ];
            }
        } catch (\Exception $e) {
            $results['notification_creation'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return $results;
    }
}