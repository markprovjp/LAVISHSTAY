<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $booking;
    public $amount;

    public function __construct($payment, $booking, $amount)
    {
        $this->payment = $payment;
        $this->booking = $booking;
        $this->amount = $amount;
    }
}

class PaymentFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $booking;
    public $amount;
    public $reason;

    public function __construct($payment, $booking, $amount, $reason = null)
    {
        $this->payment = $payment;
        $this->booking = $booking;
        $this->amount = $amount;
        $this->reason = $reason;
    }
}

class RefundRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $refund;
    public $booking;
    public $customer;
    public $amount;
    public $reason;

    public function __construct($refund, $booking, $customer, $amount, $reason = null)
    {
        $this->refund = $refund;
        $this->booking = $booking;
        $this->customer = $customer;
        $this->amount = $amount;
        $this->reason = $reason;
    }
}