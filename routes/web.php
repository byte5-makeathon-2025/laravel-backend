<?php

use App\Http\Controllers\WishSuccessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/wish/success/{trackingNumber}', [WishSuccessController::class, 'show'])
    ->where('trackingNumber', '[0-9]+')
    ->name('wish.success');
