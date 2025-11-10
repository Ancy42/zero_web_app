<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $phone_number;

    public function __construct($phone_number, public $message)
    {
        $this->phone_number = $phone_number;
    }


    public function broadcastOn(): array
    {
        return ['whatsAppNumber.' . $this->phone_number];
    }

    public function broadcastAs()
    {
        return 'whatsapp_chat';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'date' => now()->format('d M Y h:i A'),
        ];
    }

}
