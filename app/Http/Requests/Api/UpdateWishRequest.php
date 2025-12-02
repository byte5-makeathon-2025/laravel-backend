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
            'description' => ['sometimes', 'nullable', 'string'],
            'priority' => ['sometimes', 'string', Rule::in(['high', 'medium', 'low'])],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'granted', 'denied', 'in_progress'])],
            'street' => ['sometimes', 'string', 'max:255'],
            'house_number' => ['sometimes', 'string', 'max:50'],
            'postal_code' => ['sometimes', 'string', 'max:20'],
            'city' => ['sometimes', 'string', 'max:100'],
            'country' => ['sometimes', 'string', 'max:100'],
            'product_name' => ['sometimes', 'string', 'max:500'],
            'product_sku' => ['sometimes', 'nullable', 'string', 'max:50'],
            'product_image' => ['sometimes', 'nullable', 'url', 'max:1000'],
            'product_weight' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'product_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'The wish title cannot exceed 255 characters',
            'priority.in' => 'Priority must be high, medium, or low',
            'status.in' => 'Status must be pending, granted, denied, or in_progress',
            'product_name.max' => 'Product name cannot exceed 500 characters',
            'product_image.url' => 'Product image must be a valid URL',
            'product_weight.numeric' => 'Product weight must be a number',
            'product_price.numeric' => 'Product price must be a number',
        ];
    }
}
