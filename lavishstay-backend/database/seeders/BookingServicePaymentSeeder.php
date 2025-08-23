<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Service;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class BookingServicePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Use existing booking or create new one
            $booking = Booking::where('booking_code', 'LAVISHSTAY_509999')->first();
            if (!$booking) {
                $booking = Booking::create([
                    'booking_code' => 'LAVISHSTAY_509999',
                    'user_id' => 1,
                    'option_id' => 1,
                    'check_in_date' => now()->addDays(1),
                    'check_out_date' => now()->addDays(3),
                    'total_price_vnd' => 2000000,
                    'guest_count' => 2,
                    'status' => 'Confirmed',
                    'guest_name' => 'Test Guest Service Payment',
                    'guest_email' => 'test.service@example.com',
                    'guest_phone' => '0123456789',
                    'quantity' => 1,
                    'room_type_id' => 1,
                    'notes' => 'Test booking for service payment system'
                ]);
            }

            // Clear existing booking services for this test booking
            BookingService::where('booking_id', $booking->booking_id)->delete();

            // Create booking services with different payment statuses
            $services = [
                [
                    'service_id' => 2, // Spa massage
                    'quantity' => 2,
                    'price_vnd' => 500000,
                    'paid_amount_vnd' => 0,
                    'payment_status' => 'pending',
                    'last_payment_id' => null
                ],
                [
                    'service_id' => 5, // Airport transfer
                    'quantity' => 1,
                    'price_vnd' => 300000,
                    'paid_amount_vnd' => 150000,
                    'payment_status' => 'partial',
                    'last_payment_id' => null
                ],
                [
                    'service_id' => 3, // Laundry
                    'quantity' => 3,
                    'price_vnd' => 150000,
                    'paid_amount_vnd' => 450000,
                    'payment_status' => 'paid',
                    'last_payment_id' => null
                ]
            ];

            $totalAmount = 0;
            $totalPaid = 0;
            $createdServices = [];

            foreach ($services as $serviceData) {
                $bookingService = BookingService::create([
                    'booking_id' => $booking->booking_id,
                    'service_id' => $serviceData['service_id'],
                    'quantity' => $serviceData['quantity'],
                    'price_vnd' => $serviceData['price_vnd'],
                    'paid_amount_vnd' => $serviceData['paid_amount_vnd'],
                    'payment_status' => $serviceData['payment_status'],
                    'last_payment_id' => $serviceData['last_payment_id']
                ]);

                $serviceTotal = $serviceData['quantity'] * $serviceData['price_vnd'];
                $totalAmount += $serviceTotal;
                $totalPaid += $serviceData['paid_amount_vnd'];
                
                // Load service relationship for display
                $bookingService->load('service');
                $createdServices[] = [
                    'name' => $bookingService->service->name,
                    'quantity' => $serviceData['quantity'],
                    'total' => $serviceTotal,
                    'paid' => $serviceData['paid_amount_vnd'],
                    'status' => $serviceData['payment_status']
                ];
            }

            // Create some sample payment records if they don't exist
            if (Payment::count() == 0) {
                Payment::create([
                    'booking_id' => $booking->booking_id,
                    'amount_vnd' => 150000,
                    'payment_type' => 'service_payment',
                    'payment_method' => 'bank_transfer',
                    'status' => 'completed',
                    'transaction_id' => 'TEST_' . time(),
                    'created_at' => now()->subHour()
                ]);
            }

            $outstanding = $totalAmount - $totalPaid;

            $this->command->info("✅ Created test booking with services:");
            $this->command->info("   - Booking: {$booking->booking_code}");
            foreach ($createdServices as $service) {
                $status = ucfirst($service['status']);
                $this->command->info("   - {$service['name']} ({$service['quantity']}x): ₫" . number_format($service['total']) . " - {$status}" . 
                    ($service['paid'] > 0 ? " (₫" . number_format($service['paid']) . " paid)" : ""));
            }
            $this->command->info("   - Total Service Amount: ₫" . number_format($totalAmount));
            $this->command->info("   - Total Paid: ₫" . number_format($totalPaid));
            $this->command->info("   - Outstanding: ₫" . number_format($outstanding));
        });
    }
}