<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelAdmin\Http\Controllers\PanelAdminController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('paneladmins', PanelAdminController::class)->names('paneladmin');
});
