<?php

namespace App\Events;

use App\Http\Resources\ChatResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageToShop implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public $shopId, public $userId,public $chat)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return ['chat_shop_' . $this->shopId];
    }

    public function broadcastAs(): string
    {
        return 'send-message-to-shop';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'message' => ChatResource::make($this->chat),
        ];
    }
}
