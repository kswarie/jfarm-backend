<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Dashboard\CurrentStockController;
use App\Http\Controllers\Api\Master\CageTypeController;
use App\Http\Controllers\Api\Master\IncubatorController;
use App\Http\Controllers\Api\Master\ProductController;
use App\Http\Controllers\Api\Master\LocationController;
use App\Http\Controllers\Api\Master\TrayController;
use App\Http\Controllers\Api\Transaction\BirthController;
use App\Http\Controllers\Api\Transaction\BirthDeathController;
use App\Http\Controllers\Api\Transaction\DeathController;
use App\Http\Controllers\Api\Transaction\FeedUseController;
use App\Http\Controllers\Api\Transaction\IncubationController;
use App\Http\Controllers\Api\Transaction\IncubationDetailController;
use App\Http\Controllers\Api\Transaction\PurchaseController;
use App\Http\Controllers\Api\Transaction\SalesController;
use App\Http\Controllers\Api\Transaction\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});
Route::middleware(['jwt.role:ADMIN,OPERATOR'])->prefix('/api')->group(function () {
    // Product
    Route::get('/product', [ProductController::class, 'index'])->name('index');
    Route::post('/product', [ProductController::class, 'store'])->name('store');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('show');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('destroy');

    // Cage Type
    Route::get('/cage_type', [CageTypeController::class, 'index'])->name('index');
    Route::post('/cage_type', [CageTypeController::class, 'store'])->name('store');
    Route::get('/cage_type/{id}', [CageTypeController::class, 'show'])->name('show');
    Route::put('/cage_type/{id}', [CageTypeController::class, 'update'])->name('update');
    Route::delete('/cage_type/{id}', [CageTypeController::class, 'destroy'])->name('destroy');

    // Location
    Route::get('/location', [LocationController::class, 'index'])->name('index');
    Route::post('/location', [LocationController::class, 'store'])->name('store');
    Route::get('/location/{id}', [LocationController::class, 'show'])->name('show');
    Route::put('/location/{id}', [LocationController::class, 'update'])->name('update');
    Route::delete('/location/{id}', [LocationController::class, 'destroy'])->name('destroy');

    // Incubator
    Route::get('/incubator', [IncubatorController::class, 'index'])->name('index');
    Route::post('/incubator', [IncubatorController::class, 'store'])->name('store');
    Route::get('/incubator/{id}', [IncubatorController::class, 'show'])->name('show');
    Route::put('/incubator/{id}', [IncubatorController::class, 'update'])->name('update');
    Route::delete('/incubator/{id}', [IncubatorController::class, 'destroy'])->name('destroy');

    // Tray
    Route::get('/tray', [TrayController::class, 'index'])->name('index');
    Route::post('/tray', [TrayController::class, 'store'])->name('store');
    Route::get('/tray/{id}', [TrayController::class, 'show'])->name('show');
    Route::put('/tray/{id}', [TrayController::class, 'update'])->name('update');
    Route::delete('/tray/{id}', [TrayController::class, 'destroy'])->name('destroy');

});
Route::middleware(['jwt.role:OWNER'])->prefix('/api')->group(function () {
    Route::get('/dashboard/currentstock', [CurrentStockController::class, 'show'])->name('show');
});
Route::middleware(['jwt.role:OPERATOR'])->prefix('/api')->group(function () {

    Route::get('/feed-use', [FeedUseController::class, 'index'])->name('index');
    Route::post('/feed-use', [FeedUseController::class, 'store'])->name('store');
    Route::get('/feed-use/{id}', [FeedUseController::class, 'show'])->name('show');
    Route::put('/feed-use/{id}', [FeedUseController::class, 'update'])->name('update');
    Route::delete('/feed-use/{id}', [FeedUseController::class, 'destroy'])->name('destroy');

    Route::get('/incubation', [IncubationController::class, 'index'])->name('index');
    Route::post('/incubation', [IncubationController::class, 'store'])->name('store');
    Route::get('/incubation/{id}', [IncubationController::class, 'show'])->name('show');
    Route::put('/incubation/{id}', [IncubationController::class, 'update'])->name('update');
    Route::delete('/incubation/{id}', [IncubationController::class, 'destroy'])->name('destroy');

    Route::get('/incubation-detail/incubation/{incubation_id}', [IncubationDetailController::class, 'index'])->name('index');
    Route::post('/incubation-detail', [IncubationDetailController::class, 'store'])->name('store');
    Route::get('/incubation-detail/{id}', [IncubationDetailController::class, 'show'])->name('show');
    Route::put('/incubation-detail/{id}', [IncubationDetailController::class, 'update'])->name('update');
    Route::delete('/incubation-detail/{id}', [IncubationDetailController::class, 'destroy'])->name('destroy');
    //birth
    Route::get('/birth', [BirthController::class, 'index'])->name('index');
    Route::post('/birth', [BirthController::class, 'store'])->name('store');
    Route::get('/birth/{id}', [BirthController::class, 'show'])->name('show');
    Route::put('/birth/{id}', [BirthController::class, 'update'])->name('update');
    Route::delete('/birth/{id}', [BirthController::class, 'destroy'])->name('destroy');
    //death
    Route::get('/death', [DeathController::class, 'index'])->name('index');
    Route::post('/death', [DeathController::class, 'store'])->name('store');
    Route::get('/death/{id}', [DeathController::class, 'show'])->name('show');
    Route::put('/death/{id}', [DeathController::class, 'update'])->name('update');
    Route::delete('/death/{id}', [DeathController::class, 'destroy'])->name('destroy');//death

    Route::get('/purchase', [PurchaseController::class, 'index'])->name('index');
    Route::post('/purchase', [PurchaseController::class, 'store'])->name('store');
    Route::get('/purchase/{id}', [PurchaseController::class, 'show'])->name('show');
    Route::put('/purchase/{id}', [PurchaseController::class, 'update'])->name('update');
    Route::delete('/purchase/{id}', [PurchaseController::class, 'destroy'])->name('destroy');

    Route::get('/sales', [SalesController::class, 'index'])->name('index');
    Route::post('/sales', [SalesController::class, 'store'])->name('store');
    Route::get('/sales/{id}', [SalesController::class, 'show'])->name('show');
    Route::put('/sales/{id}', [SalesController::class, 'update'])->name('update');
    Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('destroy');

    Route::get('/incubator', [IncubatorController::class, 'index'])->name('index');
    Route::get('/incubator/{id}', [IncubatorController::class, 'show'])->name('show');

    Route::get('/tray', [TrayController::class, 'index'])->name('index');
    Route::get('/tray/{id}', [TrayController::class, 'show'])->name('show');

    Route::get('/dashboard/currentstock', [CurrentStockController::class, 'show'])->name('show');

    Route::get('/stock', [StockController::class, 'index'])->name('index');

});
