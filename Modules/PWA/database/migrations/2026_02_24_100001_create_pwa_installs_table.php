<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pwa_installs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('device_fingerprint', 64)->index();
            $table->string('app_version', 32)->index();
            $table->timestamp('installed_at')->useCurrent();
            $table->timestamp('last_seen_at')->nullable();
            $table->string('platform', 16)->default('web');
            $table->text('user_agent')->nullable();
            $table->boolean('is_pro')->default(false);
            $table->timestamps();

            $table->index('installed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pwa_installs');
    }
};
