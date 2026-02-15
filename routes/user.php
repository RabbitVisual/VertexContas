<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelUser\Http\Controllers\PanelUserController;
use Modules\PanelUser\Http\Controllers\ProfileController;
use Modules\PanelUser\Http\Controllers\SecurityController;
use Modules\PanelUser\Http\Controllers\SubscriptionController;
use Modules\PanelUser\Http\Controllers\SupportTicketController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Access: Auth, Verified, Role:User
|
*/

Route::prefix('user')->middleware(['auth', 'verified', 'role:free_user|pro_user|admin', 'financial.setup'])->group(function () {

    // Panel Dashboard
    Route::get('/', [PanelUserController::class, 'index'])->name('paneluser.index');
    Route::post('/onboarding/complete', [PanelUserController::class, 'completeOnboarding'])->name('paneluser.onboarding.complete');
    Route::post('/cta-sidebar/dismiss', [PanelUserController::class, 'dismissSidebarCta'])->name('user.cta-sidebar.dismiss');

    // Financial Baseline (legado) → redireciona para Core
    Route::get('/onboarding/setup-income', fn () => redirect('/minha-renda', 301))->name('paneluser.onboarding.setup-income');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('user.subscription.index');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('user.subscription.cancel');

    // Profile
    Route::get('/perfil', [ProfileController::class, 'show'])->name('user.profile.show');
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/perfil/editar', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/perfil/foto', [ProfileController::class, 'uploadPhoto'])->name('user.profile.photo.upload');
    Route::patch('/perfil/foto/{id}/active', [ProfileController::class, 'setProfilePhoto'])->name('user.profile.photo.active');
    Route::delete('/perfil/foto/{id}', [ProfileController::class, 'deletePhoto'])->name('user.profile.photo.delete');

    // Security
    Route::get('/seguranca', [SecurityController::class, 'index'])->name('user.security.index');
    Route::put('/seguranca/senha', [SecurityController::class, 'updatePassword'])->name('user.security.password');
    Route::post('/seguranca/suporte/conceder', [SecurityController::class, 'grantSupportAccess'])->name('user.security.support-access.grant');
    Route::post('/seguranca/suporte/revogar', [SecurityController::class, 'revokeSupportAccess'])->name('user.security.support-access.revoke');
    Route::get('/seguranca/exportar-log', [SecurityController::class, 'exportLogs'])->name('user.security.export-logs');

    // Support Tickets
    Route::get('/tickets', [SupportTicketController::class, 'index'])->name('user.tickets.index');
    Route::get('/tickets/exportar', [SupportTicketController::class, 'exportTickets'])->name('user.tickets.export');
    Route::get('/tickets/novo', [SupportTicketController::class, 'create'])->name('user.tickets.create');
    Route::post('/tickets', [SupportTicketController::class, 'store'])->name('user.tickets.store');
    Route::get('/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('user.tickets.show');
    Route::post('/tickets/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('user.tickets.reply');
    Route::post('/tickets/{ticket}/rate', [SupportTicketController::class, 'rate'])->name('user.tickets.rate');

    // Remote Inspection (Consent)
    Route::post('/inspection/{inspection}/accept', [\Modules\PanelUser\Http\Controllers\InspectionController::class, 'accept'])->name('user.inspection.accept');
    Route::post('/inspection/{inspection}/reject', [\Modules\PanelUser\Http\Controllers\InspectionController::class, 'reject'])->name('user.inspection.reject');

});
