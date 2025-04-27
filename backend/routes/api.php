<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('sellers')->group(function () {
        Route::get('/', [SellerController::class, 'index']);
        Route::post('/', [SellerController::class, 'store']);
        Route::get('{seller}', [SellerController::class, 'show']);
        Route::put('{seller}', [SellerController::class, 'update']);
        Route::delete('{seller}', [SellerController::class, 'destroy']);
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::post('/', [SaleController::class, 'store']);
        Route::get('{sale}', [SaleController::class, 'show']);
        Route::put('{sale}', [SaleController::class, 'update']);
        Route::post('/summary/{sellerId}', [SaleController::class, 'sendSummaryToSeller']);
        Route::get('/seller/{sellerId}', [SaleController::class, 'listBySeller']);
    });
});
