<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWishRequest;
use App\Http\Requests\Api\UpdateWishRequest;
use App\Models\Wish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WishController extends Controller
{
    #[OA\Post(
        path: '/api/wishes',
        operationId: 'storeWish',
        description: 'Create a new wish (no authentication required)',
        summary: 'Create a new wish',
        requestBody: new OA\RequestBody(
            description: 'Wish data',
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'street', 'house_number', 'postal_code', 'city', 'country', 'title', 'product_name'],
                properties: [
                    new OA\Property(property: 'name', description: 'Name of person making the wish', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'street', description: 'Street name', type: 'string', example: 'Main Street'),
                    new OA\Property(property: 'house_number', description: 'House number', type: 'string', example: '123'),
                    new OA\Property(property: 'postal_code', description: 'Postal/ZIP code', type: 'string', example: '12345'),
                    new OA\Property(property: 'city', description: 'City name', type: 'string', example: 'Berlin'),
                    new OA\Property(property: 'country', description: 'Country name', type: 'string', example: 'Germany'),
                    new OA\Property(property: 'title', description: 'Wish title', type: 'string', example: 'New Bicycle'),
                    new OA\Property(property: 'description', description: 'Optional additional description', type: 'string', nullable: true, example: 'I would love a red bicycle'),
                    new OA\Property(property: 'priority', description: 'Priority level (defaults to medium)', type: 'string', enum: ['high', 'medium', 'low'], example: 'high'),
                    new OA\Property(property: 'product_name', description: 'Name of the product wished for', type: 'string', example: 'Apple iPhone 15 Pro'),
                    new OA\Property(property: 'product_sku', description: 'Product SKU from Best Buy', type: 'string', nullable: true, example: '6525407'),
                    new OA\Property(property: 'product_image', description: 'Product image URL', type: 'string', nullable: true, example: 'https://pisces.bbystatic.com/image2/BestBuy_US/images/products/6525/6525407_sd.jpg'),
                    new OA\Property(property: 'product_weight', description: 'Product weight in pounds', type: 'number', nullable: true, example: 0.4),
                    new OA\Property(property: 'product_price', description: 'Product price in USD', type: 'number', nullable: true, example: 999.99),
                ]
            )
        ),
        tags: ['Wishes'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Wish created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Wish successfully created'),
                        new OA\Property(
                            property: 'wish',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'street', type: 'string', example: 'Main Street'),
                                new OA\Property(property: 'house_number', type: 'string', example: '123'),
                                new OA\Property(property: 'postal_code', type: 'string', example: '12345'),
                                new OA\Property(property: 'city', type: 'string', example: 'Berlin'),
                                new OA\Property(property: 'country', type: 'string', example: 'Germany'),
                                new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                new OA\Property(property: 'description', type: 'string', nullable: true, example: 'I would love a red bicycle'),
                                new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                new OA\Property(property: 'product_name', type: 'string', example: 'Apple iPhone 15 Pro'),
                                new OA\Property(property: 'product_sku', type: 'string', nullable: true, example: '6525407'),
                                new OA\Property(property: 'product_image', type: 'string', nullable: true, example: 'https://pisces.bbystatic.com/image2/BestBuy_US/images/products/6525/6525407_sd.jpg'),
                                new OA\Property(property: 'product_weight', type: 'number', nullable: true, example: 0.4),
                                new OA\Property(property: 'product_price', type: 'number', nullable: true, example: 999.99),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The name field is required.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function store(StoreWishRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $wish = Wish::create($validated);
        $wish->refresh();

        return response()->json([
            'message' => 'Wish successfully created',
            'wish' => $wish,
        ], 201);
    }

    #[OA\Get(
        path: '/api/wishes/{id}',
        operationId: 'showWish',
        description: 'Retrieve details of a specific wish (requires view_all_wishes permission - Santa and Elfs only)',
        summary: 'Get a specific wish',
        security: [['sanctum' => []]],
        tags: ['Wishes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Wish ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wish retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'wish',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'street', type: 'string', example: 'Main Street'),
                                new OA\Property(property: 'house_number', type: 'string', example: '123'),
                                new OA\Property(property: 'postal_code', type: 'string', example: '12345'),
                                new OA\Property(property: 'city', type: 'string', example: 'Berlin'),
                                new OA\Property(property: 'country', type: 'string', example: 'Germany'),
                                new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                new OA\Property(property: 'description', type: 'string', nullable: true, example: 'I would love a red bicycle'),
                                new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                new OA\Property(property: 'product_name', type: 'string', example: 'Apple iPhone 15 Pro'),
                                new OA\Property(property: 'product_sku', type: 'string', nullable: true, example: '6525407'),
                                new OA\Property(property: 'product_image', type: 'string', nullable: true, example: 'https://pisces.bbystatic.com/image2/BestBuy_US/images/products/6525/6525407_sd.jpg'),
                                new OA\Property(property: 'product_weight', type: 'number', nullable: true, example: 0.4),
                                new OA\Property(property: 'product_price', type: 'number', nullable: true, example: 999.99),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Insufficient permissions',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Wish not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Wish not found'),
                    ]
                )
            ),
        ]
    )]
    public function show(Wish $wish): JsonResponse
    {
        return response()->json([
            'wish' => $wish,
        ]);
    }

    #[OA\Put(
        path: '/api/wishes/{id}',
        operationId: 'updateWish',
        description: 'Update a wish (only Santa and Elfs can update wishes)',
        summary: 'Update a wish',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            description: 'Wish update data',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', description: 'Wish title', type: 'string', example: 'Updated Bicycle'),
                    new OA\Property(property: 'description', description: 'Detailed wish description', type: 'string', nullable: true, example: 'I would love a blue bicycle'),
                    new OA\Property(property: 'priority', type: 'string', enum: ['high', 'medium', 'low'], example: 'medium'),
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'granted', 'denied', 'in_progress'], example: 'granted'),
                    new OA\Property(property: 'street', description: 'Street name', type: 'string', example: 'Main Street'),
                    new OA\Property(property: 'house_number', description: 'House number', type: 'string', example: '123'),
                    new OA\Property(property: 'postal_code', description: 'Postal/ZIP code', type: 'string', example: '12345'),
                    new OA\Property(property: 'city', description: 'City name', type: 'string', example: 'Berlin'),
                    new OA\Property(property: 'country', description: 'Country name', type: 'string', example: 'Germany'),
                    new OA\Property(property: 'product_name', description: 'Name of the product wished for', type: 'string', example: 'Apple iPhone 15 Pro'),
                    new OA\Property(property: 'product_sku', description: 'Product SKU from Best Buy', type: 'string', nullable: true, example: '6525407'),
                    new OA\Property(property: 'product_image', description: 'Product image URL', type: 'string', nullable: true, example: 'https://pisces.bbystatic.com/image2/BestBuy_US/images/products/6525/6525407_sd.jpg'),
                    new OA\Property(property: 'product_weight', description: 'Product weight in pounds', type: 'number', nullable: true, example: 0.4),
                    new OA\Property(property: 'product_price', description: 'Product price in USD', type: 'number', nullable: true, example: 999.99),
                ]
            )
        ),
        tags: ['Wishes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Wish ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wish updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'wish', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Insufficient permissions',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Wish not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Wish not found'),
                    ]
                )
            ),
        ]
    )]
    public function update(UpdateWishRequest $request, Wish $wish): JsonResponse
    {
        $validated = $request->validated();
        $wish->update($validated);

        return response()->json([
            'wish' => $wish,
        ]);
    }

    #[OA\Delete(
        path: '/api/wishes/{id}',
        description: 'Soft delete a wish (only Santa and Elfs can delete wishes)',
        summary: 'Delete a wish',
        security: [['sanctum' => []]],
        tags: ['Wishes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Wish ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wish deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Wish deleted successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Insufficient permissions',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Wish not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Wish not found'),
                    ]
                )
            ),
        ]
    )]
    public function destroy(Wish $wish): JsonResponse
    {
        $wish->delete();

        return response()->json([
            'message' => 'Wish deleted successfully',
        ]);
    }

    #[OA\Get(
        path: '/api/wishes/all',
        operationId: 'getAllWishes',
        description: 'Retrieve all wishes with pagination (requires view_all_wishes permission - Santa and Elfs only)',
        summary: 'Get all wishes (paginated)',
        security: [['sanctum' => []]],
        tags: ['Wishes'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 15)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'All wishes retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                    new OA\Property(property: 'street', type: 'string', example: 'Main Street'),
                                    new OA\Property(property: 'house_number', type: 'string', example: '123'),
                                    new OA\Property(property: 'postal_code', type: 'string', example: '12345'),
                                    new OA\Property(property: 'city', type: 'string', example: 'Berlin'),
                                    new OA\Property(property: 'country', type: 'string', example: 'Germany'),
                                    new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'I would love a red bicycle'),
                                    new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                    new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                    new OA\Property(property: 'product_name', type: 'string', example: 'Apple iPhone 15 Pro'),
                                    new OA\Property(property: 'product_sku', type: 'string', nullable: true, example: '6525407'),
                                    new OA\Property(property: 'product_image', type: 'string', nullable: true, example: 'https://pisces.bbystatic.com/image2/BestBuy_US/images/products/6525/6525407_sd.jpg'),
                                    new OA\Property(property: 'product_weight', type: 'number', nullable: true, example: 0.4),
                                    new OA\Property(property: 'product_price', type: 'number', nullable: true, example: 999.99),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(property: 'first_page_url', type: 'string', example: 'http://localhost/api/wishes/all?page=1'),
                        new OA\Property(property: 'from', type: 'integer', example: 1),
                        new OA\Property(property: 'last_page', type: 'integer', example: 5),
                        new OA\Property(property: 'last_page_url', type: 'string', example: 'http://localhost/api/wishes/all?page=5'),
                        new OA\Property(
                            property: 'links',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'url', type: 'string', nullable: true),
                                    new OA\Property(property: 'label', type: 'string'),
                                    new OA\Property(property: 'active', type: 'boolean'),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(property: 'next_page_url', type: 'string', nullable: true, example: 'http://localhost/api/wishes/all?page=2'),
                        new OA\Property(property: 'path', type: 'string', example: 'http://localhost/api/wishes/all'),
                        new OA\Property(property: 'per_page', type: 'integer', example: 15),
                        new OA\Property(property: 'prev_page_url', type: 'string', nullable: true),
                        new OA\Property(property: 'to', type: 'integer', example: 15),
                        new OA\Property(property: 'total', type: 'integer', example: 68),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Insufficient permissions',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden'),
                    ]
                )
            ),
        ]
    )]
    public function allWishes(): JsonResponse
    {
        $wishes = Wish::latest()->paginate();

        return response()->json($wishes);
    }

    #[OA\Get(
        path: '/api/wishes/shopping-list',
        operationId: 'getShoppingList',
        description: 'Get aggregated shopping list grouped by product SKU with quantities and related wishes',
        summary: 'Get Santa\'s shopping list',
        security: [['sanctum' => []]],
        tags: ['Wishes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Shopping list retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'shopping_list',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'product_sku', type: 'string', example: '78'),
                                    new OA\Property(property: 'product_name', type: 'string', example: 'Apple MacBook Pro'),
                                    new OA\Property(property: 'product_image', type: 'string', nullable: true),
                                    new OA\Property(property: 'product_price', type: 'number', example: 1999.99),
                                    new OA\Property(property: 'product_weight', type: 'number', example: 1.5),
                                    new OA\Property(property: 'quantity', type: 'integer', example: 3),
                                    new OA\Property(property: 'total_price', type: 'number', example: 5999.97),
                                    new OA\Property(property: 'total_weight', type: 'number', example: 4.5),
                                    new OA\Property(
                                        property: 'wishes',
                                        type: 'array',
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: 'id', type: 'integer'),
                                                new OA\Property(property: 'name', type: 'string'),
                                                new OA\Property(property: 'title', type: 'string'),
                                                new OA\Property(property: 'city', type: 'string'),
                                                new OA\Property(property: 'status', type: 'string'),
                                            ],
                                            type: 'object'
                                        )
                                    ),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(property: 'total_items', type: 'integer', example: 15),
                        new OA\Property(property: 'total_cost', type: 'number', example: 12500.50),
                        new OA\Property(property: 'total_weight', type: 'number', example: 25.5),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Insufficient permissions'
            ),
        ]
    )]
    #[OA\Get(
        path: '/api/wishes/{id}/track',
        operationId: 'trackWish',
        description: 'Track the status of a wish (public, no authentication required)',
        summary: 'Track wish status',
        tags: ['Wishes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Wish ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wish status retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                        new OA\Property(property: 'status', type: 'string', example: 'pending'),
                        new OA\Property(property: 'product_name', type: 'string', example: 'Apple MacBook Pro'),
                        new OA\Property(property: 'product_image', type: 'string', nullable: true),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Wish not found'
            ),
        ]
    )]
    public function track(Wish $wish): JsonResponse
    {
        // Calculate queue position based on optimized route (distance from North Pole)
        $queuePosition = null;
        $totalInQueue = null;

        if ($wish->status === 'granted') {
            // Get all granted wishes with their addresses
            $grantedWishes = Wish::where('status', 'granted')
                ->select('id', 'city', 'country', 'postal_code')
                ->get();

            // Calculate distance from North Pole for each wish and sort
            $northPoleLat = 90;
            $northPoleLng = 0;

            $wishesWithDistance = $grantedWishes->map(function ($w) use ($northPoleLat, $northPoleLng) {
                // Use a simple geocoding approximation based on country/city
                // In production, you'd want to store lat/lng or use a geocoding service
                $coords = $this->approximateCoordinates($w->city, $w->country);
                $distance = $this->calculateHaversineDistance(
                    $northPoleLat,
                    $northPoleLng,
                    $coords['lat'],
                    $coords['lng']
                );

                return [
                    'id' => $w->id,
                    'distance' => $distance,
                ];
            })->sortBy('distance')->values();

            // Find position in sorted list
            $queuePosition = $wishesWithDistance->search(fn ($w) => $w['id'] === $wish->id);
            if ($queuePosition !== false) {
                $queuePosition += 1; // 1-indexed position
            }
            $totalInQueue = $wishesWithDistance->count();
        }

        return response()->json([
            'id' => $wish->id,
            'title' => $wish->title,
            'status' => $wish->status,
            'product_name' => $wish->product_name,
            'product_image' => $wish->product_image,
            'product_weight' => $wish->product_weight,
            'street' => $wish->street,
            'house_number' => $wish->house_number,
            'postal_code' => $wish->postal_code,
            'city' => $wish->city,
            'country' => $wish->country,
            'created_at' => $wish->created_at,
            'queue_position' => $queuePosition,
            'total_in_queue' => $totalInQueue,
            'bribe_offer' => $wish->bribe_offer,
            'bribe_status' => $wish->bribe_status,
        ]);
    }

    /**
     * Calculate Haversine distance between two points in km
     */
    private function calculateHaversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Approximate coordinates based on city/country
     * Uses a simple lookup for common countries, defaults to central position
     */
    private function approximateCoordinates(string $city, string $country): array
    {
        // Country approximate center coordinates
        $countryCoords = [
            'Germany' => ['lat' => 51.1657, 'lng' => 10.4515],
            'Deutschland' => ['lat' => 51.1657, 'lng' => 10.4515],
            'France' => ['lat' => 46.2276, 'lng' => 2.2137],
            'United Kingdom' => ['lat' => 55.3781, 'lng' => -3.4360],
            'UK' => ['lat' => 55.3781, 'lng' => -3.4360],
            'United States' => ['lat' => 37.0902, 'lng' => -95.7129],
            'USA' => ['lat' => 37.0902, 'lng' => -95.7129],
            'Canada' => ['lat' => 56.1304, 'lng' => -106.3468],
            'Spain' => ['lat' => 40.4637, 'lng' => -3.7492],
            'Italy' => ['lat' => 41.8719, 'lng' => 12.5674],
            'Netherlands' => ['lat' => 52.1326, 'lng' => 5.2913],
            'Belgium' => ['lat' => 50.5039, 'lng' => 4.4699],
            'Austria' => ['lat' => 47.5162, 'lng' => 14.5501],
            'Switzerland' => ['lat' => 46.8182, 'lng' => 8.2275],
            'Poland' => ['lat' => 51.9194, 'lng' => 19.1451],
            'Sweden' => ['lat' => 60.1282, 'lng' => 18.6435],
            'Norway' => ['lat' => 60.4720, 'lng' => 8.4689],
            'Denmark' => ['lat' => 56.2639, 'lng' => 9.5018],
            'Finland' => ['lat' => 61.9241, 'lng' => 25.7482],
            'Australia' => ['lat' => -25.2744, 'lng' => 133.7751],
            'Japan' => ['lat' => 36.2048, 'lng' => 138.2529],
            'China' => ['lat' => 35.8617, 'lng' => 104.1954],
            'Brazil' => ['lat' => -14.2350, 'lng' => -51.9253],
            'Mexico' => ['lat' => 23.6345, 'lng' => -102.5528],
            'India' => ['lat' => 20.5937, 'lng' => 78.9629],
            'Russia' => ['lat' => 61.5240, 'lng' => 105.3188],
            'South Africa' => ['lat' => -30.5595, 'lng' => 22.9375],
        ];

        // Try to find country in lookup
        foreach ($countryCoords as $key => $coords) {
            if (stripos($country, $key) !== false || stripos($key, $country) !== false) {
                return $coords;
            }
        }

        // Default to central Europe if country not found
        return ['lat' => 50.0, 'lng' => 10.0];
    }

    public function shoppingList(): JsonResponse
    {
        $wishes = Wish::whereNotNull('product_sku')
            ->where('status', 'granted')
            ->get();

        $grouped = $wishes->groupBy('product_sku')->map(function ($items, $sku) {
            $first = $items->first();
            $quantity = $items->count();
            $price = (float) ($first->product_price ?? 0);
            $weight = (float) ($first->product_weight ?? 0);

            return [
                'product_sku' => $sku,
                'product_name' => $first->product_name,
                'product_image' => $first->product_image,
                'product_price' => $price,
                'product_weight' => $weight,
                'quantity' => $quantity,
                'total_price' => round($price * $quantity, 2),
                'total_weight' => round($weight * $quantity, 2),
                'wishes' => $items->map(fn ($wish) => [
                    'id' => $wish->id,
                    'name' => $wish->name,
                    'title' => $wish->title,
                    'city' => $wish->city,
                    'status' => $wish->status,
                ])->values(),
            ];
        })->sortByDesc('quantity')->values();

        $totalItems = $grouped->sum('quantity');
        $totalCost = $grouped->sum('total_price');
        $totalWeight = $grouped->sum('total_weight');

        return response()->json([
            'shopping_list' => $grouped,
            'total_items' => $totalItems,
            'total_cost' => round($totalCost, 2),
            'total_weight' => round($totalWeight, 2),
        ]);
    }

    #[OA\Post(
        path: '/api/wishes/{id}/bribe',
        operationId: 'submitBribe',
        description: 'Submit a bribe offer to Santa for priority delivery (public, no authentication required)',
        summary: 'Submit bribe offer',
        requestBody: new OA\RequestBody(
            description: 'Bribe offer details',
            required: true,
            content: new OA\JsonContent(
                required: ['offer'],
                properties: [
                    new OA\Property(property: 'offer', description: 'The bribe offer (e.g., cookies, milk, carrots for reindeer)', type: 'string', example: '12 chocolate chip cookies and a glass of warm milk'),
                ]
            )
        ),
        tags: ['Wishes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Wish ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bribe offer submitted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Your offer has been sent to Santa!'),
                        new OA\Property(property: 'bribe_status', type: 'string', example: 'pending'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bribe already submitted'
            ),
            new OA\Response(
                response: 404,
                description: 'Wish not found'
            ),
        ]
    )]
    public function submitBribe(Request $request, Wish $wish): JsonResponse
    {
        // Check if bribe already submitted
        if ($wish->bribe_offer) {
            return response()->json([
                'message' => 'You have already submitted an offer to Santa!',
                'bribe_status' => $wish->bribe_status,
            ], 400);
        }

        $validated = $request->validate([
            'offer' => 'required|string|max:1000',
        ]);

        $wish->update([
            'bribe_offer' => $validated['offer'],
            'bribe_status' => 'pending',
            'bribe_submitted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Your offer has been sent to Santa!',
            'bribe_status' => 'pending',
        ]);
    }

    #[OA\Put(
        path: '/api/wishes/{id}/bribe',
        operationId: 'respondToBribe',
        description: 'Accept or reject a bribe offer (Santa only)',
        summary: 'Respond to bribe offer',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            description: 'Bribe response',
            required: true,
            content: new OA\JsonContent(
                required: ['status'],
                properties: [
                    new OA\Property(property: 'status', description: 'Accept or reject the bribe', type: 'string', enum: ['accepted', 'rejected']),
                ]
            )
        ),
        tags: ['Wishes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Wish ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bribe response recorded',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'bribe_status', type: 'string'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden'
            ),
            new OA\Response(
                response: 404,
                description: 'Wish not found'
            ),
        ]
    )]
    public function respondToBribe(Request $request, Wish $wish): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $wish->update([
            'bribe_status' => $validated['status'],
        ]);

        $message = $validated['status'] === 'accepted'
            ? 'Ho ho ho! The offer has been accepted!'
            : 'Santa has politely declined the offer.';

        return response()->json([
            'message' => $message,
            'bribe_status' => $validated['status'],
        ]);
    }

    #[OA\Get(
        path: '/api/wishes/bribes',
        operationId: 'getPendingBribes',
        description: 'Get all wishes with pending bribe offers (Santa only)',
        summary: 'Get pending bribes',
        security: [['sanctum' => []]],
        tags: ['Wishes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pending bribes retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'bribes',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string'),
                                    new OA\Property(property: 'title', type: 'string'),
                                    new OA\Property(property: 'city', type: 'string'),
                                    new OA\Property(property: 'country', type: 'string'),
                                    new OA\Property(property: 'bribe_offer', type: 'string'),
                                    new OA\Property(property: 'bribe_status', type: 'string'),
                                    new OA\Property(property: 'bribe_submitted_at', type: 'string', format: 'date-time'),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden'
            ),
        ]
    )]
    public function getPendingBribes(): JsonResponse
    {
        $bribes = Wish::whereNotNull('bribe_offer')
            ->orderByRaw("CASE WHEN bribe_status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('bribe_submitted_at', 'desc')
            ->get()
            ->map(fn ($wish) => [
                'id' => $wish->id,
                'name' => $wish->name,
                'title' => $wish->title,
                'city' => $wish->city,
                'country' => $wish->country,
                'product_name' => $wish->product_name,
                'bribe_offer' => $wish->bribe_offer,
                'bribe_status' => $wish->bribe_status,
                'bribe_submitted_at' => $wish->bribe_submitted_at,
            ]);

        return response()->json([
            'bribes' => $bribes,
        ]);
    }
}
