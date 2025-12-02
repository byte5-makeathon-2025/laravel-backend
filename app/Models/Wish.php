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
        'street',
        'house_number',
        'postal_code',
        'city',
        'country',
        'title',
        'description',
        'priority',
        'status',
        'product_name',
        'product_sku',
        'product_image',
        'product_weight',
        'product_price',
        'bribe_offer',
        'bribe_status',
        'bribe_submitted_at',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'bribe_submitted_at' => 'datetime',
    ];
}
