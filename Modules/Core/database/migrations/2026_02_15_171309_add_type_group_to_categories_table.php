<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('type_group', 20)->nullable()->after('pillar');
        });

        DB::statement("UPDATE categories SET type_group = CASE
            WHEN pillar = 'essential' THEN 'essential'
            WHEN pillar = 'want' THEN 'lifestyle'
            WHEN pillar = 'savings' THEN 'financial'
            ELSE 'lifestyle'
        END WHERE type_group IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('type_group');
        });
    }
};
