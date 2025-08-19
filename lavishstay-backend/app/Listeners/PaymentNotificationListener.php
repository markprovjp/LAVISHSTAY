<?php

namespace App\Listeners;

use App\Events\PaymentSuccessful;
use App\Events\PaymentFailed;
use App\Events\RefundRequested;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class PaymentNotificationListener
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle payment successful event
     */
    public function handlePaymentSuccessful(PaymentSuccessful $event)
    {
        try {
            $data = [
                'payment_id' => $event->payment->id ?? 'N/A',
                'booking_id' => $event->booking->id ?? 'N/A',
                'amount' => number_format($event->amount) . ' VND',
                'payment_method' => $event->payment->payment_method ?? 'N/A',
                'transaction_id' => $event->payment->transaction_id ?? 'N/A',
                'paid_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendPaymentNotification('success', $data);

            Log::info('Payment successful notification sent', [
                'payment_id' => $event->payment->id,
                'amount' => $event->amount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment successful notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment failed event
     */
    public function handlePaymentFailed(PaymentFailed $event)
    {
        try {
            $data = [
                'payment_id' => $event->payment->id ?? 'N/A',
                'booking_id' => $event->booking->id ?? 'N/A',
                'amount' => number_format($event->amount) . ' VND',
                'reason' => $event->reason ?? 'Unknown error',
                'payment_method' => $event->payment->payment_method ?? 'N/A',
                'failed_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendPaymentNotification('failed', $data);

            Log::info('Payment failed notification sent', [
                'payment_id' => $event->payment->id,
                'reason' => $event->reason
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment failed notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle refund requested event
     */
    public function handleRefundRequested(RefundRequested $event)
    {
        try {
            $data = [
                'refund_id' => $event->refund->id ?? 'N/A',
                'booking_id' => $event->booking->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Unknown',
                'amount' => number_format($event->amount) . ' VND',
                'reason' => $event->reason ?? 'No reason provided',
                'requested_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendPaymentNotification('refund_requested', $data);

            Log::info('Refund requested notification sent', [
                'refund_id' => $event->refund->id,
                'amount' => $event->amount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send refund requested notification: ' . $e->getMessage());
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events)
    {
        $events->listen(
            PaymentSuccessful::class,
            [PaymentNotificationListener::class, 'handlePaymentSuccessful']
        );

        $events->listen(
            PaymentFailed::class,
            [PaymentNotificationListener::class, 'handlePaymentFailed']
        );

        $events->listen(
            RefundRequested::class,
            [PaymentNotificationListener::class, 'handleRefundRequested']
        );
    }
}