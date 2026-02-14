<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds recurrence_day (1-31) and makes category_id/account_id nullable for baseline income.
     */
    public function up(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->unsignedTinyInteger('recurrence_day')->nullable()->after('frequency'); // 1-31 day of month
        });

        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['account_id']);
        });

        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->unsignedBigInteger('account_id')->nullable()->change();
        });

        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Drops recurrence_day only. Nullable category_id/account_id are left as-is to avoid data loss.
     */
    public function down(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->dropColumn('recurrence_day');
        });
    }
};
