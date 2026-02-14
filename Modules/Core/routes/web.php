<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\CoreController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [CoreController::class, 'dashboard'])->middleware('pro')->name('core.dashboard');

    // Accounts CRUD
    Route::resource('accounts', \Modules\Core\Http\Controllers\AccountController::class)->names('core.accounts');

    // Transactions CRUD + Transfer
    Route::resource('transactions', \Modules\Core\Http\Controllers\TransactionController::class)->names('core.transactions');
    Route::get('/transfer', [\Modules\Core\Http\Controllers\TransactionController::class, 'transfer'])->name('core.transactions.transfer');
    Route::post('/transfer', [\Modules\Core\Http\Controllers\TransactionController::class, 'processTransfer'])->name('core.transactions.process-transfer');

    // Goals CRUD
    Route::resource('goals', \Modules\Core\Http\Controllers\GoalController::class)->names('core.goals');

    // Categories CRUD
    Route::resource('categories', \Modules\Core\Http\Controllers\CategoryController::class)->only(['index', 'create', 'store', 'destroy'])->names('core.categories');

    // Budgets CRUD
    Route::resource('budgets', \Modules\Core\Http\Controllers\BudgetController::class)->names('core.budgets');

    // Reports (Pro only exports)
    Route::prefix('reports')->name('core.reports.')->group(function () {
        Route::get('/', [\Modules\Core\Http\Controllers\ReportsController::class, 'index'])->name('index');
        Route::get('/cashflow', [\Modules\Core\Http\Controllers\ReportsController::class, 'cashFlow'])->name('cashflow');
        Route::get('/categories', [\Modules\Core\Http\Controllers\ReportsController::class, 'categoryRanking'])->name('categories');
        Route::get('/export/cashflow/csv', [\Modules\Core\Http\Controllers\ReportsController::class, 'exportCashFlowCsv'])->name('export.cashflow.csv');
        Route::get('/export/categories/csv', [\Modules\Core\Http\Controllers\ReportsController::class, 'exportCategoryRankingCsv'])->name('export.categories.csv');
        Route::get('/export/cashflow/pdf', [\Modules\Core\Http\Controllers\ReportsController::class, 'exportCashFlowPdf'])->name('export.cashflow.pdf');
    });

    Route::resource('cores', CoreController::class)->names('core');
});
