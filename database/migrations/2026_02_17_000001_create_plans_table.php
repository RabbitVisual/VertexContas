<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('billing_interval')->default('monthly'); // monthly, yearly
            $table->boolean('is_free')->default(false);
            $table->integer('limit_account')->nullable();
            $table->integer('limit_income')->nullable();
            $table->integer('limit_expense')->nullable();
            $table->integer('limit_goal')->nullable();
            $table->integer('limit_budget')->nullable();
            $table->integer('limit_category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('stripe_price_id')->nullable();
            $table->string('mercadopago_plan_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('BRL');
            $table->timestamps();
        });

        $this->seedDefaultPlans();
    }

    private function seedDefaultPlans(): void
    {
        $freeName = 'Plano Gratuito';
        $proName = 'Vertex PRO';
        $freeLimits = ['limit_account' => 1, 'limit_income' => 15, 'limit_expense' => 15, 'limit_goal' => 1, 'limit_budget' => 1, 'limit_category' => 0];
        $proLimits = ['limit_account' => -1, 'limit_income' => -1, 'limit_expense' => -1, 'limit_goal' => -1, 'limit_budget' => -1, 'limit_category' => -1];

        if (Schema::hasTable('settings')) {
            $rows = \Illuminate\Support\Facades\DB::table('settings')->whereIn('key', [
                'plan_free_name', 'plan_pro_name', 'pro_has_limits',
                'limit_free_income', 'limit_free_expense', 'limit_free_goal', 'limit_free_budget', 'limit_free_account', 'limit_free_category',
                'limit_pro_income', 'limit_pro_expense', 'limit_pro_goal', 'limit_pro_budget', 'limit_pro_account', 'limit_pro_category',
            ])->pluck('value', 'key');
            $freeName = $rows['plan_free_name'] ?? $freeName;
            $proName = $rows['plan_pro_name'] ?? $proName;
            $freeLimits = [
                'limit_account' => (int) ($rows['limit_free_account'] ?? 1),
                'limit_income' => (int) ($rows['limit_free_income'] ?? 15),
                'limit_expense' => (int) ($rows['limit_free_expense'] ?? 15),
                'limit_goal' => (int) ($rows['limit_free_goal'] ?? 1),
                'limit_budget' => (int) ($rows['limit_free_budget'] ?? 1),
                'limit_category' => (int) ($rows['limit_free_category'] ?? 0),
            ];
            $proHasLimits = (bool) ($rows['pro_has_limits'] ?? 0);
            if ($proHasLimits) {
                $proLimits = [
                    'limit_account' => (int) ($rows['limit_pro_account'] ?? -1),
                    'limit_income' => (int) ($rows['limit_pro_income'] ?? -1),
                    'limit_expense' => (int) ($rows['limit_pro_expense'] ?? -1),
                    'limit_goal' => (int) ($rows['limit_pro_goal'] ?? -1),
                    'limit_budget' => (int) ($rows['limit_pro_budget'] ?? -1),
                    'limit_category' => (int) ($rows['limit_pro_category'] ?? -1),
                ];
            }
        }

        $now = now();
        foreach (
            [
                [
                    'slug' => 'free',
                    'name' => $freeName,
                    'billing_interval' => 'monthly',
                    'is_free' => true,
                    'limit_account' => $freeLimits['limit_account'],
                    'limit_income' => $freeLimits['limit_income'],
                    'limit_expense' => $freeLimits['limit_expense'],
                    'limit_goal' => $freeLimits['limit_goal'],
                    'limit_budget' => $freeLimits['limit_budget'],
                    'limit_category' => $freeLimits['limit_category'],
                    'sort_order' => 0,
                    'is_active' => true,
                    'amount' => null,
                    'currency' => 'BRL',
                ],
                [
                    'slug' => 'pro',
                    'name' => $proName,
                    'billing_interval' => 'monthly',
                    'is_free' => false,
                    'limit_account' => $proLimits['limit_account'],
                    'limit_income' => $proLimits['limit_income'],
                    'limit_expense' => $proLimits['limit_expense'],
                    'limit_goal' => $proLimits['limit_goal'],
                    'limit_budget' => $proLimits['limit_budget'],
                    'limit_category' => $proLimits['limit_category'],
                    'sort_order' => 1,
                    'is_active' => true,
                    'amount' => 29.90,
                    'currency' => 'BRL',
                ],
            ] as $row
        ) {
            \Illuminate\Support\Facades\DB::table('plans')->updateOrInsert(
                ['slug' => $row['slug']],
                array_merge($row, ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
