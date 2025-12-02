<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wish;

class RouteController extends Controller
{
    public function index() {
        $wishes = Wish::where('status', 'granted')->get();

        $santaLocation = [
            "address" => "Santa's House",
            "coordinates" => [
                "lat" => 90,
                "long" => 0
            ]
        ];

        // Call TSM magic here


    }
}
