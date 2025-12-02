<?php

use App\Actions\GeocodeAddressAction;
use Illuminate\Support\Facades\Http;
use Exception;

uses(Tests\TestCase::class);

test('it returns coordinates for valid address', function () {
    Http::fake([
        'https://nominatim.openstreetmap.org/*' => Http::response([
            [
                'lat' => '53.5511',
                'lon' => '9.9937',
                'display_name' => 'Hamburg, Deutschland',
            ]
        ], 200)
    ]);

    $action = new GeocodeAddressAction();
    $result = $action->execute('Hamburg');

    expect($result['address'])->toBe('Hamburg');
    expect($result['lat'])->toBe('53.5511');
    expect($result['lon'])->toBe('9.9937');
});

test('it throws exception if no address given', function () {
    $action = new GeocodeAddressAction();

    expect(fn() => $action->execute(''))
        ->toThrow(Exception::class);
});

test('it throws exception if no results found', function () {
    Http::fake([
        'https://nominatim.openstreetmap.org/*' => Http::response([], 200)
    ]);

    $action = new GeocodeAddressAction();

    expect(fn() => $action->execute('UnbekannteAdresse'))
        ->toThrow(Exception::class);
});

test('it throws exception if request failed', function () {
    Http::fake([
        'https://nominatim.openstreetmap.org/*' => Http::response(null, 500)
    ]);

    $action = new GeocodeAddressAction();

    expect(fn() => $action->execute('Hamburg'))
        ->toThrow(Exception::class);
});
