<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'avatar'   => 'image|mimes:jpeg,jpg,png,gif',
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users|max:255',
            'password' => 'sometimes|min:6|confirmed|string',
        ];
    }
}
