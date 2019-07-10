<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'time_from'              => 'required|date_format:Y-m-d',
            'time_to'                => 'required|date_format:Y-m-d',
            'additional_information' => 'nullable|string|max:255'
        ];
    }
}
