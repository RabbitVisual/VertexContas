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
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Stripe", "Mercado Pago"
            $table->string('slug')->unique(); // stripe, mercadopago
            $table->string('icon')->default('credit-card');
            $table->text('api_key')->nullable(); // Encrypted
            $table->text('secret_key')->nullable(); // Encrypted
            $table->text('webhook_secret')->nullable(); // Encrypted
            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');
            $table->boolean('is_active')->default(false);
            $table->json('metadata')->nullable(); // Extra config
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
