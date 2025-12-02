<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GeocodeAddressAction {
    public function execute(string $address): array
    {
        if (!$address) {
            throw new \Exception("Keine Adresse übergeben.");
        }

        $response = Http::get('https://nominatim.openstreetmap.org/search', [
            'q'      => $address,
            'format' => 'json',
            'limit'  => 1,
        ]);

        if ($response->failed()) {
            throw new \Exception("No Response");
        }

        $data = $response->json();

        if (empty($data)) {
            throw new \Exception("No Data");
        }

        return [
            'address' => $address,
            'lat'     => $data[0]['lat'],
            'lon'     => $data[0]['lon'],
            'raw'     => $data[0],
        ];
    }
}

