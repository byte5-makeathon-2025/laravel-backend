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
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:50'],
            'postal_code' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['sometimes', 'string', Rule::in(['high', 'medium', 'low'])],
            'product_name' => ['required', 'string', 'max:500'],
            'product_sku' => ['nullable', 'string', 'max:50'],
            'product_image' => ['nullable', 'url', 'max:1000'],
            'product_weight' => ['nullable', 'numeric', 'min:0'],
            'product_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your name',
            'street.required' => 'Please provide a street name',
            'house_number.required' => 'Please provide a house number',
            'postal_code.required' => 'Please provide a postal code',
            'city.required' => 'Please provide a city',
            'country.required' => 'Please provide a country',
            'title.required' => 'Please provide a title for your wish',
            'title.max' => 'The wish title cannot exceed 255 characters',
            'priority.in' => 'Priority must be high, medium, or low',
            'product_name.required' => 'Please select a product for your wish',
            'product_name.max' => 'Product name cannot exceed 500 characters',
            'product_image.url' => 'Product image must be a valid URL',
            'product_weight.numeric' => 'Product weight must be a number',
            'product_price.numeric' => 'Product price must be a number',
        ];
    }
}
