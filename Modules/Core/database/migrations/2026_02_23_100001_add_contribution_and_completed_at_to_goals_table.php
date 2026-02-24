<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('deadline');
            $table->decimal('monthly_contribution', 15, 2)->nullable()->after('completed_at');
            $table->foreignId('contribution_account_id')->nullable()->after('monthly_contribution')->constrained('accounts')->onDelete('set null');
            $table->foreignId('contribution_category_id')->nullable()->after('contribution_account_id')->constrained('categories')->onDelete('set null');
            $table->unsignedTinyInteger('contribution_recurrence_day')->nullable()->after('contribution_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign(['contribution_account_id']);
            $table->dropForeign(['contribution_category_id']);
            $table->dropColumn([
                'completed_at',
                'monthly_contribution',
                'contribution_account_id',
                'contribution_category_id',
                'contribution_recurrence_day',
            ]);
        });
    }
};
