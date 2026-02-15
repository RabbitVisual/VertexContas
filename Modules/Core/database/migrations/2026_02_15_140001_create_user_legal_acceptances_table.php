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
        Schema::create('user_legal_acceptances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('legal_document_id')->constrained('legal_documents')->onDelete('cascade');
            $table->string('version');
            $table->timestamp('accepted_at');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::table('user_legal_acceptances', function (Blueprint $table) {
            $table->unique(['user_id', 'legal_document_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_legal_acceptances');
    }
};
