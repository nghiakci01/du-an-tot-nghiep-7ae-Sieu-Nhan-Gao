<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cartCount;
    public $sessionId;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($cartCount, $sessionId, $userId = null)
    {
        $this->cartCount = $cartCount;
        $this->sessionId = $sessionId;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Nếu user đã đăng nhập, broadcast qua private channel của user.
        // Ngược lại, broadcast qua public channel dựa trên session_id.
        if ($this->userId) {
            return [
                new PrivateChannel('App.Models.User.' . $this->userId),
            ];
        }

        return [
            new Channel('cart.' . $this->sessionId),
        ];
    }
}
