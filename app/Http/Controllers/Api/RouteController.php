<?php

namespace App\Http\Controllers\Api;

use App\Actions\TravelingSalesmanAction;
use App\Http\Controllers\Controller;
use App\Models\Wish;
use Illuminate\Support\Collection;

class RouteController extends Controller
{
    public function index(TravelingSalesmanAction $travelingSalesmanAction)
    {
        $wishes = Wish::all();

        $santaLocation = [
            "name" => "Santa",
            "coords" => [
                0,
                90
            ]
        ];

        $coordinates = [$santaLocation["coords"], ...$wishes->pluck('coordinates')->toArray()];


        $list = $travelingSalesmanAction->execute($coordinates);

        $new = [$santaLocation, ...$wishes->map(fn($wish) => ["name" => $wish->name, "coords" => $wish->coordinates])->toArray()];
        // Call TSM magic here
        $sorted = [];
        foreach ($list as $key => $value) {
            $sorted[] = [
                "pos" => $key + 1,
                ...$new[$value]];
        }

        return response()->json([
            "coordinates" => $sorted,
        ]);
    }
}
