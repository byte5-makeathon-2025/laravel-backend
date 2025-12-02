<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['sometimes', 'string', Rule::in(['high', 'medium', 'low'])],
            'latitude' => ['sometimes', 'float'],
            'longitude' => ['sometimes', 'float'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your name',
            'title.required' => 'Please provide a title for your wish',
            'title.max' => 'The wish title cannot exceed 255 characters',
            'description.required' => 'Please describe your wish',
            'priority.in' => 'Priority must be high, medium, or low',
        ];
    }
}
