<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('bookings');
    }
}

class BookingCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $reason;

    public function __construct(Booking $booking, $reason = null)
    {
        $this->booking = $booking;
        $this->reason = $reason;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('bookings');
    }
}

class BookingModified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $changes;

    public function __construct(Booking $booking, array $changes = [])
    {
        $this->booking = $booking;
        $this->changes = $changes;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('bookings');
    }
}

class BookingCheckedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('bookings');
    }
}

class BookingCheckedOut
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('bookings');
    }
}