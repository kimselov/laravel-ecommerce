<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// //Category Route
// Route::apiResource('categories',CategoryController::class);




Route::post('login', [AuthController::class, 'login']);

// Protect these routes
Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::apiResource('categories', CategoryController::class);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource("brands",BrandController::class);

    Route::apiResource("products",ProductController::class);
});



