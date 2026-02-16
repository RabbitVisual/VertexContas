<?php

use Illuminate\Support\Facades\Route;
use Modules\VertexChat\Http\Controllers\ChatController;
use Modules\VertexChat\Http\Controllers\VertexChatController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('vertexchats', VertexChatController::class)->names('vertexchat');

    Route::prefix('chat')->middleware('chat.access')->name('vertexchat.chat.')->group(function () {
        Route::post('/conversations', [ChatController::class, 'store'])->name('store');
        Route::get('/conversations', [ChatController::class, 'index'])->name('index');
        Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('send');
    });
});
