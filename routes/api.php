<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\DocsController;
use App\Http\Controllers\Api\V1\MailController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\UserController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::post('/register', 'register')->middleware('throttle:5,1');
            Route::post('/login', 'login')->middleware('throttle:5,1');
            Route::get('/google', 'redirectToGoogle')->name('google.login');
            Route::get('/google/callback', 'handleGoogleCallback');
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/me', 'me');
                Route::put('/update-profile', 'updateProfile');
                Route::put('/user/password', 'updatePassword');
                Route::post('/logout', 'logout');
            });
        });
        Route::controller(MailController::class)->group(function () {
            Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('verification.verify');
            Route::get('/reset-password/{token}/{email}', function ($token, $email) {
                $frontendUrl = env('FRONTEND_URL');
                return redirect("{$frontendUrl}/comments?reset_token={$token}&email={$email}");
            })->name('password.reset');

            Route::post('/forgot-password', 'sendResetLinkEmail')->middleware('throttle:3,1');
            Route::post('/reset-password', 'resetpassword')->middleware('throttle:3,1');
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/email/verify/check', 'checkVerification');
                Route::post('/email/resend', 'resend');
            });
        });
    });

    // Docs
    Route::get('docs', [DocsController::class, 'index']);

    // Review
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/show/review', 'show');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/create/review', 'store');
            Route::post('/validasi/review/{id}', 'validasi');
            Route::post('edit/review/{id}', 'edit');
            Route::post('delete/review/{id}', 'delete');
            Route::post('/categories/{name}', 'show');
        });
    });
    Route::get('/categories', [CategoryController::class, 'index']);
});
