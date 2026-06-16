<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('items', ItemController::class);
Route::put('/items/{id}', [ItemController::class, 'update']);
Route::delete('/items/{id}', [ItemController::class, 'destroy']);

// Tambahkan ini untuk fitur markAsFound
Route::put('/items/{id}/found', [ItemController::class, 'markAsFound']);
