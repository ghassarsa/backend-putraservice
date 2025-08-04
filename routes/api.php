<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [UserController::class, 'logout']);        
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create/review', [ReviewController::class, 'store']);
        Route::post('/validasi/review/{id}', [ReviewController::class, 'validasi']);
        Route::post('edit/review/{id}', [ReviewController::class, 'edit']);
        Route::post('delete/review/{id}', [ReviewController::class, 'delete']);
        Route::post('/categories/{name}', [CategoryController::class, 'show']);
    });
});