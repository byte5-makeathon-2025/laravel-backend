<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WishController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/wishes/all', [WishController::class, 'allWishes'])
        ->middleware('can:view_all_wishes');
    Route::apiResource('wishes', WishController::class);
});
