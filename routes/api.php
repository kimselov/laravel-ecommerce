<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\API\CustomerCartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PayPalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// //Category Route
// Route::apiResource('categories',CategoryController::class);



Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/best-selling', [ProductController::class, 'bestSelling']);



Route::post('login', [AuthController::class, 'login']);

// Protect these routes
Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::apiResource('categories', CategoryController::class);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource("brands",BrandController::class);

    Route::apiResource("products",ProductController::class);

    Route::apiResource("cart",CartController::class);
});



// Customer Auth
Route::post('customer/register', [CustomerAuthController::class, 'register']);
Route::post('customer/login', [CustomerAuthController::class, 'login']);

// Authenticated customer routes
Route::middleware('auth:customers')->group(function () {
    Route::get('customer/me', [CustomerAuthController::class, 'me']);
    Route::post('customer/logout', [CustomerAuthController::class, 'logout']);

    // Cart APIs for customer
    Route::get('customer/cart', [CartController::class, 'index']);
    Route::post('customer/cart', [CartController::class, 'store']);
    Route::put('customer/cart/{id}', [CartController::class, 'update']);
    Route::delete('customer/cart/{id}', [CartController::class, 'destroy']);
  Route::post('customer/checkout', [OrderController::class, 'store']);

    Route::apiResource("products",ProductController::class);
});


Route::post('/paypal/create-order', [PayPalController::class, 'createOrder']);
Route::post('/paypal/capture-order', [PayPalController::class, 'captureOrder']);



