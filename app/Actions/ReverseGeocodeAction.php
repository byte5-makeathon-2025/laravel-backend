<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Exception;

class ReverseGeocodeAction
{
    /**
     * Führt die Action aus.
     *
     * @param float $lat
     * @param float $lon
     * @return array
     * @throws Exception
     */
    public function execute(float $lat, float $lon): array
    {
        $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
            'lat'    => $lat,
            'lon'    => $lon,
            'format' => 'json',
        ]);

        if ($response->failed()) {
            throw new Exception("No Response");
        }

        $data = $response->json();

        if (empty($data) || !isset($data['display_name'])) {
            throw new Exception("No Data");
        }

        return [
            'lat'     => $lat,
            'lon'     => $lon,
            'address' => $data['display_name'],
            'raw'     => $data,
        ];
    }
}
