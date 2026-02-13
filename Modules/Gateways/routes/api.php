<?php

use Illuminate\Support\Facades\Route;
use Modules\Gateways\Http\Controllers\GatewaysController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('gateways', GatewaysController::class)->names('gateways');
});
