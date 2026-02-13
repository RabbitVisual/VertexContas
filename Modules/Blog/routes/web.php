<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('blog/post/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::post('blog/comment/{id}', [BlogController::class, 'storeComment']);
    Route::post('blog/like/{id}', [BlogController::class, 'toggleLike']);
    Route::post('blog/comment/like/{id}', [BlogController::class, 'toggleCommentLike']);
    Route::post('blog/save/{id}', [BlogController::class, 'toggleSave']);
    Route::post('blog/track-conversion/{id}', [BlogController::class, 'trackConversion']);
});
