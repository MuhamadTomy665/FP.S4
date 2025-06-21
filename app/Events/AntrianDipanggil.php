<?php

namespace App\Events;

use App\Models\Antrian;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class AntrianDipanggil implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $antrian;

    /**
     * Create a new event instance.
     */
    public function __construct(Antrian $antrian)
    {
        $this->antrian = $antrian;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('antrian');
    }

    /**
     * Optional: event name on the frontend
     */
    public function broadcastAs()
    {
        return 'dipanggil';
    }
}
