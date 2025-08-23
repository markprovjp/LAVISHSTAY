<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get paginated notifications for authenticated user
     */
    public function index(Request $request)
    {
        try {
            // Debug incoming auth for troubleshooting
            // Log::debug('NotificationController@index incoming auth', [
            //     'auth_header' => $request->header('Authorization'),
            //     'bearer' => $request->bearerToken(),
            //     'cookies' => $request->cookies->all(),
            // ]);
            $user = Auth::user();
            // Log::debug('NotificationController@index Auth::user()', ['user' => $user ? $user->id : null]);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);

            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
                'meta' => [
                    'total' => $notifications->total(),
                    'page' => $notifications->currentPage(),
                    'per_page' => $notifications->perPage(),
                    'unread_count' => $unreadCount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        try {
            // Debug log để test
            // \Log::info('NotificationController@unreadCount called at ' . now());
            
            // Debug incoming auth for troubleshooting
            // Log::debug('NotificationController@unreadCount incoming auth', [
            //     'auth_header' => request()->header('Authorization'),
            //     'bearer' => request()->bearerToken(),
            //     'cookies' => request()->cookies->all(),
            // ]);
            $user = Auth::user();
            // Log::debug('NotificationController@unreadCount Auth::user()', ['user' => $user ? $user->id : null]);
            if (!$user) {
                // \Log::warning('NotificationController@unreadCount: No authenticated user');
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch unread count',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $notification = $user->notifications()->where('id', $id)->first();
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->markAsRead();

            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
