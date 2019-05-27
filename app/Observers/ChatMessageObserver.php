<?php

namespace App\Observers;

use App\Models\Chat\ChatMessage;

class ChatMessageObserver
{
    public function created(ChatMessage $chatMessage)
    {
//        $user = $chatMessage->chat()->users()->reject(function ($query) use ($chatMessage){
//            return $query->user_id == $chatMessage->author->id;
//        });
        //TODO to broadcasthere
    }
}
