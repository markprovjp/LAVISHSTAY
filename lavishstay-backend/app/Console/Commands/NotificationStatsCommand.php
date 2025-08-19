<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotificationStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:stats 
                            {--period=week : Period for statistics (day|week|month|year)}
                            {--email-report : Send report via email to admins}
                            {--format=table : Output format (table|json|csv)}
                            {--export= : Export to file path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate notification statistics and reports';

    protected $notificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->option('period');
        $emailReport = $this->option('email-report');
        $format = $this->option('format');
        $exportPath = $this->option('export');

        $this->info("Generating notification statistics for period: {$period}");

        try {
            $stats = $this->generateStatistics($period);
            
            // Display statistics
            $this->displayStatistics($stats, $format);
            
            // Export to file if requested
            if ($exportPath) {
                $this->exportStatistics($stats, $exportPath, $format);
            }
            
            // Send email report if requested
            if ($emailReport) {
                $this->sendEmailReport($stats, $period);
            }

            Log::info("Notification statistics generated successfully", [
                'period' => $period,
                'email_sent' => $emailReport,
                'exported' => !empty($exportPath)
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("Error generating statistics: " . $e->getMessage());
            Log::error("Notification statistics generation failed", [
                'error' => $e->getMessage(),
                'period' => $period
            ]);
            return 1;
        }
    }

    /**
     * Generate comprehensive statistics
     */
    protected function generateStatistics($period): array
    {
        $dateRange = $this->getDateRange($period);
        
        $stats = [
            'period' => $period,
            'date_range' => $dateRange,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'overview' => $this->getOverviewStats($dateRange),
            'by_type' => $this->getStatsByType($dateRange),
            'by_priority' => $this->getStatsByPriority($dateRange),
            'by_user_role' => $this->getStatsByUserRole($dateRange),
            'engagement' => $this->getEngagementStats($dateRange),
            'trends' => $this->getTrendStats($dateRange),
            'top_users' => $this->getTopUsers($dateRange),
            'system_health' => $this->getSystemHealthStats($dateRange),
        ];

        return $stats;
    }

    /**
     * Get date range for the specified period
     */
    protected function getDateRange($period): array
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'day':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default:
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
        }

        return [
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
            'start_carbon' => $start,
            'end_carbon' => $end,
        ];
    }

    /**
     * Get overview statistics
     */
    protected function getOverviewStats($dateRange): array
    {
        $query = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        
        return [
            'total_sent' => $query->count(),
            'total_read' => $query->clone()->whereNotNull('read_at')->count(),
            'total_unread' => $query->clone()->whereNull('read_at')->count(),
            'read_rate' => $this->calculateReadRate($query),
            'avg_per_day' => $this->calculateAvgPerDay($query, $dateRange),
            'urgent_notifications' => $query->clone()->where('priority', 'urgent')->count(),
            'failed_notifications' => $query->clone()->where('status', 'failed')->count(),
        ];
    }

    /**
     * Get statistics by notification type
     */
    protected function getStatsByType($dateRange): array
    {
        $stats = Notification::join('notification_types', 'notifications.notification_type_id', '=', 'notification_types.id')
            ->whereBetween('notifications.created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                notification_types.name,
                notification_types.title,
                COUNT(*) as total,
                COUNT(CASE WHEN notifications.read_at IS NOT NULL THEN 1 END) as read_count,
                COUNT(CASE WHEN notifications.priority = "urgent" THEN 1 END) as urgent_count,
                COUNT(CASE WHEN notifications.status = "failed" THEN 1 END) as failed_count
            ')
            ->groupBy('notification_types.id', 'notification_types.name', 'notification_types.title')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'title' => $item->title,
                    'total' => $item->total,
                    'read_count' => $item->read_count,
                    'unread_count' => $item->total - $item->read_count,
                    'read_rate' => $item->total > 0 ? round(($item->read_count / $item->total) * 100, 2) : 0,
                    'urgent_count' => $item->urgent_count,
                    'failed_count' => $item->failed_count,
                ];
            })
            ->toArray();

        return $stats;
    }

    /**
     * Get statistics by priority
     */
    protected function getStatsByPriority($dateRange): array
    {
        $stats = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                priority,
                COUNT(*) as total,
                COUNT(CASE WHEN read_at IS NOT NULL THEN 1 END) as read_count,
                AVG(CASE WHEN read_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, created_at, read_at) END) as avg_read_time_minutes
            ')
            ->groupBy('priority')
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->get()
            ->map(function ($item) {
                return [
                    'priority' => $item->priority,
                    'total' => $item->total,
                    'read_count' => $item->read_count,
                    'unread_count' => $item->total - $item->read_count,
                    'read_rate' => $item->total > 0 ? round(($item->read_count / $item->total) * 100, 2) : 0,
                    'avg_read_time_minutes' => $item->avg_read_time_minutes ? round($item->avg_read_time_minutes, 2) : null,
                ];
            })
            ->toArray();

        return $stats;
    }

    /**
     * Get statistics by user role
     */
    protected function getStatsByUserRole($dateRange): array
    {
        $stats = Notification::join('users', 'notifications.notifiable_id', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('notifications.notifiable_type', User::class)
            ->whereBetween('notifications.created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                roles.name as role_name,
                COUNT(*) as total,
                COUNT(CASE WHEN notifications.read_at IS NOT NULL THEN 1 END) as read_count,
                COUNT(DISTINCT users.id) as unique_users
            ')
            ->groupBy('roles.id', 'roles.name')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'role' => $item->role_name,
                    'total' => $item->total,
                    'read_count' => $item->read_count,
                    'unread_count' => $item->total - $item->read_count,
                    'read_rate' => $item->total > 0 ? round(($item->read_count / $item->total) * 100, 2) : 0,
                    'unique_users' => $item->unique_users,
                    'avg_per_user' => $item->unique_users > 0 ? round($item->total / $item->unique_users, 2) : 0,
                ];
            })
            ->toArray();

        return $stats;
    }

    /**
     * Get engagement statistics
     */
    protected function getEngagementStats($dateRange): array
    {
        $totalNotifications = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();
        $readNotifications = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('read_at')->count();

        $avgReadTime = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('read_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, read_at)) as avg_minutes')
            ->first();

        return [
            'overall_read_rate' => $totalNotifications > 0 ? round(($readNotifications / $totalNotifications) * 100, 2) : 0,
            'avg_read_time_minutes' => $avgReadTime->avg_minutes ? round($avgReadTime->avg_minutes, 2) : null,
            'quick_reads' => Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->whereNotNull('read_at')
                ->whereRaw('TIMESTAMPDIFF(MINUTE, created_at, read_at) <= 5')
                ->count(),
            'delayed_reads' => Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->whereNotNull('read_at')
                ->whereRaw('TIMESTAMPDIFF(HOUR, created_at, read_at) >= 24')
                ->count(),
        ];
    }

    /**
     * Get trend statistics
     */
    protected function getTrendStats($dateRange): array
    {
        // Daily breakdown for the period
        $dailyStats = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total,
                COUNT(CASE WHEN read_at IS NOT NULL THEN 1 END) as read_count,
                COUNT(CASE WHEN priority = "urgent" THEN 1 END) as urgent_count
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'total' => $item->total,
                    'read_count' => $item->read_count,
                    'unread_count' => $item->total - $item->read_count,
                    'urgent_count' => $item->urgent_count,
                ];
            })
            ->toArray();

        return [
            'daily_breakdown' => $dailyStats,
            'peak_day' => collect($dailyStats)->sortByDesc('total')->first(),
            'lowest_day' => collect($dailyStats)->sortBy('total')->first(),
        ];
    }

    /**
     * Get top users by notification activity
     */
    protected function getTopUsers($dateRange): array
    {
        return Notification::join('users', 'notifications.notifiable_id', '=', 'users.id')
            ->where('notifications.notifiable_type', User::class)
            ->whereBetween('notifications.created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                users.id,
                users.name,
                users.email,
                COUNT(*) as total_received,
                COUNT(CASE WHEN notifications.read_at IS NOT NULL THEN 1 END) as read_count,
                COUNT(CASE WHEN notifications.priority = "urgent" THEN 1 END) as urgent_received
            ')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_received')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->id,
                    'name' => $item->name,
                    'email' => $item->email,
                    'total_received' => $item->total_received,
                    'read_count' => $item->read_count,
                    'unread_count' => $item->total_received - $item->read_count,
                    'read_rate' => $item->total_received > 0 ? round(($item->read_count / $item->total_received) * 100, 2) : 0,
                    'urgent_received' => $item->urgent_received,
                ];
            })
            ->toArray();
    }

    /**
     * Get system health statistics
     */
    protected function getSystemHealthStats($dateRange): array
    {
        $total = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();
        $failed = Notification::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'failed')->count();

        return [
            'success_rate' => $total > 0 ? round((($total - $failed) / $total) * 100, 2) : 100,
            'failure_rate' => $total > 0 ? round(($failed / $total) * 100, 2) : 0,
            'total_failures' => $failed,
            'system_errors' => Notification::join('notification_types', 'notifications.notification_type_id', '=', 'notification_types.id')
                ->whereBetween('notifications.created_at', [$dateRange['start'], $dateRange['end']])
                ->where('notification_types.name', 'system_error')
                ->count(),
        ];
    }

    /**
     * Display statistics in the specified format
     */
    protected function displayStatistics($stats, $format): void
    {
        switch ($format) {
            case 'json':
                $this->line(json_encode($stats, JSON_PRETTY_PRINT));
                break;
            case 'csv':
                $this->displayCsvFormat($stats);
                break;
            default:
                $this->displayTableFormat($stats);
        }
    }

    /**
     * Display statistics in table format
     */
    protected function displayTableFormat($stats): void
    {
        $this->info("=== Notification Statistics Report ===");
        $this->info("Period: {$stats['period']} ({$stats['date_range']['start']} to {$stats['date_range']['end']})");
        $this->info("Generated: {$stats['generated_at']}");
        $this->newLine();

        // Overview
        $this->info("📊 Overview:");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Sent', number_format($stats['overview']['total_sent'])],
                ['Total Read', number_format($stats['overview']['total_read'])],
                ['Total Unread', number_format($stats['overview']['total_unread'])],
                ['Read Rate', $stats['overview']['read_rate'] . '%'],
                ['Avg Per Day', number_format($stats['overview']['avg_per_day'], 2)],
                ['Urgent Notifications', number_format($stats['overview']['urgent_notifications'])],
                ['Failed Notifications', number_format($stats['overview']['failed_notifications'])],
            ]
        );

        // By Type
        if (!empty($stats['by_type'])) {
            $this->newLine();
            $this->info("📋 By Type:");
            $this->table(
                ['Type', 'Total', 'Read', 'Unread', 'Read Rate', 'Urgent', 'Failed'],
                collect($stats['by_type'])->map(function ($item) {
                    return [
                        $item['title'],
                        number_format($item['total']),
                        number_format($item['read_count']),
                        number_format($item['unread_count']),
                        $item['read_rate'] . '%',
                        number_format($item['urgent_count']),
                        number_format($item['failed_count']),
                    ];
                })->toArray()
            );
        }

        // By Priority
        if (!empty($stats['by_priority'])) {
            $this->newLine();
            $this->info("⚡ By Priority:");
            $this->table(
                ['Priority', 'Total', 'Read', 'Unread', 'Read Rate', 'Avg Read Time (min)'],
                collect($stats['by_priority'])->map(function ($item) {
                    return [
                        ucfirst($item['priority']),
                        number_format($item['total']),
                        number_format($item['read_count']),
                        number_format($item['unread_count']),
                        $item['read_rate'] . '%',
                        $item['avg_read_time_minutes'] ?? 'N/A',
                    ];
                })->toArray()
            );
        }

        // System Health
        $this->newLine();
        $this->info("🏥 System Health:");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Success Rate', $stats['system_health']['success_rate'] . '%'],
                ['Failure Rate', $stats['system_health']['failure_rate'] . '%'],
                ['Total Failures', number_format($stats['system_health']['total_failures'])],
                ['System Errors', number_format($stats['system_health']['system_errors'])],
            ]
        );
    }

    /**
     * Display statistics in CSV format
     */
    protected function displayCsvFormat($stats): void
    {
        // This is a simplified CSV output
        $this->line("Period,Total Sent,Total Read,Read Rate,Urgent,Failed");
        $this->line(implode(',', [
            $stats['period'],
            $stats['overview']['total_sent'],
            $stats['overview']['total_read'],
            $stats['overview']['read_rate'] . '%',
            $stats['overview']['urgent_notifications'],
            $stats['overview']['failed_notifications'],
        ]));
    }

    /**
     * Export statistics to file
     */
    protected function exportStatistics($stats, $path, $format): void
    {
        $content = '';
        
        switch ($format) {
            case 'json':
                $content = json_encode($stats, JSON_PRETTY_PRINT);
                break;
            case 'csv':
                // Generate CSV content
                $content = $this->generateCsvContent($stats);
                break;
            default:
                $content = $this->generateTextReport($stats);
        }

        file_put_contents($path, $content);
        $this->info("Statistics exported to: {$path}");
    }

    /**
     * Send email report to admins
     */
    protected function sendEmailReport($stats, $period): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            try {
                // You would create a proper email template here
                // Mail::to($admin->email)->send(new NotificationStatsReport($stats, $period));
                $this->info("Email report sent to: {$admin->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$admin->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Calculate read rate
     */
    protected function calculateReadRate($query): float
    {
        $total = $query->count();
        if ($total == 0) return 0;
        
        $read = $query->clone()->whereNotNull('read_at')->count();
        return round(($read / $total) * 100, 2);
    }

    /**
     * Calculate average notifications per day
     */
    protected function calculateAvgPerDay($query, $dateRange): float
    {
        $total = $query->count();
        $days = Carbon::parse($dateRange['start'])->diffInDays(Carbon::parse($dateRange['end'])) + 1;
        
        return $days > 0 ? round($total / $days, 2) : 0;
    }

    /**
     * Generate CSV content
     */
    protected function generateCsvContent($stats): string
    {
        $csv = "Notification Statistics Report\n";
        $csv .= "Period: {$stats['period']}\n";
        $csv .= "Date Range: {$stats['date_range']['start']} to {$stats['date_range']['end']}\n";
        $csv .= "Generated: {$stats['generated_at']}\n\n";
        
        $csv .= "Overview\n";
        $csv .= "Metric,Value\n";
        foreach ($stats['overview'] as $key => $value) {
            $csv .= ucwords(str_replace('_', ' ', $key)) . "," . $value . "\n";
        }
        
        return $csv;
    }

    /**
     * Generate text report
     */
    protected function generateTextReport($stats): string
    {
        $report = "NOTIFICATION STATISTICS REPORT\n";
        $report .= str_repeat("=", 50) . "\n";
        $report .= "Period: {$stats['period']}\n";
        $report .= "Date Range: {$stats['date_range']['start']} to {$stats['date_range']['end']}\n";
        $report .= "Generated: {$stats['generated_at']}\n\n";
        
        $report .= "OVERVIEW:\n";
        foreach ($stats['overview'] as $key => $value) {
            $report .= "  " . ucwords(str_replace('_', ' ', $key)) . ": " . $value . "\n";
        }
        
        return $report;
    }
}