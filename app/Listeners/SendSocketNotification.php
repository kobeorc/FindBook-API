<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Models\Chat\ChatMessage;

class SendSocketNotification
{
    /**
     * @param MessageCreated $event
     * @return ChatMessage
     */
    public function handle(MessageCreated $event)
    {
        return $event->chatMessage;
    }
}
