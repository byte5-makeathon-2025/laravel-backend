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

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'coordinates' => 'array',
    ];
}
