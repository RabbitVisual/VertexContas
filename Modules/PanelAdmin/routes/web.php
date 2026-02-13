<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelAdmin\Http\Controllers\SettingsController;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/settings/blog', [SettingsController::class, 'updateBlog'])->name('settings.blog');
});
