<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomMaintenanceRequired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $issue;
    public $priority;

    public function __construct(Room $room, $issue, $priority = 'normal')
    {
        $this->room = $room;
        $this->issue = $issue;
        $this->priority = $priority;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('rooms');
    }
}

class RoomCleaningRequired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $urgency;

    public function __construct(Room $room, $urgency = 'normal')
    {
        $this->room = $room;
        $this->urgency = $urgency;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('rooms');
    }
}

class RoomStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $oldStatus;
    public $newStatus;

    public function __construct(Room $room, $oldStatus, $newStatus)
    {
        $this->room = $room;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('rooms');
    }
}