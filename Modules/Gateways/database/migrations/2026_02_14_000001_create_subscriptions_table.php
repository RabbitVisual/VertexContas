<?php

/**
 * Assinaturas recorrentes (Stripe Subscriptions / Mercado Pago PreApproval).
 * Autor: Vertex Solutions LTDA © 2026
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gateway_slug'); // stripe, mercadopago
            $table->string('external_subscription_id')->unique(); // sub_xxx ou preapproval id
            $table->string('external_customer_id')->nullable(); // cus_xxx (Stripe)
            $table->string('status')->default('active'); // active, canceled, past_due, paused
            $table->decimal('amount', 10, 2)->default(29.90);
            $table->string('currency')->default('BRL');
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::table('payment_logs', function (Blueprint $table) {
            $table->foreignId('subscription_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subscription_id');
        });
        Schema::dropIfExists('subscriptions');
    }
};
