<?php

namespace App\Console\Commands;

use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\NotificationType;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebugNotificationSystem extends Command
{
    protected $signature = 'notifications:debug-system {--test-event} {--test-direct}';
    protected $description = 'Debug the entire notification system';

    public function handle()
    {
        $this->info('🔍 Starting Notification System Debug...');
        
        // 1. Check database connections
        $this->checkDatabase();
        
        // 2. Check models
        $this->checkModels();
        
        // 3. Check notification types
        $this->checkNotificationTypes();
        
        // 4. Check event listeners
        $this->checkEventListeners();
        
        // 5. Check notification service
        $this->checkNotificationService();
        
        // 6. Test direct notification if requested
        if ($this->option('test-direct')) {
            $this->testDirectNotification();
        }
        
        // 7. Test event if requested
        if ($this->option('test-event')) {
            $this->testBookingEvent();
        }
        
        $this->info('✅ Debug completed!');
    }

    private function checkDatabase()
    {
        $this->info('📊 Checking database...');
        
        try {
            $connection = DB::connection()->getPdo();
            $this->info('✅ Database connection: OK');
            
            // Check tables
            $tables = ['booking', 'notifications', 'notification_types', 'users'];
            foreach ($tables as $table) {
                $count = DB::table($table)->count();
                $this->info("✅ Table '{$table}': {$count} records");
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Database error: ' . $e->getMessage());
        }
    }

    private function checkModels()
    {
        $this->info('🏗️ Checking models...');
        
        try {
            // Check Booking model
            $bookingCount = Booking::count();
            $this->info("✅ Booking model: {$bookingCount} bookings");
            
            // Check User model
            $userCount = User::count();
            $this->info("✅ User model: {$userCount} users");
            
            // Check NotificationType model
            $typeCount = NotificationType::count();
            $this->info("✅ NotificationType model: {$typeCount} types");
            
        } catch (\Exception $e) {
            $this->error('❌ Model error: ' . $e->getMessage());
        }
    }

    private function checkNotificationTypes()
    {
        $this->info('📋 Checking notification types...');
        
        try {
            $types = NotificationType::where('is_active', true)->get();
            
            foreach ($types as $type) {
                $this->info("✅ {$type->name}: {$type->title} (Priority: {$type->priority})");
            }
            
            // Check for booking_new specifically
            $bookingNewType = NotificationType::where('name', 'booking_new')->first();
            if ($bookingNewType) {
                $this->info("✅ booking_new type found: " . $bookingNewType->title);
                $this->info("   Target roles: " . implode(', ', $bookingNewType->target_roles ?? []));
            } else {
                $this->error("❌ booking_new notification type not found!");
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Notification types error: ' . $e->getMessage());
        }
    }

    private function checkEventListeners()
    {
        $this->info('👂 Checking event listeners...');
        
        try {
            $listeners = app('events')->getListeners(BookingCreated::class);
            
            if (empty($listeners)) {
                $this->error('❌ No listeners registered for BookingCreated event!');
            } else {
                $this->info('✅ BookingCreated listeners found: ' . count($listeners));
                foreach ($listeners as $listener) {
                    if (is_string($listener)) {
                        $this->info('   - ' . $listener);
                    } elseif (is_callable($listener)) {
                        $this->info('   - Callable/Closure');
                    } else {
                        $this->info('   - ' . get_class($listener));
                    }
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Event listeners error: ' . $e->getMessage());
        }
    }

    private function checkNotificationService()
    {
        $this->info('🔧 Checking notification service...');
        
        try {
            $service = app(NotificationService::class);
            $this->info('✅ NotificationService instantiated successfully');
            
            // Test basic functionality
            $stats = $service->getStatistics();
            $this->info('✅ Service statistics: ' . json_encode($stats));
            
        } catch (\Exception $e) {
            $this->error('❌ NotificationService error: ' . $e->getMessage());
        }
    }

    private function testDirectNotification()
    {
        $this->info('🧪 Testing direct notification...');
        
        try {
            $service = app(NotificationService::class);
            
            // Get first user
            $user = User::first();
            if (!$user) {
                $this->error('❌ No users found for testing');
                return;
            }
            
            $this->info("📤 Sending direct notification to user: {$user->name} (ID: {$user->id})");
            
            $result = $service->sendToUsers(
                [$user->id],
                'Test Direct Notification',
                'Đây là thông báo test trực tiếp từ debug command',
                ['test' => true, 'timestamp' => now()],
                '#test-direct'
            );
            
            if ($result) {
                $this->info('✅ Direct notification sent successfully!');
                
                // Check if notification was created
                $notificationCount = DB::table('notifications')->count();
                $this->info("📊 Total notifications in database: {$notificationCount}");
            } else {
                $this->error('❌ Direct notification failed');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Direct notification test error: ' . $e->getMessage());
            Log::error('Direct notification test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function testBookingEvent()
    {
        $this->info('🧪 Testing BookingCreated event...');
        
        try {
            // Create a fake booking object (not saved to database)
            $booking = new Booking();
            $booking->booking_id = 99999;
            $booking->booking_code = 'TEST-' . time();
            $booking->guest_name = 'Test Guest';
            $booking->guest_email = 'test@example.com';
            $booking->guest_phone = '0123456789';
            $booking->check_in_date = now()->addDay();
            $booking->check_out_date = now()->addDays(2);
            $booking->total_price_vnd = 1000000;
            $booking->status = 'Confirmed';
            
            // Add fake room relationship
            $booking->setRelation('room', (object) [
                'room_number' => 'TEST-101',
                'id' => 1
            ]);
            
            $this->info('📤 Firing BookingCreated event...');
            
            // Enable detailed logging
            Log::info('=== MANUAL EVENT TEST START ===');
            
            event(new BookingCreated($booking));
            
            Log::info('=== MANUAL EVENT TEST END ===');
            
            $this->info('✅ Event fired successfully!');
            
            // Wait a moment for processing
            sleep(2);
            
            // Check if notification was created
            $notificationCount = DB::table('notifications')->count();
            $this->info("📊 Total notifications in database: {$notificationCount}");
            
            // Check recent notifications
            $recentNotifications = DB::table('notifications')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(['title', 'message', 'created_at']);
                
            if ($recentNotifications->count() > 0) {
                $this->info('📧 Recent notifications:');
                foreach ($recentNotifications as $notification) {
                    $this->info("   - {$notification->title}: {$notification->message}");
                }
            }
            
            $this->info('📝 Check the logs and notification dashboard for results.');
            
        } catch (\Exception $e) {
            $this->error('❌ Event test error: ' . $e->getMessage());
            Log::error('Event test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}