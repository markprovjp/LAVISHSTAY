<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\UserNotificationSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get user's notifications with pagination
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 15);
        $priority = $request->get('priority');
        $unreadOnly = $request->boolean('unread_only', false);

        $query = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->with('notificationType')
            ->latest();

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($unreadOnly) {
            $query->unread();
        }

        $notifications = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'has_more' => $notifications->hasMorePages(),
                ],
                'statistics' => $this->notificationService->getStatistics($user),
            ]
        ]);
    }

    /**
     * Get recent notifications for dropdown (last 10)
     */
    public function recent()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'url' => $notification->url,
                    'priority' => $notification->priority,
                    'created_at' => $notification->created_at->format('M d, Y H:i'),
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'read_at' => $notification->read_at,
                    'is_read' => !is_null($notification->read_at),
                ];
            });

        $unreadCount = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]
        ]);
    }

    /**
     * Mark specific notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark multiple notifications as read
     */
    public function markMultipleAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'string|exists:notifications,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $success = $this->notificationService->markAsRead($request->notification_ids, $user);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notifications marked as read' : 'Failed to mark notifications as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $success = $this->notificationService->markAllAsRead($user);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'All notifications marked as read' : 'Failed to mark all notifications as read'
        ]);
    }

    /**
     * Delete specific notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Get notification statistics
     */
    public function statistics()
    {
        $user = Auth::user();
        $stats = $this->notificationService->getStatistics($user);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Send test notification (Admin only)
     */
    public function sendTest(Request $request)
    {
        // Check if user is admin
        if (!Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|exists:notification_types,name',
            'data' => 'array',
            'user_ids' => 'array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $success = $this->notificationService->sendByType(
            $request->type,
            $request->get('data', []),
            $request->get('user_ids', [])
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Test notification sent' : 'Failed to send test notification'
        ]);
    }

    /**
     * Get user's notification settings
     */
    public function getSettings()
    {
        $user = Auth::user();
        
        $notificationTypes = NotificationType::active()->get();
        $settings = [];

        foreach ($notificationTypes as $type) {
            $userSetting = UserNotificationSetting::where('user_id', $user->id)
                ->where('notification_type_id', $type->id)
                ->first();

            // Create default setting if not exists
            if (!$userSetting) {
                $userSetting = UserNotificationSetting::create([
                    'user_id' => $user->id,
                    'notification_type_id' => $type->id,
                    'is_enabled' => true,
                    'email_enabled' => false,
                    'push_enabled' => true,
                ]);
            }

            $settings[] = [
                'notification_type' => [
                    'id' => $type->id,
                    'name' => $type->name,
                    'title' => $type->title,
                    'priority' => $type->priority,
                    'icon' => $type->icon,
                    'color' => $type->color,
                ],
                'setting' => [
                    'is_enabled' => $userSetting->is_enabled,
                    'email_enabled' => $userSetting->email_enabled,
                    'push_enabled' => $userSetting->push_enabled,
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Update user's notification settings
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.notification_type_id' => 'required|integer|exists:notification_types,id',
            'settings.*.is_enabled' => 'required|boolean',
            'settings.*.email_enabled' => 'required|boolean',
            'settings.*.push_enabled' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        foreach ($request->settings as $settingData) {
            UserNotificationSetting::updateOrCreate(
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

        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated'
        ]);
    }

    /**
     * Get notification types (for admin)
     */
    public function getTypes()
    {
        if (!Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $types = NotificationType::all();

        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }
}