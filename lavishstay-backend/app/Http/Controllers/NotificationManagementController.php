<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\User;
use App\Models\UserNotificationSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationManagementController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display notification management dashboard
     */
    public function index()
{
    try {
        $statistics = [
            'total_notifications' => Notification::count(),
            'unread_notifications' => Notification::whereNull('read_at')->count(),
            'today_notifications' => Notification::whereDate('created_at', today())->count(),
            'urgent_notifications' => Notification::where('priority', 'urgent')->whereNull('read_at')->count(),
            'notification_types' => NotificationType::count(),
            'active_types' => NotificationType::active()->count(),
            'total_users' => User::count(),
            'users_with_settings' => UserNotificationSetting::distinct('user_id')->count(),
        ];

        $recentNotifications = Notification::with(['notificationType'])
            ->latest()
            ->limit(10)
            ->get();

        $notificationsByPriority = [
            'urgent' => Notification::where('priority', 'urgent')->count(),
            'high' => Notification::where('priority', 'high')->count(),
            'normal' => Notification::where('priority', 'normal')->count(),
            'low' => Notification::where('priority', 'low')->count(),
        ];

        $notificationsByStatus = [
            'sent' => Notification::where('status', 'sent')->count(),
            'pending' => Notification::where('status', 'pending')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
        ];

        $notificationTypes = NotificationType::withCount('notifications')->get();

        return view('admin.notifications.index', compact(
            'statistics',
            'recentNotifications',
            'notificationsByPriority',
            'notificationsByStatus',
            'notificationTypes'
        ));

    } catch (\Exception $e) {
        Log::error('Error loading notification dashboard: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Không thể tải trang quản lý thông báo');
    }
}

    /**
     * Display all notifications with filtering
     */
    public function notifications(Request $request)
    {
        try {
            $query = Notification::with(['notificationType']);

            // Apply filters
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('type')) {
                $query->whereHas('notificationType', function ($q) use ($request) {
                    $q->where('name', $request->type);
                });
            }

            if ($request->filled('read_status')) {
                if ($request->read_status === 'read') {
                    $query->whereNotNull('read_at');
                } else {
                    $query->whereNull('read_at');
                }
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }

            $notifications = $query->latest()->paginate(20);
            $notificationTypes = NotificationType::active()->get();

            return view('admin.notifications.list', compact('notifications', 'notificationTypes'));

        } catch (\Exception $e) {
            Log::error('Error loading notifications list: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể tải danh sách thông báo');
        }
    }

    /**
     * Display notification types management
     */
    public function types()
    {
        try {
            $notificationTypes = NotificationType::withCount('notifications')->get();
            return view('admin.notifications.types', compact('notificationTypes'));

        } catch (\Exception $e) {
            Log::error('Error loading notification types: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể tải danh sách loại thông báo');
        }
    }

    /**
     * Get single notification type
     */
    public function getType(NotificationType $notificationType)
    {
        try {
            return response()->json($notificationType);
        } catch (\Exception $e) {
            Log::error('Error getting notification type: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin loại thông báo'
            ], 500);
        }
    }

    /**
     * Create new notification type
     */
    public function createType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:notification_types,name|max:255',
            'title' => 'required|string|max:255',
            'message_template' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $notificationType = NotificationType::create([
                'name' => $request->name,
                'title' => $request->title,
                'message_template' => $request->message_template,
                'priority' => $request->priority,
                'icon' => $request->icon ?: '🔔',
                'color' => $request->color ?: '#3B82F6',
                'target_roles' => $request->target_roles ?: [],
                'is_active' => $request->boolean('is_active', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loại thông báo đã được tạo thành công',
                'data' => $notificationType
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating notification type: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo loại thông báo'
            ], 500);
        }
    }

    /**
     * Update notification type
     */
    public function updateType(Request $request, NotificationType $notificationType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:notification_types,name,' . $notificationType->id,
            'title' => 'required|string|max:255',
            'message_template' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $notificationType->update([
                'name' => $request->name,
                'title' => $request->title,
                'message_template' => $request->message_template,
                'priority' => $request->priority,
                'icon' => $request->icon ?: '🔔',
                'color' => $request->color ?: '#3B82F6',
                'target_roles' => $request->target_roles ?: [],
                'is_active' => $request->boolean('is_active', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loại thông báo đã được cập nhật thành công',
                'data' => $notificationType
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating notification type: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật loại thông báo'
            ], 500);
        }
    }

    /**
     * Delete notification type
     */
    public function deleteType(NotificationType $notificationType)
    {
        try {
            // Check if there are notifications using this type
            $notificationCount = $notificationType->notifications()->count();
            
            if ($notificationCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Không thể xóa loại thông báo này. Có {$notificationCount} thông báo đang sử dụng."
                ], 400);
            }

            $notificationType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loại thông báo đã được xóa thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting notification type: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa loại thông báo'
            ], 500);
        }
    }

    /**
     * Send custom notification
     */
    public function sendCustom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
            'url' => 'nullable|string|max:255',
            'target_type' => 'required|in:all,roles,users',
            'target_roles' => 'required_if:target_type,roles|array',
            'target_roles.*' => 'string',
            'target_users' => 'required_if:target_type,users|array',
            'target_users.*' => 'integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $targetUsers = [];

            switch ($request->target_type) {
                case 'all':
                    $targetUsers = User::pluck('id')->toArray();
                    break;
                
                case 'roles':
                    $targetUsers = User::whereHas('roles', function ($query) use ($request) {
                        $query->whereIn('name', $request->target_roles);
                    })->pluck('id')->toArray();
                    break;
                
                case 'users':
                    $targetUsers = $request->target_users;
                    break;
            }

            if (empty($targetUsers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng nào để gửi thông báo'
                ], 400);
            }

            $result = $this->notificationService->sendToUsers(
                $targetUsers,
                $request->title,
                $request->message,
                [
                    'custom' => true,
                    'sent_by' => auth()->id(),
                    'sent_at' => now()->toISOString(),
                    'priority' => $request->priority,
                    'icon' => $request->icon ?: '📢',
                    'color' => $request->color ?: '#3B82F6',
                ],
                $request->url ?: '#',
                $request->priority ?: 'normal',
                $request->icon ?: '📢',
                $request->color ?: '#3B82F6'
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thông báo đã được gửi thành công đến ' . count($targetUsers) . ' người dùng'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể gửi thông báo'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error sending custom notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi thông báo'
            ], 500);
        }
    }

    /**
     * Bulk operations on notifications
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:mark_read,mark_unread,delete',
            'notification_ids' => 'required|array|min:1',
            'notification_ids.*' => 'string|exists:notifications,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $notificationIds = $request->notification_ids;
            $count = 0;

            switch ($request->action) {
                case 'mark_read':
                    $count = Notification::whereIn('id', $notificationIds)
                        ->whereNull('read_at')
                        ->update(['read_at' => now()]);
                    $message = "Đã đánh dấu {$count} thông báo là đã đọc";
                    break;

                case 'mark_unread':
                    $count = Notification::whereIn('id', $notificationIds)
                        ->whereNotNull('read_at')
                        ->update(['read_at' => null]);
                    $message = "Đã đánh dấu {$count} thông báo là chưa đọc";
                    break;

                case 'delete':
                    $count = Notification::whereIn('id', $notificationIds)->delete();
                    $message = "Đã xóa {$count} thông báo";
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể thực hiện thao tác'
            ], 500);
        }
    }

    /**
     * Get notification statistics for charts
     */
    public function statistics(Request $request)
    {
        try {
            $days = $request->get('days', 7);
            $startDate = now()->subDays($days);

            // Notifications per day
            $notificationsPerDay = Notification::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Notifications by priority
            $notificationsByPriority = Notification::selectRaw('priority, COUNT(*) as count')
                ->where('created_at', '>=', $startDate)
                ->groupBy('priority')
                ->get();

            // Notifications by type
            $notificationsByType = Notification::join('notification_types', 'notifications.notification_type_id', '=', 'notification_types.id')
                ->selectRaw('notification_types.title, COUNT(*) as count')
                ->where('notifications.created_at', '>=', $startDate)
                ->groupBy('notification_types.id', 'notification_types.title')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            // Read vs Unread
            $readStats = [
                'read' => Notification::whereNotNull('read_at')->where('created_at', '>=', $startDate)->count(),
                'unread' => Notification::whereNull('read_at')->where('created_at', '>=', $startDate)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'notifications_per_day' => $notificationsPerDay,
                    'notifications_by_priority' => $notificationsByPriority,
                    'notifications_by_type' => $notificationsByType,
                    'read_stats' => $readStats,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting notification statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thống kê'
            ], 500);
        }
    }

    /**
     * Test notification system
     */
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_type' => 'required|in:single,multiple,broadcast',
            'user_id' => 'required_if:test_type,single|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = false;
            $message = '';

            switch ($request->test_type) {
                case 'single':
                    $result = $this->notificationService->sendToUsers(
                        [$request->user_id],
                        'Test Notification',
                        'Đây là thông báo test được gửi lúc ' . now()->format('H:i:s'),
                        ['test' => true],
                        '#test'
                    );
                    $message = 'Thông báo test đã được gửi đến 1 người dùng';
                    break;

                case 'multiple':
                    $userIds = User::limit(3)->pluck('id')->toArray();
                    $result = $this->notificationService->sendToUsers(
                        $userIds,
                        'Multiple Test Notification',
                        'Đây là thông báo test gửi đến nhiều người dùng lúc ' . now()->format('H:i:s'),
                        ['test' => true, 'type' => 'multiple'],
                        '#test-multiple'
                    );
                    $message = 'Thông báo test đã được gửi đến nhiều người dùng';
                    break;

                case 'broadcast':
                    $userIds = User::pluck('id')->toArray();
                    $result = $this->notificationService->sendToUsers(
                        $userIds,
                        'Broadcast Test Notification',
                        'Đây là thông báo test broadcast được gửi lúc ' . now()->format('H:i:s'),
                        ['test' => true, 'type' => 'broadcast'],
                        '#test-broadcast'
                    );
                    $message = 'Thông báo test đã được broadcast đến tất cả người dùng';
                    break;
            }

            return response()->json([
                'success' => $result,
                'message' => $result ? $message : 'Test thông báo thất bại'
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending test notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi thông báo test'
            ], 500);
        }
    }

    /**
     * Clean old notifications
     */
    public function cleanup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min:1|max:365',
            'confirm' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $days = $request->days;
            $cutoffDate = now()->subDays($days);
            
            $count = Notification::where('created_at', '<', $cutoffDate)->delete();

            return response()->json([
                'success' => true,
                'message' => "Đã dọn dẹp {$count} thông báo cũ hơn {$days} ngày",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error cleaning up notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể dọn dẹp thông báo'
            ], 500);
        }
    }
}