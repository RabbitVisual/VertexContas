<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelAdmin\Http\Controllers\AdminBlogController;
use Modules\PanelAdmin\Http\Controllers\AdminProfileController;
use Modules\PanelAdmin\Http\Controllers\AdminSupportController;
use Modules\PanelAdmin\Http\Controllers\AdminUserController;
use Modules\PanelAdmin\Http\Controllers\GatewayConfigController;
use Modules\PanelAdmin\Http\Controllers\PanelAdminController;
use Modules\PanelAdmin\Http\Controllers\PaymentController;
use Modules\PanelAdmin\Http\Controllers\SubscriptionController;
use Modules\PanelAdmin\Http\Controllers\PlanController;
use Modules\PanelAdmin\Http\Controllers\RoleController;
use Modules\PanelAdmin\Http\Controllers\SettingsController;

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

    // Admin Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'show'])->name('show');
        Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [AdminProfileController::class, 'update'])->name('update');
        Route::post('/update-photo', [AdminProfileController::class, 'updatePhoto'])->name('update-photo');
    });

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::post('/settings/branding', [SettingsController::class, 'updateBranding'])->name('settings.branding');
    Route::post('/settings/mail', [SettingsController::class, 'updateMail'])->name('settings.mail');
    Route::post('/settings/mail/test', [SettingsController::class, 'testMail'])->name('settings.mail.test');
    Route::post('/settings/blog', [SettingsController::class, 'updateBlog'])->name('settings.blog');
    Route::post('/settings/documents', [SettingsController::class, 'updateDocumentTemplates'])->name('settings.documents');

    // Notifications Center
    Route::get('/notifications', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications/send', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'send'])->name('notifications.send');
    Route::get('/notifications/search', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'searchUser'])->name('notifications.search');
    Route::get('/notifications/{id}', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/{id}/edit', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'edit'])->name('notifications.edit');
    Route::delete('/notifications/{id}', [\Modules\Notifications\Http\Controllers\AdminNotificationController::class, 'destroy'])->name('notifications.destroy');

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

    // Global Payments & Subscriptions
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

    // Admin Support Center (Global Management)
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [AdminSupportController::class, 'index'])->name('index');
        Route::get('/{ticket}/messages', [AdminSupportController::class, 'messages'])->name('messages');
        Route::get('/{ticket}', [AdminSupportController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [AdminSupportController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/assign', [AdminSupportController::class, 'assign'])->name('assign');
        Route::post('/{ticket}/takeover', [AdminSupportController::class, 'takeover'])->name('takeover');
    });

    // Blog Management
    Route::prefix('blog')->name('blog.')->group(function () {
        // Posts
        Route::get('/', [AdminBlogController::class, 'index'])->name('index');
        Route::get('/create', [AdminBlogController::class, 'create'])->name('create');
        Route::post('/', [AdminBlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminBlogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminBlogController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminBlogController::class, 'destroy'])->name('destroy');

        // Categories
        Route::get('/categories', [AdminBlogController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminBlogController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{id}', [AdminBlogController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminBlogController::class, 'destroyCategory'])->name('categories.destroy');

        // Comments
        Route::get('/comments', [AdminBlogController::class, 'comments'])->name('comments');
        Route::post('/comments/{id}/approve', [AdminBlogController::class, 'approveComment'])->name('comments.approve');
        Route::delete('/comments/{id}/reject', [AdminBlogController::class, 'rejectComment'])->name('comments.reject');
    });
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
    // User Management Extensions
    Route::post('users/{user}/update-photo', [AdminSupportController::class, 'updateUserPhoto'])->name('users.update-photo');
});
