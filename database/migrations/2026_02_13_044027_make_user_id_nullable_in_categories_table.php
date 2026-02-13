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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Reverting might be risky if we have nulls, but strictly speaking we return it to non-null
            // In practice for this flow we might just leave it or handle it.
            // For now, let's reverse it strictly assuming data was cleaned up if rolled back.
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
