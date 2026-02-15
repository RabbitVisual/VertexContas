<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelSuporte\Http\Controllers\SupportAgentController;

/*
|--------------------------------------------------------------------------
| Support Routes
|--------------------------------------------------------------------------
|
| Access: Auth, Verified, Role:Admin|Support
|
*/

Route::prefix('support')->middleware(['auth', 'verified', 'role:admin|support'])->name('support.')->group(function () {
    // Dashboard
    Route::get('/', [SupportAgentController::class, 'dashboard'])->name('index');

    // Manual & Reports
    Route::get('/manual', [\Modules\PanelSuporte\Http\Controllers\ManualController::class, 'index'])->name('manual.index');
    Route::get('/reports', [\Modules\PanelSuporte\Http\Controllers\SupportReportController::class, 'index'])->name('reports.index');

    // Tickets
    Route::get('/tickets', [SupportAgentController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}/messages', [SupportAgentController::class, 'messages'])->name('tickets.messages');
    Route::get('/tickets/{ticket}', [SupportAgentController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [SupportAgentController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/close', [SupportAgentController::class, 'close'])->name('tickets.close');
    Route::post('/tickets/{ticket}/inspection/request', [\Modules\PanelSuporte\Http\Controllers\InspectionController::class, 'request'])->name('tickets.inspection.request');

    // Wiki
    Route::get('/wiki', [\Modules\PanelSuporte\Http\Controllers\WikiController::class, 'index'])->name('wiki.index');
    Route::get('/wiki/{article:slug}', [\Modules\PanelSuporte\Http\Controllers\WikiController::class, 'show'])->name('wiki.show');
    Route::post('/wiki/suggestion', [\Modules\PanelSuporte\Http\Controllers\WikiController::class, 'storeSuggestion'])->name('wiki.suggestion.store');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\Modules\PanelSuporte\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [\Modules\PanelSuporte\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [\Modules\PanelSuporte\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::post('/photo', [\Modules\PanelSuporte\Http\Controllers\ProfileController::class, 'uploadPhoto'])->name('photo.upload');
        Route::patch('/photo/{id}', [\Modules\PanelSuporte\Http\Controllers\ProfileController::class, 'setProfilePhoto'])->name('photo.active');
        Route::delete('/photo/{id}', [\Modules\PanelSuporte\Http\Controllers\ProfileController::class, 'deletePhoto'])->name('photo.delete');
    });

    // User Management (Support Access Required)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/{user}', [\Modules\PanelSuporte\Http\Controllers\UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [\Modules\PanelSuporte\Http\Controllers\UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [\Modules\PanelSuporte\Http\Controllers\UserManagementController::class, 'update'])->name('update');
    });
});

// Inspection session control (Exempt from role check to allow termination while impersonating)
Route::prefix('support/inspection')->middleware(['auth', 'verified'])->name('support.inspection.')->group(function () {
    Route::post('/{inspection}/enter', [\Modules\PanelSuporte\Http\Controllers\InspectionController::class, 'enter'])->name('enter');
    Route::post('/{inspection}/stop', [\Modules\PanelSuporte\Http\Controllers\InspectionController::class, 'stop'])->name('stop');
    Route::get('/check-session', [\Modules\PanelSuporte\Http\Controllers\InspectionController::class, 'checkSession'])->name('check');
});
