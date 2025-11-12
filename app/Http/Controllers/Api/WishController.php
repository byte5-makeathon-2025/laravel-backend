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
    #[OA\Get(
        path: '/api/wishes',
        description: 'Retrieve a list of wishes created by the authenticated user',
        summary: "Get authenticated user's wishes",
        security: [['sanctum' => []]],
        tags: ['Wishes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wishes retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'wishes',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
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
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $wishes = $request->user()->wishes()->latest()->get();

        return response()->json([
            'wishes' => $wishes,
        ]);
    }

    #[OA\Post(
        path: '/api/wishes',
        description: 'Create a new wish for the authenticated user',
        summary: 'Create a new wish',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            description: 'Wish data',
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'description'],
                properties: [
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
                        new OA\Property(
                            property: 'wish',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'user_id', type: 'integer', example: 1),
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
                        new OA\Property(property: 'message', type: 'string', example: 'The title field is required.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
        ]
    )]
    public function store(StoreWishRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $wish = $request->user()->wishes()->create($validated);
        $wish->refresh();

        return response()->json([
            'wish' => $wish,
        ], 201);
    }

    #[OA\Get(
        path: '/api/wishes/{id}',
        description: 'Retrieve details of a specific wish (users can only view their own wishes)',
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
                                new OA\Property(property: 'user_id', type: 'integer', example: 1),
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
                response: 403,
                description: 'Forbidden - Not your wish',
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
    public function show(Request $request, int $id): JsonResponse
    {
        $wish = Wish::find($id);

        if (! $wish) {
            return response()->json([
                'message' => 'Wish not found',
            ], 404);
        }

        if ($wish->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        return response()->json([
            'wish' => $wish,
        ]);
    }

    #[OA\Put(
        path: '/api/wishes/{id}',
        description: 'Update a wish (users can update their own wishes, Santa can update status of any wish)',
        summary: 'Update a wish',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            description: 'Wish update data',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', description: 'Wish title', type: 'string', example: 'Updated Bicycle'),
                    new OA\Property(property: 'description', description: 'Detailed wish description', type: 'string', example: 'I would love a blue bicycle'),
                    new OA\Property(property: 'priority', type: 'string', enum: ['high', 'medium', 'low'], example: 'medium'),
                    new OA\Property(property: 'status', description: 'Status (Santa can update this)', type: 'string', enum: ['pending', 'granted', 'denied', 'in_progress'], example: 'granted'),
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
                description: 'Forbidden - Not your wish or cannot update status',
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
    public function update(UpdateWishRequest $request, int $id): JsonResponse
    {
        $wish = Wish::find($id);

        if (! $wish) {
            return response()->json([
                'message' => 'Wish not found',
            ], 404);
        }

        $validated = $request->validated();
        $isSanta = $request->user()->hasPermissionTo('view_all_wishes');
        $isOwner = $wish->user_id === $request->user()->id;

        if (! $isOwner && ! $isSanta) {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        if (! $isOwner && $isSanta) {
            $validated = array_intersect_key($validated, ['status' => true]);
        }

        if (isset($validated['status']) && ! $isSanta && $isOwner) {
            unset($validated['status']);
        }

        $wish->update($validated);

        return response()->json([
            'wish' => $wish->fresh(),
        ]);
    }

    #[OA\Delete(
        path: '/api/wishes/{id}',
        description: 'Soft delete a wish (users can only delete their own wishes)',
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
                description: 'Forbidden - Not your wish',
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
    public function destroy(Request $request, int $id): JsonResponse
    {
        $wish = Wish::find($id);

        if (! $wish) {
            return response()->json([
                'message' => 'Wish not found',
            ], 404);
        }

        if ($wish->user_id !== $request->user()->id) {
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
        description: 'Retrieve all wishes from all users (requires view_all_wishes permission)',
        summary: 'Get all wishes (Santa only)',
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
                                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                                    new OA\Property(property: 'title', type: 'string', example: 'New Bicycle'),
                                    new OA\Property(property: 'description', type: 'string', example: 'I would love a red bicycle'),
                                    new OA\Property(property: 'priority', type: 'string', example: 'high'),
                                    new OA\Property(property: 'status', type: 'string', example: 'pending'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                    new OA\Property(
                                        property: 'user',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                            new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                                        ],
                                        type: 'object'
                                    ),
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
        $wishes = Wish::with('user:id,name,email')->latest()->get();

        return response()->json([
            'wishes' => $wishes,
        ]);
    }
}
