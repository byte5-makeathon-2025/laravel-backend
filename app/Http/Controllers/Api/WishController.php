<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWishRequest;
use App\Http\Requests\Api\UpdateWishRequest;
use App\Models\Wish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class WishController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/wishes",
     *     summary="Get authenticated user's wishes",
     *     description="Retrieve a list of wishes created by the authenticated user",
     *     operationId="getWishes",
     *     tags={"Wishes"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Wishes retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="wishes", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="New Bicycle"),
     *                     @OA\Property(property="description", type="string", example="I would love a red bicycle"),
     *                     @OA\Property(property="priority", type="string", example="high"),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $wishes = $request->user()->wishes()->latest()->get();

        return response()->json([
            'wishes' => $wishes,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/wishes",
     *     summary="Create a new wish",
     *     description="Create a new wish for the authenticated user",
     *     operationId="createWish",
     *     tags={"Wishes"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Wish data",
     *         @OA\JsonContent(
     *             required={"title","description"},
     *             @OA\Property(property="title", type="string", example="New Bicycle", description="Wish title"),
     *             @OA\Property(property="description", type="string", example="I would love a red bicycle", description="Detailed wish description"),
     *             @OA\Property(property="priority", type="string", example="high", enum={"high","medium","low"}, description="Priority level (defaults to medium)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wish created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="wish", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="New Bicycle"),
     *                 @OA\Property(property="description", type="string", example="I would love a red bicycle"),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The title field is required."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(StoreWishRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $wish = $request->user()->wishes()->create($validated);
        $wish->refresh();

        return response()->json([
            'wish' => $wish,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/wishes/{id}",
     *     summary="Get a specific wish",
     *     description="Retrieve details of a specific wish (users can only view their own wishes)",
     *     operationId="getWish",
     *     tags={"Wishes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Wish ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wish retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="wish", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="New Bicycle"),
     *                 @OA\Property(property="description", type="string", example="I would love a red bicycle"),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not your wish",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wish not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Wish not found")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/wishes/{id}",
     *     summary="Update a wish",
     *     description="Update a wish (users can update their own wishes, Santa can update status of any wish)",
     *     operationId="updateWish",
     *     tags={"Wishes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Wish ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Wish update data",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Bicycle", description="Wish title"),
     *             @OA\Property(property="description", type="string", example="I would love a blue bicycle", description="Detailed wish description"),
     *             @OA\Property(property="priority", type="string", example="medium", enum={"high","medium","low"}),
     *             @OA\Property(property="status", type="string", example="granted", enum={"pending","granted","denied","in_progress"}, description="Status (Santa can update this)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wish updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="wish", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not your wish or cannot update status",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wish not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Wish not found")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/wishes/{id}",
     *     summary="Delete a wish",
     *     description="Soft delete a wish (users can only delete their own wishes)",
     *     operationId="deleteWish",
     *     tags={"Wishes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Wish ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wish deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Wish deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not your wish",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wish not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Wish not found")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/wishes/all",
     *     summary="Get all wishes (Santa only)",
     *     description="Retrieve all wishes from all users (requires view_all_wishes permission)",
     *     operationId="getAllWishes",
     *     tags={"Wishes"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="All wishes retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="wishes", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="New Bicycle"),
     *                     @OA\Property(property="description", type="string", example="I would love a red bicycle"),
     *                     @OA\Property(property="priority", type="string", example="high"),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="John Doe"),
     *                         @OA\Property(property="email", type="string", example="john@example.com")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Insufficient permissions",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     )
     * )
     */
    public function allWishes(): JsonResponse
    {
        $wishes = Wish::with('user:id,name,email')->latest()->get();

        return response()->json([
            'wishes' => $wishes,
        ]);
    }
}
