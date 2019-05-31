<?php

namespace App\Events;

use App\Models\Chat\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated implements ShouldBroadcast
{
    /** @var ChatMessage */
    public $chatMessage;

    /**
     * MessageCreated constructor.
     * @param ChatMessage $chatMessage
     */
    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * @return Channel|Channel[]|PrivateChannel
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat.' . $this->chatMessage->chat_id);
    }

    public function broadcastAs()
    {
        return 'chat.' . $this->chatMessage->chat_id;
    }
}
