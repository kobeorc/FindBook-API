<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChatController extends ApiController
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'chat_type' => [
                'required',
                Rule::in(Chat::TYPES),
            ],
            'message_type' => [
                'required',
                Rule::in(ChatMessage::TYPES),
            ],
            'text' => 'required|string',
            'to' => 'required',
        ]);

        switch ($request->get('chat_type')) {
            case Chat::TYPE_PRIVATE:
                $this->storePrivateMessage(
                    $request->get('message_type'),
                    User::findOrFail($request->get('to')),
                    $request->get('text')
                );
                break;
            default:
                abort(400, 'Не поддерживаемый тип чата');
        }
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
                $chats = $user->chats()
                    ->where('type', Chat::TYPE_PRIVATE)
                    ->get()
                    ->map(function ($query) {
                        return $query->id;
                    });

                $chat = Chat::query()
                    ->whereIn('id', $chats)
                    ->whereHas('users', function ($query) use ($to) {
                        return $query->where('user_id', $to->id);
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

                $this->storeTextMessage($chat, $text, $user);

                return $this->jsonResponse([]);

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
        return $chatMessage->save();
    }

    public function getUsersPrivateChats()
    {
        /** @var User $user */
        $user = Auth::user();
        return $this->jsonResponse($user->chats()->get());
    }

    public function getUserPrivateMessages($chatId)
    {
        $chatMessages = ChatMessage::query()->with(['author'])->where('chat_id', $chatId)->get();
        return $this->jsonResponse($chatMessages);
    }
}