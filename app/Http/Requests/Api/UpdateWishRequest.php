<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'priority' => ['sometimes', 'string', Rule::in(['high', 'medium', 'low'])],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'granted', 'denied', 'in_progress'])],
        ];
    }
}
