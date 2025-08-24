<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('payments');
    }
}

class PaymentFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $error;

    public function __construct(Payment $payment, $error = null)
    {
        $this->payment = $payment;
        $this->error = $error;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('payments');
    }
}

class RefundRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $amount;
    public $reason;

    public function __construct(Payment $payment, $amount, $reason = null)
    {
        $this->payment = $payment;
        $this->amount = $amount;
        $this->reason = $reason;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('payments');
    }
}

class RefundProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $refundAmount;

    public function __construct(Payment $payment, $refundAmount)
    {
        $this->payment = $payment;
        $this->refundAmount = $refundAmount;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('payments');
    }
}