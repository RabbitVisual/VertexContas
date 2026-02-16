<?php

use Illuminate\Support\Facades\Route;
use Modules\VertexChat\Http\Controllers\VertexChatController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('vertexchats', VertexChatController::class)->names('vertexchat');
});
