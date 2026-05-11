<?php

namespace App\Events;

use App\Models\Boarding;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BoardingCreatedRealtime implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $boarding;

    public function __construct(Boarding $boarding)
    {
        $this->boarding = $boarding;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('boardings'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-boarding';
    }
}
