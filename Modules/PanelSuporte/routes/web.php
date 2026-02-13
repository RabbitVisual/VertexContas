<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelSuporte\Http\Controllers\BlogManagerController;

Route::prefix('support/blog')->middleware(['auth', 'verified', 'role:admin|support'])->name('suporte.blog.')->group(function () {
    Route::get('/', [BlogManagerController::class, 'index'])->name('index');
    Route::get('/create', [BlogManagerController::class, 'create'])->name('create');
    Route::post('/', [BlogManagerController::class, 'store'])->name('store');
    Route::get('/comments', [BlogManagerController::class, 'comments'])->name('comments');
    Route::post('/comments/{comment}/approve', [BlogManagerController::class, 'approveComment'])->name('comments.approve');
    Route::delete('/comments/{comment}/reject', [BlogManagerController::class, 'rejectComment'])->name('comments.reject');
    Route::get('/{post}/edit', [BlogManagerController::class, 'edit'])->name('edit');
    Route::put('/{post}', [BlogManagerController::class, 'update'])->name('update');
    Route::delete('/{post}', [BlogManagerController::class, 'destroy'])->name('destroy');
});
