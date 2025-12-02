<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWishRequest;
use App\Http\Requests\Api\UpdateWishRequest;
use App\Models\Wish;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class WishController extends Controller
{
    #[OA\Post(
        path: '/api/wishes',
        description: 'Create a new wish (no authentication required)',
        summary: 'Create a new wish',
        requestBody: new OA\RequestBody(
            description: 'Wish data',
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'title', 'description'],
                properties: [
                    new OA\Property(property: 'name', description: 'Name of person making the wish', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'title', description: 'Wish title', type: 'string', example: 'New Bicycle'),
                    new OA\Property(property: 'description', description: 'Detailed wish description', type: 'string', example: 'I would love a red bicycle'),
                    new OA\Property(property: 'priority', description: 'Priority level (defaults to medium)', type: 'string', enum: ['high', 'medium', 'low'], example: 'high'),
                    new OA\Property(property: 'house_number', description: 'House number', type: 'string', example: '123'),
                    new OA\Property(property: 'street', description: 'Street name', type: 'string', example: 'Main Street'),
                    new OA\Property(property: 'city', description: 'City name', type: 'string', example: 'New York'),
                    new OA\Property(property: 'state', description: 'State or province', type: 'string', example: 'NY'),
                    new OA\Property(property: 'country', description: 'Country name', type: 'string', example: 'USA'),
                    new OA\Property(property: 'postal_code', description: 'Postal code', type: 'string', example: '10001'),
                    new OA\Property(property: 'latitude', description: 'Latitude coordinate', type: 'number', format: 'float', example: 40.7128),
                    new OA\Property(property: 'longitude', description: 'Longitude coordinate', type: 'number', format: 'float', example: -74.0060),
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
                                new OA\Property(property: 'tracking_number', type: 'integer', example: 12345678),
                                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                new OA\Property(property: 'description', type: 'string', example: 'I would love a red bicycle'),
                                new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ],
                            type: 'object'
                        ),
                        new OA\Property(property: 'success_url', type: 'string', example: 'http://localhost/wish/success/12345678'),
                        new OA\Property(property: 'tracking_url', type: 'string', example: 'http://localhost/track/12345678'),
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
            'success_url' => route('wish.success', $wish->tracking_number),
            'tracking_url' => route('tracking.show', $wish->tracking_number),
        ], 201);
    }

    #[OA\Get(
        path: '/api/wishes/{id}',
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
                                new OA\Property(property: 'tracking_number', type: 'integer', nullable: true, example: 12345678),
                                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                new OA\Property(property: 'description', type: 'string', example: 'I would love a red bicycle'),
                                new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                new OA\Property(property: 'house_number', type: 'string', nullable: true, example: '123'),
                                new OA\Property(property: 'street', type: 'string', nullable: true, example: 'Main Street'),
                                new OA\Property(property: 'city', type: 'string', nullable: true, example: 'New York'),
                                new OA\Property(property: 'state', type: 'string', nullable: true, example: 'NY'),
                                new OA\Property(property: 'country', type: 'string', nullable: true, example: 'USA'),
                                new OA\Property(property: 'postal_code', type: 'string', nullable: true, example: '10001'),
                                new OA\Property(property: 'latitude', type: 'number', format: 'float', nullable: true, example: 40.7128),
                                new OA\Property(property: 'longitude', type: 'number', format: 'float', nullable: true, example: -74.0060),
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
        description: 'Update a wish (only Santa and Elfs can update wishes)',
        summary: 'Update a wish',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            description: 'Wish update data',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', description: 'Wish title', type: 'string', example: 'Updated Bicycle'),
                    new OA\Property(property: 'description', description: 'Detailed wish description', type: 'string', example: 'I would love a blue bicycle'),
                    new OA\Property(property: 'priority', type: 'string', enum: ['high', 'medium', 'low'], example: 'medium'),
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'granted', 'denied', 'in_progress', 'delivered'], example: 'granted'),
                    new OA\Property(property: 'house_number', description: 'House number', type: 'string', example: '123'),
                    new OA\Property(property: 'street', description: 'Street name', type: 'string', example: 'Main Street'),
                    new OA\Property(property: 'city', description: 'City name', type: 'string', example: 'New York'),
                    new OA\Property(property: 'state', description: 'State or province', type: 'string', example: 'NY'),
                    new OA\Property(property: 'country', description: 'Country name', type: 'string', example: 'USA'),
                    new OA\Property(property: 'postal_code', description: 'Postal code', type: 'string', example: '10001'),
                    new OA\Property(property: 'latitude', description: 'Latitude coordinate', type: 'number', format: 'float', example: 40.7128),
                    new OA\Property(property: 'longitude', description: 'Longitude coordinate', type: 'number', format: 'float', example: -74.0060),
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
                                    new OA\Property(property: 'tracking_number', type: 'integer', nullable: true, example: 12345678),
                                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                    new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                    new OA\Property(property: 'description', type: 'string', example: 'I would love a red bicycle'),
                                    new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                    new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                    new OA\Property(property: 'house_number', type: 'string', nullable: true, example: '123'),
                                    new OA\Property(property: 'street', type: 'string', nullable: true, example: 'Main Street'),
                                    new OA\Property(property: 'city', type: 'string', nullable: true, example: 'New York'),
                                    new OA\Property(property: 'state', type: 'string', nullable: true, example: 'NY'),
                                    new OA\Property(property: 'country', type: 'string', nullable: true, example: 'USA'),
                                    new OA\Property(property: 'postal_code', type: 'string', nullable: true, example: '10001'),
                                    new OA\Property(property: 'latitude', type: 'number', format: 'float', nullable: true, example: 40.7128),
                                    new OA\Property(property: 'longitude', type: 'number', format: 'float', nullable: true, example: -74.0060),
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
}
