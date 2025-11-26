<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'priority' => ['sometimes', 'string', Rule::in(['high', 'medium', 'low'])],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'granted', 'denied', 'in_progress'])],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'The wish title cannot exceed 255 characters',
            'priority.in' => 'Priority must be high, medium, or low',
            'status.in' => 'Status must be pending, granted, denied, or in_progress',
        ];
    }
}
