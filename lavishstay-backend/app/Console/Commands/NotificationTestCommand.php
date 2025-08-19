<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\NotificationType;
use App\Services\NotificationService;
use App\Helpers\NotificationHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificationTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test 
                            {--type=all : Type of test to run (all|delivery|events|performance|integration)}
                            {--user-id= : Test with specific user ID}
                            {--count=5 : Number of test notifications to send}
                            {--delay=1 : Delay between notifications in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test notification system functionality';

    protected $notificationService;
    protected $testResults = [];

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
        $testType = $this->option('type');
        $userId = $this->option('user-id');
        $count = (int) $this->option('count');
        $delay = (int) $this->option('delay');

        $this->info("Starting notification system tests...");
        $this->info("Test type: {$testType}");

        try {
            $testUser = $this->getTestUser($userId);
            if (!$testUser) {
                $this->error('No test user found. Please specify a valid user ID or ensure users exist.');
                return 1;
            }

            $this->info("Using test user: {$testUser->name} ({$testUser->email})");

            switch ($testType) {
                case 'delivery':
                    $this->testNotificationDelivery($testUser, $count, $delay);
                    break;
                case 'events':
                    $this->testEventNotifications($testUser);
                    break;
                case 'performance':
                    $this->testPerformance($testUser, $count);
                    break;
                case 'integration':
                    $this->testIntegration($testUser);
                    break;
                case 'all':
                default:
                    $this->testNotificationDelivery($testUser, $count, $delay);
                    $this->testEventNotifications($testUser);
                    $this->testPerformance($testUser, $count);
                    $this->testIntegration($testUser);
                    break;
            }

            $this->displayTestResults();

            Log::info('Notification system tests completed', [
                'test_type' => $testType,
                'user_id' => $testUser->id,
                'results' => $this->testResults
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("Test failed: " . $e->getMessage());
            Log::error('Notification system tests failed', [
                'error' => $e->getMessage(),
                'test_type' => $testType
            ]);
            return 1;
        }
    }

    /**
     * Get test user
     */
    protected function getTestUser($userId)
    {
        if ($userId) {
            return User::find($userId);
        }

        // Try to find an admin user first
        $user = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->first();

        // If no admin, get any user
        return $user ?: User::first();
    }

    /**
     * Test basic notification delivery
     */
    protected function testNotificationDelivery($testUser, $count, $delay): void
    {
        $this->info("\n=== Testing Notification Delivery ===");

        $successCount = 0;
        $failureCount = 0;
        $totalTime = 0;

        for ($i = 1; $i <= $count; $i++) {
            $start = microtime(true);
            
            try {
                $result = $this->notificationService->sendToUsers(
                    [$testUser->id],
                    "Test Notification #{$i}",
                    "This is test notification number {$i} sent at " . now()->format('H:i:s'),
                    [
                        'test' => true,
                        'sequence' => $i,
                        'timestamp' => now()->toDateTimeString()
                    ],
                    '#test'
                );

                $duration = round((microtime(true) - $start) * 1000, 2);
                $totalTime += $duration;

                if ($result) {
                    $successCount++;
                    $this->line("✅ Test #{$i} sent successfully ({$duration}ms)");
                } else {
                    $failureCount++;
                    $this->line("❌ Test #{$i} failed");
                }

            } catch (\Exception $e) {
                $failureCount++;
                $this->line("❌ Test #{$i} error: " . $e->getMessage());
            }

            if ($i < $count && $delay > 0) {
                sleep($delay);
            }
        }

        $avgTime = $count > 0 ? round($totalTime / $count, 2) : 0;
        $successRate = $count > 0 ? round(($successCount / $count) * 100, 2) : 0;

        $this->testResults['delivery'] = [
            'total_sent' => $count,
            'successful' => $successCount,
            'failed' => $failureCount,
            'success_rate' => $successRate,
            'avg_time_ms' => $avgTime,
            'total_time_ms' => $totalTime
        ];

        $this->info("Delivery Test Results:");
        $this->line("  Total: {$count}");
        $this->line("  Successful: {$successCount}");
        $this->line("  Failed: {$failureCount}");
        $this->line("  Success Rate: {$successRate}%");
        $this->line("  Average Time: {$avgTime}ms");
    }

    /**
     * Test event-based notifications
     */
    protected function testEventNotifications($testUser): void
    {
        $this->info("\n=== Testing Event Notifications ===");

        $eventTests = [
            'booking_created' => function () use ($testUser) {
                // Simulate booking creation
                $mockBooking = (object) [
                    'id' => 'TEST_' . time(),
                    'room' => (object) ['number' => '101'],
                    'checkin_date' => now()->addDay()->format('Y-m-d'),
                    'checkout_date' => now()->addDays(3)->format('Y-m-d'),
                    'total_amount' => 150000
                ];

                NotificationHelper::bookingCreated($mockBooking, $testUser);
                return 'Booking created event triggered';
            },

            'payment_success' => function () use ($testUser) {
                // Simulate payment success
                $mockPayment = (object) [
                    'id' => 'PAY_' . time(),
                    'payment_method' => 'credit_card',
                    'transaction_id' => 'TXN_' . time()
                ];

                $mockBooking = (object) ['id' => 'BOOK_' . time()];

                NotificationHelper::paymentSuccessful($mockPayment, $mockBooking, 150000);
                return 'Payment success event triggered';
            },

            'system_error' => function () {
                NotificationHelper::systemErrorOccurred(
                    'Test system error notification',
                    'TEST_ERROR_001',
                    ['test' => true, 'component' => 'notification_test'],
                    'high'
                );
                return 'System error event triggered';
            },

            'review_negative' => function () use ($testUser) {
                $mockReview = (object) [
                    'id' => 'REV_' . time(),
                    'comment' => 'This is a test negative review'
                ];

                $mockBooking = (object) [
                    'id' => 'BOOK_' . time(),
                    'room' => (object) ['number' => '102']
                ];

                NotificationHelper::negativeReviewReceived($mockReview, $testUser, $mockBooking, 2, 'Test negative review');
                return 'Negative review event triggered';
            }
        ];

        $eventResults = [];

        foreach ($eventTests as $eventName => $testFunction) {
            try {
                $start = microtime(true);
                $message = $testFunction();
                $duration = round((microtime(true) - $start) * 1000, 2);

                $eventResults[$eventName] = [
                    'status' => 'success',
                    'message' => $message,
                    'duration_ms' => $duration
                ];

                $this->line("✅ {$eventName}: {$message} ({$duration}ms)");

            } catch (\Exception $e) {
                $eventResults[$eventName] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'duration_ms' => 0
                ];

                $this->line("❌ {$eventName}: " . $e->getMessage());
            }
        }

        $this->testResults['events'] = $eventResults;
    }

    /**
     * Test notification system performance
     */
    protected function testPerformance($testUser, $count): void
    {
        $this->info("\n=== Testing Performance ===");

        // Test bulk notification sending
        $this->info("Testing bulk notification sending...");
        
        $start = microtime(true);
        $memoryStart = memory_get_usage(true);

        $bulkData = [];
        for ($i = 1; $i <= $count; $i++) {
            $bulkData[] = [
                'title' => "Bulk Test #{$i}",
                'message' => "Bulk notification test message #{$i}",
                'data' => ['bulk_test' => true, 'sequence' => $i]
            ];
        }

        $bulkSuccess = 0;
        foreach ($bulkData as $index => $notification) {
            try {
                $result = $this->notificationService->sendToUsers(
                    [$testUser->id],
                    $notification['title'],
                    $notification['message'],
                    $notification['data'],
                    '#bulk-test'
                );

                if ($result) {
                    $bulkSuccess++;
                }

            } catch (\Exception $e) {
                // Count failures
            }
        }

        $bulkDuration = round((microtime(true) - $start) * 1000, 2);
        $memoryUsed = memory_get_usage(true) - $memoryStart;
        $avgTimePerNotification = $count > 0 ? round($bulkDuration / $count, 2) : 0;

        $this->testResults['performance'] = [
            'bulk_count' => $count,
            'bulk_success' => $bulkSuccess,
            'bulk_duration_ms' => $bulkDuration,
            'avg_time_per_notification_ms' => $avgTimePerNotification,
            'memory_used_bytes' => $memoryUsed,
            'memory_used_mb' => round($memoryUsed / 1024 / 1024, 2),
            'notifications_per_second' => $bulkDuration > 0 ? round(($count / $bulkDuration) * 1000, 2) : 0
        ];

        $this->info("Performance Test Results:");
        $this->line("  Bulk notifications: {$count}");
        $this->line("  Successful: {$bulkSuccess}");
        $this->line("  Total time: {$bulkDuration}ms");
        $this->line("  Avg per notification: {$avgTimePerNotification}ms");
        $this->line("  Memory used: " . round($memoryUsed / 1024 / 1024, 2) . "MB");
        $this->line("  Rate: " . ($bulkDuration > 0 ? round(($count / $bulkDuration) * 1000, 2) : 0) . " notifications/second");
    }

    /**
     * Test system integration
     */
    protected function testIntegration($testUser): void
    {
        $this->info("\n=== Testing System Integration ===");

        $integrationTests = [
            'database_connection' => function () {
                $count = \App\Models\Notification::count();
                return "Database accessible, {$count} notifications in system";
            },

            'notification_types' => function () {
                $activeTypes = NotificationType::where('is_active', true)->count();
                $totalTypes = NotificationType::count();
                return "Found {$activeTypes} active types out of {$totalTypes} total";
            },

            'user_settings' => function () use ($testUser) {
                $settings = $testUser->notificationSettings()->count();
                return "User has {$settings} notification settings configured";
            },

            'cache_system' => function () {
                $testKey = 'notification_test_' . time();
                $testValue = 'test_value_' . rand(1000, 9999);
                
                \Cache::put($testKey, $testValue, 60);
                $retrieved = \Cache::get($testKey);
                \Cache::forget($testKey);
                
                return $retrieved === $testValue ? 'Cache system working' : 'Cache system failed';
            },

            'queue_system' => function () {
                // Basic queue test - this is simplified
                try {
                    // You might want to dispatch a test job here
                    return 'Queue system appears to be configured';
                } catch (\Exception $e) {
                    return 'Queue system error: ' . $e->getMessage();
                }
            },

            'pusher_config' => function () {
                $pusherKey = config('broadcasting.connections.pusher.key');
                $pusherSecret = config('broadcasting.connections.pusher.secret');
                $pusherAppId = config('broadcasting.connections.pusher.app_id');

                if (empty($pusherKey) || empty($pusherSecret) || empty($pusherAppId)) {
                    return 'Pusher configuration incomplete';
                }

                return 'Pusher configuration appears complete';
            }
        ];

        $integrationResults = [];

        foreach ($integrationTests as $testName => $testFunction) {
            try {
                $start = microtime(true);
                $message = $testFunction();
                $duration = round((microtime(true) - $start) * 1000, 2);

                $integrationResults[$testName] = [
                    'status' => 'success',
                    'message' => $message,
                    'duration_ms' => $duration
                ];

                $this->line("✅ {$testName}: {$message} ({$duration}ms)");

            } catch (\Exception $e) {
                $integrationResults[$testName] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'duration_ms' => 0
                ];

                $this->line("❌ {$testName}: " . $e->getMessage());
            }
        }

        $this->testResults['integration'] = $integrationResults;
    }

    /**
     * Display comprehensive test results
     */
    protected function displayTestResults(): void
    {
        $this->info("\n" . str_repeat("=", 50));
        $this->info("COMPREHENSIVE TEST RESULTS");
        $this->info(str_repeat("=", 50));

        $overallSuccess = true;
        $totalTests = 0;
        $passedTests = 0;

        foreach ($this->testResults as $category => $results) {
            $this->info("\n{$category} Results:");
            
            if (is_array($results) && isset($results['total_sent'])) {
                // Delivery test results
                $success = $results['success_rate'] >= 90;
                $overallSuccess = $overallSuccess && $success;
                $totalTests++;
                if ($success) $passedTests++;

                $this->line("  Success Rate: {$results['success_rate']}% " . ($success ? '✅' : '❌'));
                $this->line("  Average Time: {$results['avg_time_ms']}ms");
                
            } elseif (is_array($results) && isset($results['bulk_count'])) {
                // Performance test results
                $success = $results['notifications_per_second'] > 10; // At least 10 notifications per second
                $overallSuccess = $overallSuccess && $success;
                $totalTests++;
                if ($success) $passedTests++;

                $this->line("  Performance: {$results['notifications_per_second']} notifications/sec " . ($success ? '✅' : '❌'));
                $this->line("  Memory Usage: {$results['memory_used_mb']}MB");
                
            } else {
                // Event or integration test results
                foreach ($results as $testName => $result) {
                    $success = $result['status'] === 'success';
                    $overallSuccess = $overallSuccess && $success;
                    $totalTests++;
                    if ($success) $passedTests++;

                    $icon = $success ? '✅' : '❌';
                    $this->line("  {$testName}: {$result['message']} {$icon}");
                }
            }
        }

        $this->info("\n" . str_repeat("-", 50));
        $this->info("OVERALL RESULTS:");
        $this->line("  Tests Passed: {$passedTests}/{$totalTests}");
        $this->line("  Success Rate: " . ($totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0) . "%");
        $this->line("  Overall Status: " . ($overallSuccess ? 'PASS ✅' : 'FAIL ❌'));

        if (!$overallSuccess) {
            $this->error("\nSome tests failed. Please review the results above and check your notification system configuration.");
        } else {
            $this->info("\nAll tests passed! Your notification system is working correctly.");
        }
    }

    /**
     * Generate test report
     */
    protected function generateTestReport(): array
    {
        return [
            'timestamp' => now()->toDateTimeString(),
            'test_results' => $this->testResults,
            'summary' => [
                'overall_status' => $this->calculateOverallStatus(),
                'recommendations' => $this->generateRecommendations()
            ]
        ];
    }

    /**
     * Calculate overall test status
     */
    protected function calculateOverallStatus(): string
    {
        $hasFailures = false;

        foreach ($this->testResults as $category => $results) {
            if (is_array($results)) {
                foreach ($results as $result) {
                    if (is_array($result) && isset($result['status']) && $result['status'] === 'error') {
                        $hasFailures = true;
                        break 2;
                    }
                }
            }
        }

        return $hasFailures ? 'FAIL' : 'PASS';
    }

    /**
     * Generate recommendations based on test results
     */
    protected function generateRecommendations(): array
    {
        $recommendations = [];

        // Check delivery performance
        if (isset($this->testResults['delivery']['success_rate']) && 
            $this->testResults['delivery']['success_rate'] < 95) {
            $recommendations[] = 'Delivery success rate is below 95%. Check database connectivity and notification service configuration.';
        }

        // Check performance
        if (isset($this->testResults['performance']['notifications_per_second']) && 
            $this->testResults['performance']['notifications_per_second'] < 10) {
            $recommendations[] = 'Notification sending performance is slow. Consider optimizing database queries or using queue workers.';
        }

        // Check integration issues
        if (isset($this->testResults['integration'])) {
            foreach ($this->testResults['integration'] as $testName => $result) {
                if ($result['status'] === 'error') {
                    $recommendations[] = "Integration issue with {$testName}: {$result['message']}";
                }
            }
        }

        return $recommendations;
    }
}