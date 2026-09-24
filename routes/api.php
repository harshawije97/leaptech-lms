<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::get('/test', [TestController::class, 'index']);

// Admin routes
Route::prefix('admin')->group(function () {
    Route::post('/sign-in', [AuthController::class, 'adminAuthenticate'])->middleware('throttle:5,1');

    Route::middleware(['auth:sanctum', 'role:super_admin,admin'])->group(function () {
        Route::post('/sign-out', [AuthController::class, 'adminLoggedOut']);

    });
});