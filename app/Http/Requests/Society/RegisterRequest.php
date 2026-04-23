<?php

namespace App\Http\Requests\Society;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'phone'          => 'required|string|max:20',
            'society_id'     => 'required|exists:societies,id',
            'unit_number'    => 'required|string|max:50',
            'document'       => 'required|file|mimes:png,jpg,jpeg,pdf|max:5120',
            'password'       => ['required', 'confirmed', Password::min(6)],
        ];
    }
}
