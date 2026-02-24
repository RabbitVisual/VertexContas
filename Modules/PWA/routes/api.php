<?php

use Illuminate\Support\Facades\Route;
use Modules\PWA\Http\Controllers\PWAController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pwas', PWAController::class)->names('pwa');
});
