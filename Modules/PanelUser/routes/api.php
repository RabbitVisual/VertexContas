<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelUser\Http\Controllers\PanelUserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('panelusers', PanelUserController::class)->names('paneluser');
});
