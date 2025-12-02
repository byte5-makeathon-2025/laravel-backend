<?php

namespace App\Http\Controllers\Api;

use App\Actions\GeocodeAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LocationRequest;
use App\Http\Resources\LocationResource;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function index(LocationRequest $request, GeocodeAddressAction $geocodeAddressAction)
    {
        $validated = $request->validated();

        try {
            $result = $geocodeAddressAction->execute($validated['address']);
            return new LocationResource($result);
        } catch (\Throwable $th) {
            return new JsonResponse([
                "message" => "Could not get geocode for address",
                "errors" => [
                    "geocodeAddressAction" => $th->getMessage()
                ]
            ], 422);
        }
    }
}
