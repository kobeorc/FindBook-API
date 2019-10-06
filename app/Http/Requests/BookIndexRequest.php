<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookIndexRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'categoriesIds'   => 'sometimes|array',
            'categoriesIds.*' => 'integer',
            'bookIds'         => 'sometimes|array',
            'bookIds.*'       => 'integer',
            'publishersIds'   => 'sometimes|array',
            'publishersIds.*' => 'integer',
            'authorsIds'      => 'sometimes|array',
            'authorsIds.*'    => 'integer',
            'square_top'      => 'required_with:square_left,square_bottom,square_right',
            'square_left'     => 'required_with:square_top,square_bottom,square_right',
            'square_right'    => 'required_with:square_top,square_bottom,square_left',
            'square_bottom'   => 'required_with:square_top,square_left,square_right',
            'latitude'        => 'required_with:longitude',
            'longitude'       => 'required_with:latitude',
            'except_me'       => 'sometimes|boolean',
        ];
    }
}
