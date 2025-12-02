<?php

namespace App\Models;

use Database\Factories\WishFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wish extends Model
{
    /** @use HasFactory<WishFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'description',
        'priority',
        'status',
        'house_number',
        'street',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'tracking_number',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wish) {
            if (empty($wish->tracking_number)) {
                $wish->tracking_number = static::generateTrackingNumber();
            }
        });
    }

    protected static function generateTrackingNumber(): int
    {
        do {
            // Generate a unique integer tracking number (8-10 digits)
            $trackingNumber = rand(10000000, 9999999999);
        } while (static::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }
}
