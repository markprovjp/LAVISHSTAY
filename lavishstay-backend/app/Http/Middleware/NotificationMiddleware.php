<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $user = Auth::user();

        // Check specific permissions if provided
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($user, $permission)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient permissions'
                ], 403);
            }
        }

        // Check notification ownership for specific routes
        if ($this->requiresOwnershipCheck($request)) {
            if (!$this->checkNotificationOwnership($request, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found or access denied'
                ], 404);
            }
        }

        return $next($request);
    }

    /**
     * Check if user has specific permission
     */
    protected function hasPermission($user, $permission): bool
    {
        switch ($permission) {
            case 'view-notifications':
                // All authenticated users can view their own notifications
                return true;

            case 'manage-notifications':
                // Only admins can manage notification system
                return $user->hasRole('admin');

            case 'send-notifications':
                // Admins and managers can send notifications
                return $user->hasAnyRole(['admin', 'hotel_manager']);

            case 'view-all-notifications':
                // Only admins can view all users' notifications
                return $user->hasRole('admin');

            case 'notification-settings':
                // All users can manage their own notification settings
                return true;

            case 'notification-types':
                // Only admins can manage notification types
                return $user->hasRole('admin');

            default:
                return false;
        }
    }

    /**
     * Check if the route requires notification ownership verification
     */
    protected function requiresOwnershipCheck(Request $request): bool
    {
        $routeName = $request->route()->getName();
        $method = $request->method();

        // Routes that require ownership check
        $ownershipRoutes = [
            'notifications.show',
            'notifications.update',
            'notifications.destroy',
        ];

        // Check if route has notification ID parameter
        $hasNotificationId = $request->route('notification') || $request->route('id');

        // Check if it's a notification-specific endpoint
        $isNotificationEndpoint = str_contains($request->path(), 'notifications/') && 
                                 preg_match('/\/notifications\/[^\/]+\/(read|update|delete)/', $request->path());

        return $hasNotificationId || $isNotificationEndpoint || in_array($routeName, $ownershipRoutes);
    }

    /**
     * Check if user owns the notification or has permission to access it
     */
    protected function checkNotificationOwnership(Request $request, $user): bool
    {
        // Get notification ID from route parameters
        $notificationId = $request->route('notification') ?? 
                         $request->route('id') ?? 
                         $this->extractNotificationIdFromPath($request->path());

        if (!$notificationId) {
            return true; // No specific notification to check
        }

        // Find the notification
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return false; // Notification doesn't exist
        }

        // Check if user owns the notification
        if ($notification->notifiable_id == $user->id && 
            $notification->notifiable_type == get_class($user)) {
            return true;
        }

        // Check if user has admin privileges
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check if user has permission to view notifications for their managed departments
        if ($this->canAccessDepartmentNotifications($user, $notification)) {
            return true;
        }

        return false;
    }

    /**
     * Extract notification ID from URL path
     */
    protected function extractNotificationIdFromPath(string $path): ?string
    {
        if (preg_match('/\/notifications\/([^\/]+)/', $path, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Check if user can access notifications for their managed departments
     */
    protected function canAccessDepartmentNotifications($user, $notification): bool
    {
        // This is a placeholder for department-based access control
        // Implement based on your business logic
        
        // Example: Hotel managers can see all notifications for their hotel
        if ($user->hasRole('hotel_manager')) {
            return true;
        }

        // Example: Department managers can see notifications for their department staff
        if ($user->hasRole('dept_manager')) {
            // You would need to implement logic to check if the notification
            // belongs to someone in the same department
            return false;
        }

        return false;
    }

    /**
     * Check rate limiting for notification actions
     */
    protected function checkRateLimit(Request $request, $user): bool
    {
        $key = 'notification_actions:' . $user->id;
        $maxAttempts = 100; // Max 100 notification actions per minute
        $decayMinutes = 1;

        // Use Laravel's rate limiter
        $rateLimiter = app('Illuminate\Cache\RateLimiter');

        if ($rateLimiter->tooManyAttempts($key, $maxAttempts)) {
            return false;
        }

        $rateLimiter->hit($key, $decayMinutes * 60);
        return true;
    }

    /**
     * Validate notification data for creation/update
     */
    protected function validateNotificationData(Request $request): bool
    {
        // Basic validation for notification sending
        if ($request->isMethod('POST') && str_contains($request->path(), 'send')) {
            $required = ['type', 'data'];
            
            foreach ($required as $field) {
                if (!$request->has($field)) {
                    return false;
                }
            }

            // Validate notification type exists
            $type = $request->input('type');
            if (!$this->isValidNotificationType($type)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if notification type is valid
     */
    protected function isValidNotificationType(string $type): bool
    {
        $validTypes = [
            'booking_new',
            'booking_cancelled',
            'booking_modified',
            'checkin_reminder',
            'checkout_completed',
            'payment_success',
            'payment_failed',
            'refund_requested',
            'room_maintenance',
            'room_cleaning_urgent',
            'review_new',
            'review_negative',
            'system_error',
            'system_maintenance',
            'staff_shift_reminder',
        ];

        return in_array($type, $validTypes);
    }

    /**
     * Log notification access for audit purposes
     */
    protected function logNotificationAccess(Request $request, $user, $action = 'access'): void
    {
        // Log notification access for security audit
        \Log::info('Notification access', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'action' => $action,
            'path' => $request->path(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}