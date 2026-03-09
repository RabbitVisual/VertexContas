<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Plan;

/**
 * Garante que os planos Free e Pro existam.
 * Usa firstOrCreate: só insere se não existir; nunca altera planos já existentes (preserva limites/config do Admin).
 */
class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::firstOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Plano Gratuito',
                'billing_interval' => 'monthly',
                'is_free' => true,
                'limit_account' => 3,
                'limit_income' => -1,
                'limit_expense' => -1,
                'limit_goal' => 1,
                'limit_budget' => 1,
                'limit_category' => 0,
                'sort_order' => 0,
                'is_active' => true,
                'amount' => null,
                'currency' => 'BRL',
            ]
        );

        Plan::firstOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Vertex PRO',
                'billing_interval' => 'monthly',
                'is_free' => false,
                'limit_account' => -1,
                'limit_income' => -1,
                'limit_expense' => -1,
                'limit_goal' => -1,
                'limit_budget' => -1,
                'limit_category' => -1,
                'sort_order' => 1,
                'is_active' => true,
                'amount' => 29.90,
                'currency' => 'BRL',
            ]
        );
    }
}
