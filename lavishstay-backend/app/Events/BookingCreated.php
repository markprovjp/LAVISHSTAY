<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $customer;

    public function __construct($booking, $customer)
    {
        $this->booking = $booking;
        $this->customer = $customer;
    }
}

class BookingCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $customer;
    public $reason;

    public function __construct($booking, $customer, $reason = null)
    {
        $this->booking = $booking;
        $this->customer = $customer;
        $this->reason = $reason;
    }
}

class BookingModified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $customer;
    public $changes;

    public function __construct($booking, $customer, $changes = [])
    {
        $this->booking = $booking;
        $this->customer = $customer;
        $this->changes = $changes;
    }
}

class CheckinReminder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $customer;

    public function __construct($booking, $customer)
    {
        $this->booking = $booking;
        $this->customer = $customer;
    }
}

class CheckoutCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $customer;
    public $room;

    public function __construct($booking, $customer, $room)
    {
        $this->booking = $booking;
        $this->customer = $customer;
        $this->room = $room;
    }
}