<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $review = $this->route('review');

        return ($review->user_id == auth()->id());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stars'   => 'required|integer|max:5',
            'comment' => 'required|max:255',
        ];
    }
}
