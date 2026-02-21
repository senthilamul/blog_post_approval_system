<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts/{post}/approve', [PostController::class, 'approve']);
    Route::post('/posts/{post}/reject', [PostController::class, 'reject']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::get('/posts/{post}/logs', [PostController::class, 'logs']);
});
