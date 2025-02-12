<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected auth routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Shop routes
Route::get('/products', [ShopController::class, 'listProducts']);
Route::get('/products/{product}', [ShopController::class, 'getProduct']);

// Cart and order routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Cart routes
    Route::post('/cart/products/{product}', [ShopController::class, 'addToCart']);
    Route::get('/cart', [ShopController::class, 'getCart']);
    
    // Order routes
    Route::get('/orders', [ShopController::class, 'listOrders']);
    Route::post('/orders', [ShopController::class, 'placeOrder']);
});
