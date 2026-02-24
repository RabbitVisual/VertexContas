<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pwa_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 32)->unique();
            $table->text('release_notes')->nullable();
            $table->boolean('is_force_update')->default(false);
            $table->timestamp('released_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pwa_versions');
    }
};
