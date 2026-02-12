<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public bool $isOnline;
    public ?string $lastSeenAt;

    public function __construct(int $userId, bool $isOnline, ?string $lastSeenAt = null)
    {
        $this->userId = $userId;
        $this->isOnline = $isOnline;
        $this->lastSeenAt = $lastSeenAt;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('online'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'is_online' => $this->isOnline,
            'last_seen_at' => $this->lastSeenAt,
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.status';
    }
}
