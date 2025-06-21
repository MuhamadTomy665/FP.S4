<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Antrian;

class PanggilAntrianEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $antrian;

    public function __construct(Antrian $antrian)
    {
        $this->antrian = $antrian;
    }

    public function broadcastOn()
    {
        return new Channel('antrian-channel');
    }

    public function broadcastAs()
    {
        return 'antrian-dipanggil';
    }
}
