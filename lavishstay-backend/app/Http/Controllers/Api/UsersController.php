<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Get all users for notification targeting
     */
    public function index(Request $request)
    {
        try {
            $query = User::select('id', 'name', 'email');

            // Filter by role if specified
            if ($request->filled('role')) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }

            // Search by name or email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->orderBy('name')->get();

            return response()->json($users);

        } catch (\Exception $e) {
            Log::error('Error getting users: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể lấy danh sách người dùng'
            ], 500);
        }
    }

    /**
     * Get users by role
     */
    public function byRole($role)
    {
        try {
            $users = User::select('id', 'name', 'email')
                ->whereHas('roles', function ($query) use ($role) {
                    $query->where('name', $role);
                })
                ->orderBy('name')
                ->get();

            return response()->json($users);

        } catch (\Exception $e) {
            Log::error('Error getting users by role: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể lấy danh sách người dùng theo vai trò'
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'users_by_role' => User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->selectRaw('roles.name as role, COUNT(*) as count')
                    ->groupBy('roles.name')
                    ->get(),
                'active_users' => User::where('email_verified_at', '!=', null)->count(),
                'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Error getting user statistics: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể lấy thống kê người dùng'
            ], 500);
        }
    }
}