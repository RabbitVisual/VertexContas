<?php

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // API/Ajax for Polling
    Route::get('/notifications/unread', [NotificationController::class, 'fetchUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // UI Pages (Contextual)
    Route::get('/user/notifications', function () {
        return view('paneluser::notifications.index');
    })->name('user.notifications.index');

    Route::get('/admin/notifications', function () {
        return view('paneladmin::notifications.index');
    })->name('admin.notifications.index');

    Route::get('/support/notifications', function () {
        return view('panelsuporte::notifications.index');
    })->name('support.notifications.index');
});
