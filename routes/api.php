<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Dashboard\CurrentStockController;
use App\Http\Controllers\Api\Master\CageTypeController;
use App\Http\Controllers\Api\Master\ProductController;
use App\Http\Controllers\Api\Transaction\LocationController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});
Route::middleware(['jwt.role:admin'])->prefix('/api')->group(function () {
    Route::get('/product', [ProductController::class, 'index'])->name('index');
    Route::post('/product', [ProductController::class, 'store'])->name('store');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('show');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('destroy');

    Route::get('/cage_type', [CageTypeController::class, 'index'])->name('index');
    Route::post('/cage_type', [CageTypeController::class, 'store'])->name('store');
    Route::get('/cage_type/{id}', [CageTypeController::class, 'show'])->name('show');
    Route::put('/cage_type/{id}', [CageTypeController::class, 'update'])->name('update');
    Route::delete('/cage_type/{id}', [CageTypeController::class, 'destroy'])->name('destroy');

    Route::get('/location', [LocationController::class, 'index'])->name('index');
    Route::post('/location', [LocationController::class, 'store'])->name('store');
    Route::get('/location/{id}', [LocationController::class, 'show'])->name('show');
    Route::put('/location/{id}', [LocationController::class, 'update'])->name('update');
    Route::delete('/location/{id}', [LocationController::class, 'destroy'])->name('destroy');

    Route::get('/dashboard/currentstock', [CurrentStockController::class, 'show'])->name('show');
});
Route::middleware(['jwt.role:owner'])->prefix('/api')->group(function () {
//    Route::get('/dashboard', [CurrentStockController::class, 'show'])->name('show');
});
Route::middleware(['jwt.role:operator'])->prefix('/api')->group(function () {
//    Route::get('/dashboard', [CurrentStockController::class, 'show'])->name('show');
});
