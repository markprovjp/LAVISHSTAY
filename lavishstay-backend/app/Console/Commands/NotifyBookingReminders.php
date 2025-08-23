<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingReminderNotification;
use App\Notifications\CheckoutReminderNotification;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotifyBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notify:booking-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send booking reminders for upcoming check-ins and check-outs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting booking reminders notification process...');

        $checkinCount = $this->sendCheckinReminders();
        $checkoutCount = $this->sendCheckoutReminders();

        $this->info("Sent {$checkinCount} check-in reminders and {$checkoutCount} check-out reminders.");
        
        Log::info('Booking reminders process completed', [
            'checkin_reminders' => $checkinCount,
            'checkout_reminders' => $checkoutCount
        ]);

        return 0;
    }

    /**
     * Send check-in reminders
     */
    protected function sendCheckinReminders(): int
    {
        $windowHours = config('notifications.checkin_window_hours', 24);
        $now = Carbon::now();
        $windowEnd = $now->copy()->addHours($windowHours);

        $bookings = Booking::where('status', 'confirmed')
            ->whereBetween('check_in_date', [$now, $windowEnd])
            ->with('user')
            ->get();

        $sentCount = 0;

        foreach ($bookings as $booking) {
            if (!$booking->user) {
                continue;
            }

            // Check for duplicate notifications
            $existingNotification = $booking->user->notifications()
                ->where('type', BookingReminderNotification::class)
                ->where('data->booking_id', $booking->booking_id)
                ->exists();

            if ($existingNotification) {
                continue;
            }

            try {
                $booking->user->notify(new BookingReminderNotification($booking));
                $sentCount++;
                
                $this->line("Sent check-in reminder for booking {$booking->booking_code} to user {$booking->user->email}");
            } catch (\Exception $e) {
                Log::error('Failed to send check-in reminder', [
                    'booking_id' => $booking->booking_id,
                    'user_id' => $booking->user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $sentCount;
    }

    /**
     * Send check-out reminders
     */
    protected function sendCheckoutReminders(): int
    {
        $windowHours = config('notifications.checkout_window_hours', 24);
        $now = Carbon::now();
        $windowEnd = $now->copy()->addHours($windowHours);

        $bookings = Booking::where('status', 'checked_in')
            ->whereBetween('check_out_date', [$now, $windowEnd])
            ->with('user')
            ->get();

        $sentCount = 0;

        foreach ($bookings as $booking) {
            if (!$booking->user) {
                continue;
            }

            // Check for duplicate notifications
            $existingNotification = $booking->user->notifications()
                ->where('type', CheckoutReminderNotification::class)
                ->where('data->booking_id', $booking->booking_id)
                ->exists();

            if ($existingNotification) {
                continue;
            }

            try {
                $booking->user->notify(new CheckoutReminderNotification($booking));
                $sentCount++;
                
                $this->line("Sent check-out reminder for booking {$booking->booking_code} to user {$booking->user->email}");
            } catch (\Exception $e) {
                Log::error('Failed to send check-out reminder', [
                    'booking_id' => $booking->booking_id,
                    'user_id' => $booking->user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $sentCount;
    }
}
