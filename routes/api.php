<?php

use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ToDoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/todos', [ToDoController::class, 'index']);
    Route::post('/todos', [ToDoController::class, 'store']);
    Route::put('/todos/{id}', [ToDoController::class, 'update']);
    Route::delete('/todos/{id}', [ToDoController::class, 'destroy']);
    Route::post('/todos/{todoId}/share', [ToDoController::class, 'share']);
    Route::post('/todos/{todoId}/unshare', [ToDoController::class, 'unshare']);
    Route::post('/todos/{id}/restore', [ToDoController::class, 'restore']);
    Route::delete('/todos/{id}/delete', [ToDoController::class, 'delete']);

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

Route::middleware(['web'])->post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
