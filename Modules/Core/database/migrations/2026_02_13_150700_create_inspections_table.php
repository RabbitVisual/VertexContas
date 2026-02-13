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
        Schema::create('inspections', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $blueprint->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $blueprint->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $blueprint->enum('status', ['pending', 'active', 'completed', 'rejected'])->default('pending');
            $blueprint->string('token')->unique()->nullable();
            $blueprint->timestamp('started_at')->nullable();
            $blueprint->timestamp('ended_at')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
