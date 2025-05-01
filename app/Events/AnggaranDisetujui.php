<?php

namespace App\Events;

use App\Models\RancanganAnggaran;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnggaranDisetujui
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $anggaran;

    /**
     * Create a new event instance.
     */
    public function __construct(RancanganAnggaran $anggaran)
    {
        $this->anggaran = $anggaran;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
