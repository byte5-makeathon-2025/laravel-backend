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
               //$wish->latitude = rand(47.2, 55.0);
               $randomFloat = new Randomizer();
               $wish->latitude = $randomFloat->nextFloat();
           }

           if (!$wish->longitude) {
               //$wish->longitude = rand(8.0, 15.0);
               $randomFloat = new Randomizer();
               $wish->latitude = $randomFloat->nextFloat();
           }
        });
    }
}
