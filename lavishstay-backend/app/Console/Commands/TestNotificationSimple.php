<?php

namespace App\Console\Commands;

use App\Events\BookingCreated;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestNotificationSimple extends Command
{
    protected $signature = 'notifications:test-simple {--direct} {--event} {--real-booking=}';
    protected $description = 'Simple notification system test';

    public function handle()
    {
        $this->info('🧪 Simple Notification Test');

        if ($this->option('direct')) {
            $this->testDirectNotification();
        }

        if ($this->option('event')) {
            $this->testEventNotification();
        }

        if ($this->option('real-booking')) {
            $this->testRealBookingEvent();
        }

        if (!$this->option('direct') && !$this->option('event') && !$this->option('real-booking')) {
            $this->info('Available options:');
            $this->info('  --direct         Test direct notification');
            $this->info('  --event          Test event notification');
            $this->info('  --real-booking=ID Test with real booking ID');
        }
    }

    private function testDirectNotification()
    {
        $this->info('📤 Testing direct notification...');

        try {
            $service = app(NotificationService::class);
            
            // Get first user
            $user = DB::table('users')->first();
            if (!$user) {
                $this->error('❌ No users found');
                return;
            }

            $this->info("Sending to user: {$user->name} (ID: {$user->id})");

            $result = $service->sendToUsers(
                [$user->id],
                'Test Direct Notification',
                'Đây là test notification trực tiếp lúc ' . now()->format('H:i:s'),
                ['test' => true, 'timestamp' => now()],
                '#test-direct'
            );

            if ($result) {
                $this->info('✅ Direct notification sent successfully!');
                
                // Check database
                $count = DB::table('notifications')->count();
                $this->info("📊 Total notifications: {$count}");
                
                $latest = DB::table('notifications')->latest('created_at')->first();
                if ($latest) {
                    $this->info("📧 Latest: {$latest->title}");
                }
            } else {
                $this->error('❌ Direct notification failed');
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            Log::error('Direct notification test failed', ['error' => $e->getMessage()]);
        }
    }

    private function testEventNotification()
    {
        $this->info('📤 Testing event notification...');

        try {
            // Create simple booking object (not saved to DB)
            $booking = new Booking();
            $booking->booking_id = 99999;
            $booking->booking_code = 'TEST-' . time();
            $booking->guest_name = 'Test Guest Event';
            $booking->guest_email = 'test@example.com';
            $booking->guest_phone = '0123456789';
            $booking->check_in_date = now()->addDay()->format('Y-m-d');
            $booking->check_out_date = now()->addDays(2)->format('Y-m-d');
            $booking->total_price_vnd = 1500000;
            $booking->status = 'Confirmed';

            // Add fake room
            $booking->setRelation('room', (object) [
                'room_number' => 'TEST-101',
                'id' => 1
            ]);

            $this->info('Booking data:');
            $this->info("  ID: {$booking->booking_id}");
            $this->info("  Guest: {$booking->guest_name}");
            $this->info("  Room: TEST-101");

            Log::info('=== EVENT TEST START ===', [
                'booking_id' => $booking->booking_id,
                'guest_name' => $booking->guest_name
            ]);

            // Fire event
            event(new BookingCreated($booking));

            Log::info('=== EVENT TEST END ===');

            $this->info('✅ Event fired successfully!');

            // Wait and check results
            sleep(1);
            
            $count = DB::table('notifications')->count();
            $this->info("📊 Total notifications: {$count}");
            
            $recent = DB::table('notifications')
                ->where('created_at', '>=', now()->subMinutes(1))
                ->orderBy('created_at', 'desc')
                ->first();
                
            if ($recent) {
                $this->info("📧 Recent notification: {$recent->title}");
                $this->info("   Message: {$recent->message}");
            } else {
                $this->warn('⚠️ No recent notifications found');
            }

        } catch (\Exception $e) {
            $this->error('❌ Event test error: ' . $e->getMessage());
            Log::error('Event test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function testRealBookingEvent()
    {
        $bookingId = $this->option('real-booking');
        $this->info("📤 Testing with real booking ID: {$bookingId}");

        try {
            $booking = Booking::with(['roomType', 'bookingRooms.room'])->find($bookingId);
            
            if (!$booking) {
                $this->error("❌ Booking {$bookingId} not found");
                return;
            }

            $this->info('Real booking data:');
            $this->info("  ID: {$booking->booking_id}");
            $this->info("  Guest: {$booking->guest_name}");
            $this->info("  Status: {$booking->status}");
            
            if ($booking->roomType) {
                $this->info("  Room Type: {$booking->roomType->name}");
            }

            // Add room relation for notification
            if ($booking->bookingRooms->isNotEmpty() && $booking->bookingRooms->first()->room) {
                $booking->setRelation('room', $booking->bookingRooms->first()->room);
                $this->info("  Room Number: {$booking->bookingRooms->first()->room->room_number}");
            } else {
                $booking->setRelation('room', (object) [
                    'room_number' => $booking->roomType ? $booking->roomType->name : 'N/A',
                    'id' => null
                ]);
            }

            Log::info('=== REAL BOOKING EVENT TEST START ===', [
                'booking_id' => $booking->booking_id,
                'guest_name' => $booking->guest_name
            ]);

            // Fire event
            event(new BookingCreated($booking));

            Log::info('=== REAL BOOKING EVENT TEST END ===');

            $this->info('✅ Real booking event fired successfully!');

            // Check results
            sleep(1);
            
            $count = DB::table('notifications')->count();
            $this->info("📊 Total notifications: {$count}");
            
            $recent = DB::table('notifications')
                ->where('created_at', '>=', now()->subMinutes(1))
                ->orderBy('created_at', 'desc')
                ->first();
                
            if ($recent) {
                $this->info("📧 Recent notification: {$recent->title}");
                $this->info("   Message: {$recent->message}");
            }

        } catch (\Exception $e) {
            $this->error('❌ Real booking test error: ' . $e->getMessage());
            Log::error('Real booking test failed', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
        }
    }
}