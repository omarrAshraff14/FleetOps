<?php

use Illuminate\Support\Facades\Route;
use Modules\CarLog\Http\Controllers\CarLogController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('carlogs', CarLogController::class)->names('carlog');
});
