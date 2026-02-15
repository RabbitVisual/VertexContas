<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     * 50/30/20 mapping: essential (50%), lifestyle (30%), financial (20%).
     */
    public function run(): void
    {
        $defaultCategories = [
            // Essential (50%) - Aluguel, Energia, Água, Mercado, Saúde, Educação
            ['name' => 'Alimentação', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#10b981', 'type_group' => 'essential'],
            ['name' => 'Moradia', 'type' => 'expense', 'icon' => 'house', 'color' => '#3b82f6', 'type_group' => 'essential'],
            ['name' => 'Transporte', 'type' => 'expense', 'icon' => 'car', 'color' => '#f59e0b', 'type_group' => 'essential'],
            ['name' => 'Saúde', 'type' => 'expense', 'icon' => 'heart-pulse', 'color' => '#ef4444', 'type_group' => 'essential'],
            ['name' => 'Educação', 'type' => 'expense', 'icon' => 'graduation-cap', 'color' => '#06b6d4', 'type_group' => 'essential'],
            ['name' => 'Serviços', 'type' => 'expense', 'icon' => 'wrench', 'color' => '#64748b', 'type_group' => 'essential'],
            ['name' => 'Energia', 'type' => 'expense', 'icon' => 'bolt', 'color' => '#eab308', 'type_group' => 'essential'],
            ['name' => 'Água', 'type' => 'expense', 'icon' => 'droplet', 'color' => '#0ea5e9', 'type_group' => 'essential'],
            // Lifestyle (30%) - Lazer, Restaurantes, Assinaturas (Netflix), Presentes
            ['name' => 'Lazer', 'type' => 'expense', 'icon' => 'gamepad', 'color' => '#8b5cf6', 'type_group' => 'lifestyle'],
            ['name' => 'Vestuário', 'type' => 'expense', 'icon' => 'shirt', 'color' => '#ec4899', 'type_group' => 'lifestyle'],
            ['name' => 'Restaurantes', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#f97316', 'type_group' => 'lifestyle'],
            ['name' => 'Assinaturas', 'type' => 'expense', 'icon' => 'rectangle-ad', 'color' => '#a855f7', 'type_group' => 'lifestyle'],
            ['name' => 'Presentes', 'type' => 'expense', 'icon' => 'gift', 'color' => '#ec4899', 'type_group' => 'lifestyle'],
            // Financial (20%) - Investimentos, Reserva de Emergência, Pagamento de Dívidas
            ['name' => 'Investimentos', 'type' => 'expense', 'icon' => 'chart-line', 'color' => '#10b981', 'type_group' => 'financial'],
            ['name' => 'Reserva de Emergência', 'type' => 'expense', 'icon' => 'piggy-bank', 'color' => '#059669', 'type_group' => 'financial'],
            ['name' => 'Pagamento de Dívidas', 'type' => 'expense', 'icon' => 'credit-card', 'color' => '#dc2626', 'type_group' => 'financial'],

            // Income Categories (type_group ignored)
            ['name' => 'Salário', 'type' => 'income', 'icon' => 'money-bill-wave', 'color' => '#10b981', 'type_group' => null],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => 'laptop-code', 'color' => '#06b6d4', 'type_group' => null],
            ['name' => 'Investimentos', 'type' => 'income', 'icon' => 'chart-line', 'color' => '#8b5cf6', 'type_group' => null],
            ['name' => 'Vendas', 'type' => 'income', 'icon' => 'shop', 'color' => '#f59e0b', 'type_group' => null],
            ['name' => 'Outros', 'type' => 'income', 'icon' => 'circle-dollar', 'color' => '#64748b', 'type_group' => null],
        ];

        foreach ($defaultCategories as $category) {
            $typeGroup = $category['type_group'] ?? null;
            unset($category['type_group']);

            $cat = Category::firstOrCreate(
                [
                    'user_id' => null,
                    'name' => $category['name'],
                    'type' => $category['type'],
                ],
                [
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                ]
            );

            if ($typeGroup !== null && $cat->type_group !== $typeGroup) {
                $cat->update(['type_group' => $typeGroup, 'pillar' => match ($typeGroup) {
                    'essential' => 'essential',
                    'lifestyle' => 'want',
                    'financial' => 'savings',
                    default => 'want',
                }]);
            }
        }

        $this->command->info('System default categories seeded successfully!');
    }
}
