<?php

namespace App\Http\Requests\Api;

use App\Enums\GenderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'age' => ['required', 'min:4', 'max:80', 'numeric'],
            'gender' => ['nullable', 'string', Rule::enum(GenderType::class)],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your name',
            'name.max' => 'Your name cannot exceed 255 characters',
            'email.required' => 'Please provide your email address',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address is already registered',
            'age.required' => 'Please provide your age',
            'age.min' => 'You must be at least 4 years old to register',
            'age.max' => 'Age cannot exceed 80 years',
            'age.numeric' => 'Age must be a number',
            'gender.in_array' => 'Gender must be male, female, or other',
            'password.required' => 'Please provide a password',
            'password.confirmed' => 'The password confirmation does not match',
        ];
    }
}
