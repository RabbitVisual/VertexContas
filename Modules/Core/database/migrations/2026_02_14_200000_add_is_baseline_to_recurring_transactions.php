<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Baseline = from Planejamento (Minha Renda); non-baseline = from "Repetir" in transactions.
     * Only baseline is replaced on planning save; only non-baseline is processed by the job.
     */
    public function up(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->boolean('is_baseline')->default(true)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->dropColumn('is_baseline');
        });
    }
};
