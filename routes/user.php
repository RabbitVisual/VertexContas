<?php

use Illuminate\Support\Facades\Route;
use Modules\PanelUser\Http\Controllers\PanelUserController;
use Modules\PanelUser\Http\Controllers\ProfileController;
use Modules\PanelUser\Http\Controllers\SecurityController;
use Modules\PanelUser\Http\Controllers\SubscriptionController;
use Modules\PanelUser\Http\Controllers\SupportTicketController;
use Modules\PanelUser\Http\Controllers\BlogController;
use Modules\PanelUser\Http\Controllers\LegalAcceptanceController;
use Modules\PanelUser\Http\Controllers\VertexBotController;
use Modules\PanelUser\Http\Controllers\AchievementController;
use Modules\PanelUser\Http\Controllers\WizardController;
use Modules\Core\Http\Controllers\TourController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Access: Auth, Verified, Role:User. Legal first, then Wizard (config inicial).
|
*/

Route::prefix('user')->middleware(['auth', 'verified', 'role:free_user|pro_user|admin', 'legal.acceptance'])->group(function () {

    // Legal Acceptance (Compliance Wall)
    Route::get('/legal/aceitar', [LegalAcceptanceController::class, 'index'])->name('paneluser.legal.acceptance');
    Route::post('/legal/aceitar', [LegalAcceptanceController::class, 'store'])->name('paneluser.legal.store');

    // Wizard Inicial (configuração obrigatória: renda + conta)
    Route::get('/configuracao-inicial', [WizardController::class, 'show'])->name('paneluser.wizard.show');
    Route::post('/configuracao-inicial/renda', [WizardController::class, 'storeIncome'])->name('paneluser.wizard.income.store');
    Route::post('/configuracao-inicial/conta', [WizardController::class, 'storeAccount'])->name('paneluser.wizard.account.store');
    Route::post('/configuracao-inicial/pular-orcamento', [WizardController::class, 'skipBudget'])->name('paneluser.wizard.skip-budget');
    Route::post('/configuracao-inicial/concluir', [WizardController::class, 'complete'])->name('paneluser.wizard.complete');

    // Rest of panel: require wizard complete (income + at least one account)
    Route::middleware('wizard.complete')->group(function () {

    // Panel Dashboard
    Route::get('/', [PanelUserController::class, 'index'])->name('paneluser.index');
    Route::post('/onboarding/complete', [PanelUserController::class, 'completeOnboarding'])->name('paneluser.onboarding.complete');
    Route::post('/cta-sidebar/dismiss', [PanelUserController::class, 'dismissSidebarCta'])->name('user.cta-sidebar.dismiss');
    Route::get('/mentor/analise', [VertexBotController::class, 'showAnalysis'])->name('user.vertex-bot.analysis');
    Route::post('/vertex-bot/dismiss', [VertexBotController::class, 'dismissInsight'])->name('user.vertex-bot.dismiss');
    Route::post('/tour/complete', [TourController::class, 'complete'])->name('user.tour.complete');
    Route::get('/conquistas', [AchievementController::class, 'index'])->name('user.achievements.index');
    Route::get('/conquistas/{medal}', [AchievementController::class, 'show'])->name('user.achievements.show');

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
    Route::get('/tickets/{ticket}/exportar', [SupportTicketController::class, 'exportTicket'])->name('user.tickets.export-single');
    Route::get('/tickets/novo', [SupportTicketController::class, 'create'])->name('user.tickets.create');
    Route::post('/tickets', [SupportTicketController::class, 'store'])->name('user.tickets.store');
    Route::get('/tickets/{ticket}/messages', [SupportTicketController::class, 'messages'])->name('user.tickets.messages');
    Route::get('/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('user.tickets.show');
    Route::post('/tickets/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('user.tickets.reply');
    Route::post('/tickets/{ticket}/rate', [SupportTicketController::class, 'rate'])->name('user.tickets.rate');

    // Blog (reading experience under panel)
    Route::prefix('blog')->name('paneluser.blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
        Route::post('/comment/{post}', [BlogController::class, 'storeComment'])->name('comment.store');
        Route::post('/like/{post}', [BlogController::class, 'toggleLike'])->name('like.toggle');
        Route::post('/save/{post}', [BlogController::class, 'toggleSave'])->name('save.toggle');
    });

    // Remote Inspection (Consent & Sync)
    Route::post('/inspection/{inspection}/accept', [\Modules\PanelUser\Http\Controllers\InspectionController::class, 'accept'])->name('user.inspection.accept');
    Route::post('/inspection/{inspection}/reject', [\Modules\PanelUser\Http\Controllers\InspectionController::class, 'reject'])->name('user.inspection.reject');
    Route::get('/inspection/sync-url', [\Modules\PanelUser\Http\Controllers\InspectionController::class, 'syncUrl'])->name('user.inspection.sync');

    }); // wizard.complete
});
