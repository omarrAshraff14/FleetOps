<?php

use Illuminate\Support\Facades\Route;
use Modules\Fleet\Http\Controllers\FleetController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('fleets', FleetController::class)->names('fleet');
});
