<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\PublicTrackingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('register-store', [AuthController::class, 'registerStore']);
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::get('tracking/{token}', [PublicTrackingController::class, 'show']);
    Route::post('tracking/{token}/location', [PublicTrackingController::class, 'confirmLocation']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::apiResource('deliveries', DeliveryController::class)->only(['index', 'store', 'update']);
        Route::post('deliveries/{delivery}/assign', [DeliveryController::class, 'assign']);
        Route::post('deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus']);
    });
});
