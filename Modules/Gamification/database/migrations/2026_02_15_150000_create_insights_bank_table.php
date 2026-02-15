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
        Schema::create('insights_bank', function (Blueprint $table) {
            $table->id();
            $table->string('trigger_event'); // low_balance, budget_reached, savings_milestone, daily_tip
            $table->text('content');
            $table->string('level')->default('info'); // info, success, warning, danger
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['trigger_event', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insights_bank');
    }
};
