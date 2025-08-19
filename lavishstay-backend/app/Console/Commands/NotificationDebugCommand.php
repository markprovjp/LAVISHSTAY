<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationDebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug notification system step by step';

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
        $this->info('🔧 Starting Notification System Debug...');
        $this->newLine();

        // Step 1: Check database connection
        $this->checkDatabaseConnection();

        // Step 2: Check tables exist
        $this->checkTables();

        // Step 3: Check models
        $this->checkModels();

        // Step 4: Check service
        $this->checkService();

        // Step 5: Test basic notification creation
        $this->testBasicNotificationCreation();

        // Step 6: Test service method
        $this->testServiceMethod();

        $this->newLine();
        $this->info('🎉 Debug completed!');
    }

    protected function checkDatabaseConnection()
    {
        $this->info('1️⃣ Checking database connection...');
        
        try {
            DB::connection()->getPdo();
            $this->line('   ✅ Database connection: OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Database connection failed: ' . $e->getMessage());
            return;
        }
    }

    protected function checkTables()
    {
        $this->info('2️⃣ Checking required tables...');
        
        $requiredTables = [
            'users',
            'notifications',
            'notification_types',
            'user_notification_settings'
        ];

        foreach ($requiredTables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line("   ✅ Table '{$table}': EXISTS ({$count} records)");
            } else {
                $this->error("   ❌ Table '{$table}': MISSING");
            }
        }
    }

    protected function checkModels()
    {
        $this->info('3️⃣ Checking models...');
        
        try {
            // Check User model
            $userCount = User::count();
            $this->line("   ✅ User model: OK ({$userCount} users)");
            
            if ($userCount === 0) {
                $this->warn('   ⚠️  No users found in database');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ User model error: ' . $e->getMessage());
        }

        try {
            // Check Notification model
            $notificationCount = Notification::count();
            $this->line("   ✅ Notification model: OK ({$notificationCount} notifications)");
        } catch (\Exception $e) {
            $this->error('   ❌ Notification model error: ' . $e->getMessage());
        }

        try {
            // Check NotificationType model
            $typeCount = NotificationType::count();
            $this->line("   ✅ NotificationType model: OK ({$typeCount} types)");
            
            if ($typeCount === 0) {
                $this->warn('   ⚠️  No notification types found. Run: php artisan db:seed --class=NotificationTypesSeeder');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ NotificationType model error: ' . $e->getMessage());
        }
    }

    protected function checkService()
    {
        $this->info('4️⃣ Checking NotificationService...');
        
        try {
            $debugResults = $this->notificationService->debugTest();
            
            foreach ($debugResults as $test => $result) {
                if ($result['status'] === 'success') {
                    $this->line("   ✅ {$test}: {$result['message']}");
                } else {
                    $this->error("   ❌ {$test}: {$result['message']}");
                }
            }
        } catch (\Exception $e) {
            $this->error('   ❌ NotificationService error: ' . $e->getMessage());
        }
    }

    protected function testBasicNotificationCreation()
    {
        $this->info('5️⃣ Testing basic notification creation...');
        
        try {
            $user = User::first();
            if (!$user) {
                $this->error('   ❌ No users available for testing');
                return;
            }

            $this->line("   📝 Using test user: {$user->name} (ID: {$user->id})");

            // Create a test notification directly
            $notification = Notification::create([
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'title' => 'Debug Test Notification',
                'message' => 'This is a debug test notification created at ' . now()->format('H:i:s'),
                'priority' => 'normal',
                'icon' => '🔧',
                'color' => '#3B82F6',
                'url' => '#debug-test',
                'status' => 'sent',
            ]);

            $this->line("   ✅ Created notification with ID: {$notification->id}");

            // Verify it was created
            $found = Notification::find($notification->id);
            if ($found) {
                $this->line('   ✅ Notification can be retrieved from database');
            } else {
                $this->error('   ❌ Notification not found in database');
            }

            // Clean up
            $notification->delete();
            $this->line('   🧹 Test notification cleaned up');

        } catch (\Exception $e) {
            $this->error('   ❌ Basic notification creation failed: ' . $e->getMessage());
            $this->error('   📋 Stack trace: ' . $e->getTraceAsString());
        }
    }

    protected function testServiceMethod()
    {
        $this->info('6️⃣ Testing NotificationService::sendToUsers method...');
        
        try {
            $user = User::first();
            if (!$user) {
                $this->error('   ❌ No users available for testing');
                return;
            }

            $this->line("   📝 Testing with user: {$user->name} (ID: {$user->id})");

            // Count notifications before
            $beforeCount = Notification::where('notifiable_id', $user->id)->count();
            $this->line("   📊 Notifications before test: {$beforeCount}");

            // Test the service method
            $result = $this->notificationService->sendToUsers(
                [$user->id],
                'Service Test Notification',
                'This is a test notification sent via NotificationService at ' . now()->format('H:i:s'),
                ['test' => true, 'debug' => true],
                '#service-test'
            );

            // Count notifications after
            $afterCount = Notification::where('notifiable_id', $user->id)->count();
            $this->line("   📊 Notifications after test: {$afterCount}");

            if ($result) {
                $this->line('   ✅ NotificationService::sendToUsers returned TRUE');
            } else {
                $this->error('   ❌ NotificationService::sendToUsers returned FALSE');
            }

            if ($afterCount > $beforeCount) {
                $this->line('   ✅ Notification was created in database');
                
                // Get the latest notification
                $latestNotification = Notification::where('notifiable_id', $user->id)
                    ->latest()
                    ->first();
                
                if ($latestNotification) {
                    $this->line("   📋 Latest notification: {$latestNotification->title}");
                    $this->line("   📋 Message: {$latestNotification->message}");
                    $this->line("   📋 Status: {$latestNotification->status}");
                }
            } else {
                $this->error('   ❌ No new notification was created');
            }

        } catch (\Exception $e) {
            $this->error('   ❌ Service method test failed: ' . $e->getMessage());
            $this->error('   📋 Stack trace: ' . $e->getTraceAsString());
        }
    }
}