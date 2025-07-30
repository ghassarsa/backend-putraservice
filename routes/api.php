<?php

use App\Http\Controllers\Api\V1\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/create/review', [ReviewController::class, 'store']);
    Route::post('/validasi/review/{id}', [ReviewController::class, 'validasi']);
    Route::post('edit/review/{id}', [ReviewController::class, 'edit']);
    Route::post('delete/review/{id}', [ReviewController::class, 'delete']);
});