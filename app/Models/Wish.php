<?php

namespace App\Models;

use Database\Factories\WishFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Random\Randomizer;

class Wish extends Model
{
    /** @use HasFactory<WishFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (self $wish) {
           if (!$wish->latitude) {
               $randomFloat = new Randomizer();
               $wish->latitude = $randomFloat->getFloat(47.2, 55.0);
           }

           if (!$wish->longitude) {
               $randomFloat = new Randomizer();
               $wish->longitude = $randomFloat->getFloat(8.0, 15.0);
           }
        });
    }
}
