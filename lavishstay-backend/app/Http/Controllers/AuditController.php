<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    /**
     * Display audit logs with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_range')) {
            $this->applyDateRangeFilter($query, $request->date_range);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('changes_summary', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Get statistics
        $statistics = $this->getStatistics($request);

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        // Get model tabs configuration
        $modelTabs = $this->getModelTabs();

        // Paginate results
        $auditLogs = $query->paginate(20)->withQueryString();

        return view('admin.audit.index', compact(
            'auditLogs',
            'statistics',
            'filterOptions',
            'modelTabs'
        ));
    }

    /**
     * Show detailed audit log entry (supports both web and AJAX requests)
     */
    public function show(AuditLog $auditLog, Request $request)
    {
        $auditLog->load('user');
        
        // Get related audit logs for the same model/record
        $relatedLogs = AuditLog::where('model', $auditLog->model)
            ->where('model_id', $auditLog->model_id)
            ->where('audit_id', '!=', $auditLog->audit_id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Format data for response
        $auditData = [
            'audit_id' => $auditLog->audit_id,
            'action' => $auditLog->action,
            'formatted_action' => $auditLog->getFormattedAction(),
            'action_color' => $auditLog->getActionColor(),
            'model' => $auditLog->model,
            'formatted_model' => $auditLog->getFormattedModel(),
            'model_id' => $auditLog->model_id,
            'old_values' => $auditLog->old_values,
            'new_values' => $auditLog->new_values,
            'changes_summary' => $auditLog->changes_summary,
            'description' => $auditLog->description,
            'ip_address' => $auditLog->ip_address,
            'user_agent' => $auditLog->user_agent,
            'url' => $auditLog->url,
            'method' => $auditLog->method,
            'session_id' => $auditLog->session_id,
            'is_sensitive' => $auditLog->is_sensitive,
            'created_at' => $auditLog->created_at->toISOString(),
            'user' => $auditLog->user ? [
                'id' => $auditLog->user->id,
                'name' => $auditLog->user->name,
                'email' => $auditLog->user->email,
            ] : null,
        ];

        $relatedLogsData = $relatedLogs->map(function ($log) {
            return [
                'audit_id' => $log->audit_id,
                'action' => $log->action,
                'formatted_action' => $log->getFormattedAction(),
                'action_color' => $log->getActionColor(),
                'created_at' => $log->created_at->toISOString(),
                'user' => $log->user ? $log->user->name : 'System',
            ];
        });

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'audit' => $auditData,
                'related_logs' => $relatedLogsData,
            ]);
        }

        // Return view for regular requests (fallback)
        return view('admin.audit.show', compact('auditLog', 'relatedLogs'));
    }

    /**
     * Compare two audit log entries
     */
    public function compare(Request $request)
    {
        $request->validate([
            'from' => 'required|exists:audit_logs,audit_id',
            'to' => 'required|exists:audit_logs,audit_id'
        ]);

        $fromLog = AuditLog::with('user')->findOrFail($request->from);
        $toLogs = AuditLog::with('user')->findOrFail($request->to);

        // Ensure both logs are for the same model/record
        if ($fromLog->model !== $toLogs->model || $fromLog->model_id !== $toLogs->model_id) {
            return redirect()->back()->with('error', 'Không thể so sánh các bản ghi khác nhau');
        }

        $comparison = $this->generateComparison($fromLog, $toLogs);

        return view('admin.audit.compare', compact('fromLog', 'toLogs', 'comparison'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);
        }

        $auditLogs = $query->limit(10000)->get(); // Limit for performance

        switch ($request->format) {
            case 'csv':
                return $this->exportToCsv($auditLogs);
            case 'excel':
                return $this->exportToExcel($auditLogs);
            case 'pdf':
                return $this->exportToPdf($auditLogs);
        }
    }

    /**
     * Get audit statistics for dashboard
     */
    public function statistics(Request $request): JsonResponse
    {
        $statistics = $this->getStatistics($request);
        return response()->json($statistics);
    }

    /**
     * Restore a deleted record from audit log
     */
    public function restore(AuditLog $auditLog)
    {
        if ($auditLog->action !== 'delete') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể khôi phục các bản ghi đã bị xóa'
            ]);
        }

        try {
            $modelClass = "App\\Models\\{$auditLog->model}";
            
            if (!class_exists($modelClass)) {
                throw new \Exception("Model {$auditLog->model} không tồn tại");
            }

            // Create new record with old values
            $restoredRecord = $modelClass::create($auditLog->old_values);

            // Log the restore action
            AuditLog::createLog([
                'action' => 'restore',
                'model' => $auditLog->model,
                'model_id' => $restoredRecord->getKey(),
                'old_values' => null,
                'new_values' => $auditLog->old_values,
                'description' => "Khôi phục bản ghi từ audit log #{$auditLog->audit_id}",
                'metadata' => [
                    'restored_from_audit_id' => $auditLog->audit_id,
                    'original_deleted_at' => $auditLog->created_at
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Khôi phục bản ghi thành công',
                'restored_id' => $restoredRecord->getKey()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi khôi phục: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Clean up old audit logs
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:3650' // 30 days to 10 years
        ]);

        $cutoffDate = Carbon::now()->subDays($request->days);
        
        $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        return response()->json([
            'success' => true,
            'message' => "Đã xóa {$deletedCount} bản ghi audit log cũ",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Get model tabs configuration for filtering
     */
    protected function getModelTabs()
    {
        // Get unique models from audit logs and create display names
        $models = AuditLog::distinct('model')
            ->pluck('model')
            ->filter()
            ->sort();

        $modelTabs = [];
        
        foreach ($models as $model) {
            // Create human-readable display names
            $displayName = $this->getModelDisplayName($model);
            $modelTabs[$model] = $displayName;
        }

        return $modelTabs;
    }

    /**
     * Get human-readable display name for model
     */
    protected function getModelDisplayName($model)
    {
        $displayNames = [
            'User' => 'Người dùng',
            'Hotel' => 'Khách sạn',
            'Room' => 'Phòng',
            'Booking' => 'Đặt phòng',
            'Payment' => 'Thanh toán',
            'Review' => 'Đánh giá',
            'Setting' => 'Cài đặt',
            'Category' => 'Danh mục',
            'Amenity' => 'Tiện ích',
            'Location' => 'Địa điểm',
            'Promotion' => 'Khuyến mãi',
            'Coupon' => 'Mã giảm giá',
            'RoomType' => 'Loại phòng',
            'HotelImage' => 'Hình ảnh khách sạn',
            'RoomImage' => 'Hình ảnh phòng',
            'Notification' => 'Thông báo',
            'Report' => 'Báo cáo',
            'Admin' => 'Quản trị viên',
            'Role' => 'Vai trò',
            'Permission' => 'Quyền hạn',
        ];

        return $displayNames[$model] ?? $model;
    }

    /**
     * Apply date range filter to query
     */
    protected function applyDateRangeFilter($query, $dateRange)
    {
        $now = Carbon::now();
        
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created_at', $now->subDay()->toDateString());
                break;
            case 'last_7_days':
                $query->where('created_at', '>=', $now->subDays(7));
                break;
            case 'last_30_days':
                $query->where('created_at', '>=', $now->subDays(30));
                break;
            case 'this_month':
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereMonth('created_at', $lastMonth->month)
                      ->whereYear('created_at', $lastMonth->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', $now->year);
                break;
        }
    }

    /**
     * Get statistics for dashboard
     */
    protected function getStatistics($request = null)
    {
        $now = Carbon::now();
        
        return [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', $now->toDateString())->count(),
            'unique_users' => AuditLog::distinct('user_id')->whereNotNull('user_id')->count(),
            'recent_critical' => AuditLog::whereIn('action', ['delete', 'bulk_delete'])
                ->where('created_at', '>=', $now->subDay())
                ->count(),
        ];
    }

    /**
     * Get filter options for dropdowns
     */
    protected function getFilterOptions()
    {
        return [
            'users' => User::select('id', 'name', 'email')
                ->whereHas('auditLogs')
                ->orderBy('name')
                ->get(),
            'models' => AuditLog::distinct('model')
                ->pluck('model')
                ->sort()
                ->values(),
            'actions' => AuditLog::distinct('action')
                ->pluck('action')
                ->sort()
                ->values(),
        ];
    }

    /**
     * Generate comparison between two audit logs
     */
    protected function generateComparison($fromLog, $toLog)
    {
        $fromValues = $fromLog->new_values ?? [];
        $toValues = $toLog->new_values ?? [];
        
        $comparison = [
            'added' => [],
            'removed' => [],
            'changed' => []
        ];
        
        // Find added fields
        foreach ($toValues as $key => $value) {
            if (!array_key_exists($key, $fromValues)) {
                $comparison['added'][$key] = $value;
            }
        }
        
        // Find removed fields
        foreach ($fromValues as $key => $value) {
            if (!array_key_exists($key, $toValues)) {
                $comparison['removed'][$key] = $value;
            }
        }
        
        // Find changed fields
        foreach ($fromValues as $key => $fromValue) {
            if (array_key_exists($key, $toValues) && $fromValue !== $toValues[$key]) {
                $comparison['changed'][$key] = [
                    'from' => $fromValue,
                    'to' => $toValues[$key]
                ];
            }
        }
        
        return $comparison;
    }

    /**
     * Export audit logs to CSV
     */
    protected function exportToCsv($auditLogs)
    {
        $filename = 'audit_logs_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Hành động',
                'Model',
                'Model ID',
                'Người dùng',
                'Email',
                'Mô tả',
                'IP Address',
                'Thời gian'
            ]);

            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->audit_id,
                    $log->getFormattedAction(),
                    $log->getFormattedModel(),
                    $log->model_id,
                    $log->user ? $log->user->name : 'System',
                    $log->user ? $log->user->email : '',
                    $log->description,
                    $log->ip_address,
                    $log->created_at->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export audit logs to Excel (simplified version)
     */
    protected function exportToExcel($auditLogs)
    {
        // For now, return CSV with Excel headers
        // You can integrate with Laravel Excel package later
        $filename = 'audit_logs_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        // For simplicity, return CSV format
        return $this->exportToCsv($auditLogs);
    }

    /**
     * Export audit logs to PDF (simplified version)
     */
    protected function exportToPdf($auditLogs)
    {
        // For now, return a simple text format
        // You can integrate with DomPDF or similar later
        $filename = 'audit_logs_' . Carbon::now()->format('Y-m-d_H-i-s') . '.txt';
        
        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $content = "AUDIT LOG REPORT\n";
        $content .= "Generated: " . Carbon::now()->format('d/m/Y H:i:s') . "\n";
        $content .= str_repeat("=", 80) . "\n\n";

        foreach ($auditLogs as $log) {
            $content .= "ID: {$log->audit_id}\n";
            $content .= "Action: {$log->getFormattedAction()}\n";
            $content .= "Model: {$log->getFormattedModel()} (ID: {$log->model_id})\n";
            $content .= "User: " . ($log->user ? $log->user->name : 'System') . "\n";
            $content .= "Time: {$log->created_at->format('d/m/Y H:i:s')}\n";
            $content .= "Description: {$log->description}\n";
            $content .= "IP: {$log->ip_address}\n";
            $content .= str_repeat("-", 40) . "\n";
        }

        return response($content, 200, $headers);
    }
}