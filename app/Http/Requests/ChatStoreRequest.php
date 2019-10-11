<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChatStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
        ];
    }
}
