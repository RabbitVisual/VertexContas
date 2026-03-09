<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds: account_id (which account/card), is_recurring (repeat every month vs one-time), period_start (for one-time: which month).
     */
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('category_id')->constrained('accounts')->nullOnDelete();
            $table->boolean('is_recurring')->default(true)->after('period');
            $table->date('period_start')->nullable()->after('is_recurring');
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'category_id', 'period']);
            $table->unique(['user_id', 'category_id', 'period', 'period_start'], 'budgets_user_category_period_start_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropUnique('budgets_user_category_period_start_unique');
            $table->unique(['user_id', 'category_id', 'period']);
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn(['account_id', 'is_recurring', 'period_start']);
        });
    }
};
