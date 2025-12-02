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
            'status' => ['sometimes', 'string', Rule::in(['pending', 'granted', 'denied', 'in_progress', 'delivered'])],
            'house_number' => ['sometimes', 'string', 'max:50'],
            'street' => ['sometimes', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:255'],
            'state' => ['sometimes', 'string', 'max:255'],
            'country' => ['sometimes', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'string', 'max:20'],
            'latitude' => ['sometimes', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'The wish title cannot exceed 255 characters',
            'priority.in' => 'Priority must be high, medium, or low',
            'status.in' => 'Status must be pending, granted, denied, in_progress, or delivered',
            'house_number.max' => 'House number cannot exceed 50 characters',
            'street.max' => 'Street name cannot exceed 255 characters',
            'city.max' => 'City name cannot exceed 255 characters',
            'state.max' => 'State name cannot exceed 255 characters',
            'country.max' => 'Country name cannot exceed 255 characters',
            'postal_code.max' => 'Postal code cannot exceed 20 characters',
            'latitude.numeric' => 'Latitude must be a number',
            'latitude.between' => 'Latitude must be between -90 and 90',
            'longitude.numeric' => 'Longitude must be a number',
            'longitude.between' => 'Longitude must be between -180 and 180',
        ];
    }
}
