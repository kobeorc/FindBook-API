<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ProfilePutToInventoryRequest extends FormRequest
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
            'book_id'             => 'sometimes|exists:books,id',
            'book_name'           => 'required|string',
            'book_description'    => 'sometimes|string|nullable',
            'year'                => 'sometimes|integer|nullable|digits:4|max:' . Carbon::now('Y'),
            'author_full_name'    => 'sometimes|array',
            'author_full_name.*'  => 'sometimes|string|nullable',
            'publisher_full_name' => 'sometimes|string|nullable',
            'categories_ids'      => 'sometimes|array',
            'categories_ids.*'    => 'sometimes|exists:categories,id|nullable',
            'images'              => 'sometimes|array',
            'images.*'            => 'sometimes|image',
            'latitude'            => 'required_with:longitude|regex:/^\-?[0-9]+\.([0-9]){0,7}$/',
            'longitude'           => 'required_with:latitude|regex:/^\-?[0-9]+\.([0-9]){0,7}$/',
            'address'             => 'sometimes|string',
        ];
    }
}
