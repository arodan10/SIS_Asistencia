<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardSent implements ShouldBroadCast{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $resultCommunion
        //public string $resultRelation,
        //public string $resultMission,
    ){}

    public function broadcastOn(): Channel{
        return new Channel('termometer');
    }
}
