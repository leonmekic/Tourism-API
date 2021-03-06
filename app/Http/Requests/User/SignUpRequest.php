<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'name'                 => 'required|string',
            'phone_number'         => 'required|string',
            'email'                => 'required|string|email|unique:users,email',
            'password'             => 'required|string|confirmed',
            'terms_and_conditions' => 'required|accepted',
            'app_id'               => 'required|integer|exists:apps,id'
        ];
    }
}
