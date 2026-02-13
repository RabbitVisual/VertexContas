<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('destination_account_id')->nullable()->after('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('transactions')->onDelete('cascade');

            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['destination_account_id']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['destination_account_id', 'parent_id']);
        });
    }
};
