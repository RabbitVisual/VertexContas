<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelAdmin\Http\Controllers\GatewayConfigController;
use Modules\PanelAdmin\Http\Controllers\PanelAdminController;
use Modules\PanelAdmin\Http\Controllers\SettingsController;
use Modules\PanelAdmin\Http\Controllers\PlanController;
use Modules\PanelAdmin\Http\Controllers\AdminUserController;
use Modules\PanelAdmin\Http\Controllers\RoleController;
use Modules\PanelAdmin\Http\Controllers\PaymentController;
use Modules\PanelAdmin\Http\Controllers\SupportController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Centralized Admin Routes handled by PanelAdmin module.
|
*/

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [PanelAdminController::class, 'index'])->name('index');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::post('/settings/branding', [SettingsController::class, 'updateBranding'])->name('settings.branding');
    Route::post('/settings/mail', [SettingsController::class, 'updateMail'])->name('settings.mail');
    Route::post('/settings/mail/test', [SettingsController::class, 'testMail'])->name('settings.mail.test');
    Route::post('/settings/blog', [SettingsController::class, 'updateBlog'])->name('settings.blog');

    // Notifications Center
    Route::get('/notifications', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/send', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'send'])->name('notifications.send');
    Route::get('/notifications/search', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'searchUser'])->name('notifications.search');

    // Gateways
    Route::resource('gateways', GatewayConfigController::class)->only(['index', 'edit', 'update']);
    Route::post('/gateways/{gateway}/toggle', [GatewayConfigController::class, 'toggle'])->name('gateways.toggle');

    // Plans & Limits
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans', [PlanController::class, 'update'])->name('plans.update');

    // User Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');

    // Roles & Permissions
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'update'])->name('roles.update');

    // Global Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    // Support Bridge
    Route::get('/support', [SupportController::class, 'index'])->name('support.index')->middleware('role:admin');
    // Wiki Management
    Route::prefix('wiki')->name('wiki.')->group(function () {
        // Suggestions
        Route::get('/suggestions', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'suggestions'])->name('suggestions');
        Route::put('/suggestions/{suggestion}', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'updateSuggestionStatus'])->name('suggestions.update');
        Route::delete('/suggestions/{suggestion}', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'destroySuggestion'])->name('suggestions.destroy');

        // Categories
        Route::get('/categories', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'categories'])->name('categories');
        Route::post('/categories', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'destroyCategory'])->name('categories.destroy');
        // Articles
        Route::get('/articles', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'articles'])->name('articles');
        Route::get('/articles/create', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'createArticle'])->name('articles.create');
        Route::post('/articles', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'storeArticle'])->name('articles.store');
        Route::get('/articles/{article}/edit', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'editArticle'])->name('articles.edit');
        Route::put('/articles/{article}', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'updateArticle'])->name('articles.update');
        Route::delete('/articles/{article}', [\Modules\PanelAdmin\Http\Controllers\WikiManagerController::class, 'destroyArticle'])->name('articles.destroy');
    });
});
