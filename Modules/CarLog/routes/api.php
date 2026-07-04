<?php

use Illuminate\Support\Facades\Route;
use Modules\CarLog\Http\Controllers\CarLogController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('carlogs', CarLogController::class)->names('carlog');
});
