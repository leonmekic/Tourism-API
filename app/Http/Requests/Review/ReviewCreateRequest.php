<?php

namespace App\Http\Requests\Review;

use App\Rules\StarsValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewCreateRequest extends FormRequest
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
            'stars'   => ['required','integer', new StarsValidationRule],
            'comment' => 'required|max:255',
        ];
    }
}
