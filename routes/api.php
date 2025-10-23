<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/category', [CategoryController::class, 'createCategory']);
    Route::get('/category', [CategoryController::class, 'getCategory']);
    Route::put('/category/${id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/category/${id}', [CategoryController::class, 'deleteCategory']);
});
