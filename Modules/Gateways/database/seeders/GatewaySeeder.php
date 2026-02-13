<?php

namespace Modules\Gateways\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gateways\Models\Gateway;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Stripe',
                'slug' => 'stripe',
                'icon' => 'stripe', // Font Awesome brand icon
                'mode' => 'sandbox',
                'is_active' => false,
                'metadata' => [
                    'currency' => 'brl',
                ],
            ],
            [
                'name' => 'Mercado Pago',
                'slug' => 'mercadopago',
                'icon' => 'credit-card', // Custom icon
                'mode' => 'sandbox',
                'is_active' => false,
                'metadata' => [
                    'currency' => 'brl',
                ],
            ],
        ];

        foreach ($gateways as $gateway) {
            Gateway::updateOrCreate(
                ['slug' => $gateway['slug']],
                $gateway
            );
        }
    }
}
