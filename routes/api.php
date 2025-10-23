<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/category', [CategoryController::class, 'index']);

Route::get('/post', [PostController::class, 'index']);
Route::get('/post/${id}', [PostController::class, 'show']);
Route::get('/categories/{categoryID}/posts', [PostController::class, 'getByCategory']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/${id}', [CategoryController::class, 'update']);
    Route::delete('/category/${id}', [CategoryController::class, 'destroy']);

    Route::post('/post', [PostController::class, 'store']);
    Route::put('/post/${id}', [PostController::class, 'update']);
    Route::delete('/post/${id}', [PostController::class, 'destroy']);

    Route::get('/posts/${postID}/comments', [CommentController::class, 'index']);
    Route::post('/posts/${postID}/comments', [CommentController::class, 'store']);
    Route::get('/comments/${id}', [CommentController::class, 'update']);
    Route::get('/comments/${id}', [CommentController::class, 'destroy']);
});
