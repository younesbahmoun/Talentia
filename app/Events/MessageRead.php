<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $conversationId;
    public int $readerId;

    public function __construct(int $conversationId, int $readerId)
    {
        $this->conversationId = $conversationId;
        $this->readerId = $readerId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'reader_id' => $this->readerId,
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }
}
