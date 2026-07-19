<?php
// Modules/Fleet/routes.php

use Illuminate\Support\Facades\Route;
use Modules\Fleet\Http\Controllers\CarController;
// use Modules\Fleet\Http\Controllers\CarBrandController;

Route::middleware(['tenant', 'auth'])->prefix('fleet')->name('fleet.')->group(function () {
Route::post('locale/{locale}', [\App\Http\Controllers\LocaleController::class, 'switch'])
     ->name('locale.switch');
    // Cars
    Route::resource('cars', CarController::class);
    Route::post('cars/{car}/status', [CarController::class, 'changeStatus'])
         ->name('cars.status')
         ->middleware('permission:cars.change_status');

    // Brands & Models (للـ Super Admin بس)
    Route::middleware('role:super_admin')->group(function () {
        // Route::resource('brands', CarBrandController::class);
    });
});
