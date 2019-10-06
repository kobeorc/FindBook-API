<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ChatStoreRequest;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends ApiController
{
    public function store(ChatStoreRequest $request)
    {
        switch ($request->get('chat_type')) {
            case Chat::TYPE_PRIVATE:
                $message = $this->storePrivateMessage(
                    $request->get('message_type'),
                    User::findOrFail($request->get('to')),
                    $request->get('text')
                );
                break;
            default:
                abort(400, 'Не поддерживаемый тип чата');
        }

        return $this->jsonResponse([$message]);
    }

    protected function storePrivateMessage(string $type, User $to, string $text)
    {
        switch ($type) {
            case ChatMessage::TYPE_TEXT:
                /** @var User $user */
                $user = Auth::user();

                /**
                 * wtf TODO refactor
                 */
                $chat = $user->chats()
                    ->where('type', Chat::TYPE_PRIVATE)
                    ->whereHas('users', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->whereHas('users', function ($query) use ($to) {
                        $query->where('user_id',$to->id);
                    })
                    ->first();

                /**
                 * end
                 */

                if (!$chat) {
                    $chat = new Chat();
                    $chat->type = Chat::TYPE_PRIVATE;
                    $chat->save();
                    $chat->users()->attach($user);
                    $chat->users()->attach($to);
                    $chat->save();

                }

                return $this->storeTextMessage($chat, $text, $user);

                break;
            default:
                abort(400, 'Не поддерживаемый тип сообщения');
        }
    }

    protected function storeTextMessage(Chat $chat, string $text, User $user)
    {
        $chatMessage = new ChatMessage();
        $chatMessage->chat()->associate($chat->id);
        $chatMessage->author()->associate($user->id);
        $chatMessage->status = ChatMessage::STATUS_SENDING;
        $chatMessage->type = ChatMessage::TYPE_TEXT;
        $chatMessage->text = $text;
        $chatMessage->save();
        $result = ChatMessage::with(['author'])->findOrFail($chatMessage->id);

        return $result;
    }

    public function getUsersPrivateChats()
    {
        /** @var User $user */
        $user = Auth::user();
        return $this->jsonResponse($user->chats()->get());
    }

    public function getUserPrivateMessages($chatId)
    {
        $chatMessages = ChatMessage::query()->with(['author'])->where('chat_id', $chatId);
        return $this->jsonPaginateResponse($chatMessages);
    }

    public function markMessageAsSent(Request $request, Chat $chat, ChatMessage $chatMessage)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($chat->users()->where('user_id', $user->id)->exists(), 400, 'Пользователя нет в этом чате');
        abort_unless($chatMessage->author->id === $user->id, 400, 'Пользователей не автор сообщения');

        $chatMessage->status = ChatMessage::STATUS_SENT;
        $chatMessage->save();

        return $this->jsonResponse([]);
    }

    public function markMessageAsRead(Request $request,Chat $chat, ChatMessage $chatMessage)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($chat->users()->where('user_id', $user->id)->exists(), 400, 'Пользователя нет в этом чате');
        abort_if($chatMessage->author->id === $user->id, 400, 'Пользователей автор сообщения');

        $chatMessage->status = ChatMessage::STATUS_READ;
        $chatMessage->save();

        return $this->jsonResponse([]);
    }
}
