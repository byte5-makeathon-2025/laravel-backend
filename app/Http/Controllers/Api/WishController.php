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
                                new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                new OA\Property(property: 'description', type: 'string', example: 'I would love a red bicycle'),
                                new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                new OA\Property(property: 'status', type: 'string', example: 'pending'),
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
        description: 'Retrieve details of a specific wish (public access)',
        summary: 'Get a specific wish',
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
                                new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                new OA\Property(property: 'description', type: 'string', example: 'I would love a red bicycle'),
                                new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ],
                            type: 'object'
                        ),
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
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'granted', 'denied', 'in_progress'], example: 'granted'),
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
        if (! $request->user()->hasPermissionTo('update_wish')) {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        $validated = $request->validated();
        $wish->update($validated);

        return response()->json([
            'wish' => $wish->fresh(),
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
    public function destroy(Request $request, Wish $wish): JsonResponse
    {
        if (! $request->user()->hasPermissionTo('delete_wish')) {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        $wish->delete();

        return response()->json([
            'message' => 'Wish deleted successfully',
        ]);
    }

    #[OA\Get(
        path: '/api/wishes/all',
        description: 'Retrieve all wishes (requires view_all_wishes permission - Santa and Elfs only)',
        summary: 'Get all wishes',
        security: [['sanctum' => []]],
        tags: ['Wishes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'All wishes retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'wishes',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                    new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                    new OA\Property(property: 'description', type: 'string', example: 'I would love a red bicycle'),
                                    new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                    new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                ],
                                type: 'object'
                            )
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
        ]
    )]
    public function allWishes(): JsonResponse
    {
        $wishes = Wish::latest()->get();

        return response()->json([
            'wishes' => $wishes,
        ]);
    }
}
