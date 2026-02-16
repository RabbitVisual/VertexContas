<?php

use Illuminate\Support\Facades\Route;
use Modules\VertexChat\Http\Controllers\VertexChatController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('vertexchats', VertexChatController::class)->names('vertexchat');
});
