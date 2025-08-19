<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\User;
use App\Services\NotificationService;
use App\Helpers\NotificationHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationHealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:health-check 
                            {--alert-threshold=10 : Failure rate threshold to trigger alerts (percentage)}
                            {--check-period=60 : Period to check in minutes}
                            {--send-alerts : Send alerts if issues are detected}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor notification system health and detect issues';

    protected $notificationService;
    protected $healthIssues = [];

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
        $alertThreshold = (float) $this->option('alert-threshold');
        $checkPeriod = (int) $this->option('check-period');
        $sendAlerts = $this->option('send-alerts');

        $this->info("Starting notification system health check...");
        $this->info("Alert threshold: {$alertThreshold}% failure rate");
        $this->info("Check period: {$checkPeriod} minutes");

        try {
            $healthReport = $this->performHealthCheck($checkPeriod, $alertThreshold);
            
            $this->displayHealthReport($healthReport);
            
            if ($sendAlerts && !empty($this->healthIssues)) {
                $this->sendHealthAlerts($healthReport);
            }

            // Cache health status
            Cache::put('notification_system_health', $healthReport, now()->addMinutes(30));

            $overallStatus = empty($this->healthIssues) ? 'HEALTHY' : 'ISSUES_DETECTED';
            $this->info("Health check completed. Status: {$overallStatus}");

            Log::info("Notification health check completed", [
                'status' => $overallStatus,
                'issues_count' => count($this->healthIssues),
                'check_period' => $checkPeriod
            ]);

            return empty($this->healthIssues) ? 0 : 1;

        } catch (\Exception $e) {
            $this->error("Health check failed: " . $e->getMessage());
            Log::error("Notification health check failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 2;
        }
    }

    /**
     * Perform comprehensive health check
     */
    protected function performHealthCheck($checkPeriod, $alertThreshold): array
    {
        $checkStart = Carbon::now()->subMinutes($checkPeriod);
        $checkEnd = Carbon::now();

        $healthReport = [
            'timestamp' => $checkEnd->toDateTimeString(),
            'check_period_minutes' => $checkPeriod,
            'alert_threshold' => $alertThreshold,
            'overall_status' => 'HEALTHY',
            'checks' => [
                'database_connectivity' => $this->checkDatabaseConnectivity(),
                'notification_delivery' => $this->checkNotificationDelivery($checkStart, $checkEnd, $alertThreshold),
                'queue_health' => $this->checkQueueHealth(),
                'pusher_connectivity' => $this->checkPusherConnectivity(),
                'notification_types' => $this->checkNotificationTypes(),
                'user_settings' => $this->checkUserSettings(),
                'system_resources' => $this->checkSystemResources(),
                'recent_errors' => $this->checkRecentErrors($checkStart, $checkEnd),
            ],
            'issues' => [],
            'recommendations' => []
        ];

        // Determine overall status
        $failedChecks = collect($healthReport['checks'])->filter(function ($check) {
            return $check['status'] !== 'PASS';
        });

        if ($failedChecks->isNotEmpty()) {
            $healthReport['overall_status'] = 'ISSUES_DETECTED';
            $healthReport['issues'] = $failedChecks->keys()->toArray();
        }

        return $healthReport;
    }

    /**
     * Check database connectivity
     */
    protected function checkDatabaseConnectivity(): array
    {
        try {
            $start = microtime(true);
            $count = Notification::count();
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            if ($responseTime > 1000) { // More than 1 second
                $this->healthIssues[] = 'Slow database response time';
                return [
                    'status' => 'WARNING',
                    'message' => 'Database response time is slow',
                    'response_time_ms' => $responseTime,
                    'total_notifications' => $count
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'Database connectivity is healthy',
                'response_time_ms' => $responseTime,
                'total_notifications' => $count
            ];

        } catch (\Exception $e) {
            $this->healthIssues[] = 'Database connectivity failed';
            return [
                'status' => 'FAIL',
                'message' => 'Database connectivity failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check notification delivery rates
     */
    protected function checkNotificationDelivery($start, $end, $threshold): array
    {
        try {
            $totalSent = Notification::whereBetween('created_at', [$start, $end])->count();
            $failed = Notification::whereBetween('created_at', [$start, $end])
                ->where('status', 'failed')->count();

            $failureRate = $totalSent > 0 ? round(($failed / $totalSent) * 100, 2) : 0;

            if ($failureRate > $threshold) {
                $this->healthIssues[] = 'High notification failure rate';
                return [
                    'status' => 'FAIL',
                    'message' => "Failure rate ({$failureRate}%) exceeds threshold ({$threshold}%)",
                    'total_sent' => $totalSent,
                    'failed' => $failed,
                    'failure_rate' => $failureRate
                ];
            }

            if ($failureRate > ($threshold / 2)) {
                return [
                    'status' => 'WARNING',
                    'message' => "Failure rate ({$failureRate}%) is elevated",
                    'total_sent' => $totalSent,
                    'failed' => $failed,
                    'failure_rate' => $failureRate
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'Notification delivery is healthy',
                'total_sent' => $totalSent,
                'failed' => $failed,
                'failure_rate' => $failureRate
            ];

        } catch (\Exception $e) {
            $this->healthIssues[] = 'Unable to check delivery rates';
            return [
                'status' => 'FAIL',
                'message' => 'Unable to check delivery rates: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check queue health
     */
    protected function checkQueueHealth(): array
    {
        try {
            // This is a basic check - you might want to implement more sophisticated queue monitoring
            $queueSize = Cache::get('queue_size', 0);
            
            if ($queueSize > 1000) {
                $this->healthIssues[] = 'Large queue backlog';
                return [
                    'status' => 'WARNING',
                    'message' => 'Queue has large backlog',
                    'queue_size' => $queueSize
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'Queue is healthy',
                'queue_size' => $queueSize
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'WARNING',
                'message' => 'Unable to check queue health: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check Pusher connectivity
     */
    protected function checkPusherConnectivity(): array
    {
        try {
            // Basic check - in production you might want to actually test Pusher connection
            $pusherKey = config('broadcasting.connections.pusher.key');
            $pusherSecret = config('broadcasting.connections.pusher.secret');
            $pusherAppId = config('broadcasting.connections.pusher.app_id');

            if (empty($pusherKey) || empty($pusherSecret) || empty($pusherAppId)) {
                $this->healthIssues[] = 'Pusher configuration incomplete';
                return [
                    'status' => 'FAIL',
                    'message' => 'Pusher configuration is incomplete',
                    'has_key' => !empty($pusherKey),
                    'has_secret' => !empty($pusherSecret),
                    'has_app_id' => !empty($pusherAppId)
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'Pusher configuration is complete',
                'configured' => true
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'WARNING',
                'message' => 'Unable to check Pusher connectivity: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check notification types configuration
     */
    protected function checkNotificationTypes(): array
    {
        try {
            $totalTypes = NotificationType::count();
            $activeTypes = NotificationType::where('is_active', true)->count();
            $inactiveTypes = $totalTypes - $activeTypes;

            if ($activeTypes === 0) {
                $this->healthIssues[] = 'No active notification types';
                return [
                    'status' => 'FAIL',
                    'message' => 'No active notification types found',
                    'total_types' => $totalTypes,
                    'active_types' => $activeTypes
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'Notification types are configured',
                'total_types' => $totalTypes,
                'active_types' => $activeTypes,
                'inactive_types' => $inactiveTypes
            ];

        } catch (\Exception $e) {
            $this->healthIssues[] = 'Unable to check notification types';
            return [
                'status' => 'FAIL',
                'message' => 'Unable to check notification types: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check user notification settings
     */
    protected function checkUserSettings(): array
    {
        try {
            $totalUsers = User::count();
            $usersWithSettings = User::whereHas('notificationSettings')->count();
            $usersWithoutSettings = $totalUsers - $usersWithSettings;

            $coveragePercentage = $totalUsers > 0 ? round(($usersWithSettings / $totalUsers) * 100, 2) : 0;

            if ($coveragePercentage < 50) {
                return [
                    'status' => 'WARNING',
                    'message' => 'Low notification settings coverage',
                    'total_users' => $totalUsers,
                    'users_with_settings' => $usersWithSettings,
                    'coverage_percentage' => $coveragePercentage
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'User notification settings coverage is good',
                'total_users' => $totalUsers,
                'users_with_settings' => $usersWithSettings,
                'coverage_percentage' => $coveragePercentage
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'WARNING',
                'message' => 'Unable to check user settings: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check system resources
     */
    protected function checkSystemResources(): array
    {
        try {
            $memoryUsage = memory_get_usage(true);
            $memoryLimit = ini_get('memory_limit');
            $memoryLimitBytes = $this->convertToBytes($memoryLimit);
            
            $memoryUsagePercentage = $memoryLimitBytes > 0 ? round(($memoryUsage / $memoryLimitBytes) * 100, 2) : 0;

            $diskUsage = disk_free_space('/');
            $diskTotal = disk_total_space('/');
            $diskUsagePercentage = $diskTotal > 0 ? round((($diskTotal - $diskUsage) / $diskTotal) * 100, 2) : 0;

            $issues = [];
            $status = 'PASS';

            if ($memoryUsagePercentage > 90) {
                $issues[] = 'High memory usage';
                $status = 'WARNING';
                $this->healthIssues[] = 'High memory usage';
            }

            if ($diskUsagePercentage > 85) {
                $issues[] = 'High disk usage';
                $status = 'WARNING';
                $this->healthIssues[] = 'High disk usage';
            }

            return [
                'status' => $status,
                'message' => empty($issues) ? 'System resources are healthy' : 'Resource usage is elevated: ' . implode(', ', $issues),
                'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
                'memory_limit_mb' => round($memoryLimitBytes / 1024 / 1024, 2),
                'memory_usage_percentage' => $memoryUsagePercentage,
                'disk_free_gb' => round($diskUsage / 1024 / 1024 / 1024, 2),
                'disk_total_gb' => round($diskTotal / 1024 / 1024 / 1024, 2),
                'disk_usage_percentage' => $diskUsagePercentage,
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'WARNING',
                'message' => 'Unable to check system resources: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check for recent errors in logs
     */
    protected function checkRecentErrors($start, $end): array
    {
        try {
            // Check for notification-related errors in logs
            $errorCount = 0;
            $criticalErrors = [];

            // This is a simplified check - in production you might want to parse actual log files
            // or use a logging service like Sentry, Bugsnag, etc.
            
            // Check for failed notifications
            $failedNotifications = Notification::whereBetween('created_at', [$start, $end])
                ->where('status', 'failed')
                ->count();

            if ($failedNotifications > 0) {
                $errorCount += $failedNotifications;
                $criticalErrors[] = "{$failedNotifications} failed notifications";
            }

            // Check for system errors
            $systemErrors = Notification::join('notification_types', 'notifications.notification_type_id', '=', 'notification_types.id')
                ->whereBetween('notifications.created_at', [$start, $end])
                ->where('notification_types.name', 'system_error')
                ->count();

            if ($systemErrors > 0) {
                $errorCount += $systemErrors;
                $criticalErrors[] = "{$systemErrors} system error notifications";
            }

            if ($errorCount > 10) {
                $this->healthIssues[] = 'High error rate detected';
                return [
                    'status' => 'WARNING',
                    'message' => 'High error rate detected in recent period',
                    'total_errors' => $errorCount,
                    'critical_errors' => $criticalErrors,
                    'failed_notifications' => $failedNotifications,
                    'system_errors' => $systemErrors
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'Error rate is within acceptable limits',
                'total_errors' => $errorCount,
                'failed_notifications' => $failedNotifications,
                'system_errors' => $systemErrors
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'WARNING',
                'message' => 'Unable to check recent errors: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Display health report in console
     */
    protected function displayHealthReport($healthReport): void
    {
        $this->newLine();
        $this->info("=== NOTIFICATION SYSTEM HEALTH REPORT ===");
        $this->info("Timestamp: {$healthReport['timestamp']}");
        $this->info("Overall Status: {$healthReport['overall_status']}");
        $this->newLine();

        foreach ($healthReport['checks'] as $checkName => $result) {
            $statusIcon = $this->getStatusIcon($result['status']);
            $checkTitle = ucwords(str_replace('_', ' ', $checkName));
            
            $this->line("{$statusIcon} {$checkTitle}: {$result['message']}");
            
            // Show additional details for failed or warning checks
            if ($result['status'] !== 'PASS') {
                foreach ($result as $key => $value) {
                    if (!in_array($key, ['status', 'message', 'error'])) {
                        $this->line("    {$key}: {$value}");
                    }
                }
            }
        }

        if (!empty($this->healthIssues)) {
            $this->newLine();
            $this->error("Issues detected:");
            foreach ($this->healthIssues as $issue) {
                $this->line("  • {$issue}");
            }
        }

        $this->newLine();
    }

    /**
     * Send health alerts to administrators
     */
    protected function sendHealthAlerts($healthReport): void
    {
        try {
            $this->info("Sending health alerts to administrators...");

            $alertData = [
                'timestamp' => $healthReport['timestamp'],
                'overall_status' => $healthReport['overall_status'],
                'issues_count' => count($this->healthIssues),
                'issues' => $this->healthIssues,
                'failed_checks' => collect($healthReport['checks'])
                    ->filter(fn($check) => $check['status'] !== 'PASS')
                    ->keys()
                    ->toArray(),
                'check_period' => $healthReport['check_period_minutes'],
            ];

            // Send notification to system administrators
            NotificationHelper::systemErrorOccurred(
                'Notification System Health Issues Detected',
                'HEALTH_CHECK_ALERT',
                $alertData,
                'urgent'
            );

            $this->info("Health alerts sent successfully");

        } catch (\Exception $e) {
            $this->error("Failed to send health alerts: " . $e->getMessage());
            Log::error("Failed to send notification health alerts", [
                'error' => $e->getMessage(),
                'issues' => $this->healthIssues
            ]);
        }
    }

    /**
     * Get status icon for display
     */
    protected function getStatusIcon($status): string
    {
        return match($status) {
            'PASS' => '✅',
            'WARNING' => '⚠️',
            'FAIL' => '❌',
            default => '❓'
        };
    }

    /**
     * Convert memory limit string to bytes
     */
    protected function convertToBytes($value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Test notification system functionality
     */
    protected function testNotificationSystem(): array
    {
        try {
            $testStart = microtime(true);
            
            // Send a test notification
            $testResult = $this->notificationService->sendToUsers(
                [auth()->id() ?? 1], // Use current user or fallback to user ID 1
                'Health Check Test',
                'This is a test notification from the health check system.',
                ['test' => true, 'timestamp' => now()->toDateTimeString()],
                '#'
            );

            $testDuration = round((microtime(true) - $testStart) * 1000, 2);

            if (!$testResult) {
                $this->healthIssues[] = 'Test notification failed';
                return [
                    'status' => 'FAIL',
                    'message' => 'Test notification failed to send',
                    'duration_ms' => $testDuration
                ];
            }

            return [
                'status' => 'PASS',
                'message' => 'Test notification sent successfully',
                'duration_ms' => $testDuration
            ];

        } catch (\Exception $e) {
            $this->healthIssues[] = 'Test notification error';
            return [
                'status' => 'FAIL',
                'message' => 'Test notification error: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate health recommendations
     */
    protected function generateRecommendations($healthReport): array
    {
        $recommendations = [];

        foreach ($healthReport['checks'] as $checkName => $result) {
            if ($result['status'] === 'FAIL' || $result['status'] === 'WARNING') {
                switch ($checkName) {
                    case 'database_connectivity':
                        if (isset($result['response_time_ms']) && $result['response_time_ms'] > 1000) {
                            $recommendations[] = 'Consider optimizing database queries or upgrading database resources';
                        }
                        break;

                    case 'notification_delivery':
                        if (isset($result['failure_rate']) && $result['failure_rate'] > 5) {
                            $recommendations[] = 'Investigate notification delivery failures and check external service connections';
                        }
                        break;

                    case 'queue_health':
                        if (isset($result['queue_size']) && $result['queue_size'] > 500) {
                            $recommendations[] = 'Consider scaling queue workers or optimizing queue processing';
                        }
                        break;

                    case 'pusher_connectivity':
                        $recommendations[] = 'Verify Pusher configuration and check network connectivity to Pusher servers';
                        break;

                    case 'notification_types':
                        $recommendations[] = 'Review and activate necessary notification types';
                        break;

                    case 'user_settings':
                        $recommendations[] = 'Implement automatic notification settings creation for new users';
                        break;

                    case 'system_resources':
                        if (isset($result['memory_usage_percentage']) && $result['memory_usage_percentage'] > 80) {
                            $recommendations[] = 'Consider increasing memory allocation or optimizing memory usage';
                        }
                        if (isset($result['disk_usage_percentage']) && $result['disk_usage_percentage'] > 80) {
                            $recommendations[] = 'Clean up old files or increase disk space';
                        }
                        break;

                    case 'recent_errors':
                        $recommendations[] = 'Review error logs and address recurring issues';
                        break;
                }
            }
        }

        return $recommendations;
    }
}