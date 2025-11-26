<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please provide your email address',
            'email.email' => 'Please provide a valid email address',
            'password.required' => 'Please provide your password',
            'password.min' => 'Your password must be at least 8 characters',
        ];
    }
}
