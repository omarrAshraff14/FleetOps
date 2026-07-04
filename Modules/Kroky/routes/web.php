<?php

use Illuminate\Support\Facades\Route;
use Modules\Kroky\Http\Controllers\KrokyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('krokies', KrokyController::class)->names('kroky');
});
