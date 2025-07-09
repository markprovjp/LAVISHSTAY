<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Representative;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email {--create-demo-booking}';
    protected $description = 'Test email functionality with demo booking data';

    public function handle()
    {
        if ($this->option('create-demo-booking')) {
            $this->createDemoBooking();
        }
        
        $this->testEmailFunctionality();
    }

    private function createDemoBooking()
    {
        $this->info('Creating demo booking...');
        
        DB::beginTransaction();
        try {
            // Tạo booking demo
            $booking = Booking::create([
                'booking_code' => 'DEMO' . now()->format('YmdHis'),
                'guest_name' => 'Nguyễn Văn Demo',
                'guest_email' => 'demo@example.com',
                'guest_phone' => '0123456789',
                'check_in_date' => Carbon::now()->addDays(7),
                'check_out_date' => Carbon::now()->addDays(10),
                'guest_count' => 2,
                'total_price_vnd' => 2500000,
                'status' => 'confirmed',
                'adults' => 2,
                'children' => 0,
                'user_id' => null,
                'room_id' => 1,
            ]);

            // Tạo representative demo
            Representative::create([
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'room_id' => 1,
                'full_name' => 'Nguyễn Văn Demo',
                'phone_number' => '0123456789',
                'email' => 'quyenjpn@gmail.com', // Email thật để test
                'id_card' => '123456789',
            ]);

            // Tạo booking_rooms demo
            DB::table('booking_rooms')->insert([
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'room_id' => 1,
                'option_id' => 'DEMO-OPTION-001',
                'option_name' => 'Deluxe Room Package',
                'option_price' => 850000,
                'adults' => 2,
                'children' => 0,
                'price_per_night' => 850000,
                'nights' => 3,
                'total_price' => 2550000,
                'check_in_date' => Carbon::now()->addDays(7),
                'check_out_date' => Carbon::now()->addDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            
            $this->info("Demo booking created successfully!");
            $this->info("Booking ID: {$booking->booking_id}");
            $this->info("Booking Code: {$booking->booking_code}");
            
            return $booking->booking_id;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error creating demo booking: " . $e->getMessage());
            return null;
        }
    }

    private function testEmailFunctionality()
    {
        $this->info('Testing email functionality...');
        
        // Lấy booking gần nhất
        $booking = Booking::latest()->first();
        
        if (!$booking) {
            $this->error('No booking found. Run with --create-demo-booking option first.');
            return;
        }

        $this->info("Testing with booking ID: {$booking->booking_id}");
        $this->info("Booking Code: {$booking->booking_code}");
        
        // Test gửi email
        $paymentController = new PaymentController();
        $reflection = new \ReflectionClass($paymentController);
        $method = $reflection->getMethod('sendBookingConfirmationEmail');
        $method->setAccessible(true);
        
        try {
            $result = $method->invoke($paymentController, $booking->booking_id);
            
            if ($result) {
                $this->info('✅ Email sent successfully!');
            } else {
                $this->error('❌ Email failed to send. Check logs for details.');
            }
        } catch (\Exception $e) {
            $this->error('❌ Error testing email: ' . $e->getMessage());
        }
    }
}
