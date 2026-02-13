<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $defaultCategories = [
            // Expense Categories
            [
                'name' => 'Alimentação',
                'type' => 'expense',
                'icon' => 'utensils',
                'color' => '#10b981',
            ],
            [
                'name' => 'Moradia',
                'type' => 'expense',
                'icon' => 'house',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'Transporte',
                'type' => 'expense',
                'icon' => 'car',
                'color' => '#f59e0b',
            ],
            [
                'name' => 'Saúde',
                'type' => 'expense',
                'icon' => 'heart-pulse',
                'color' => '#ef4444',
            ],
            [
                'name' => 'Lazer',
                'type' => 'expense',
                'icon' => 'gamepad',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Educação',
                'type' => 'expense',
                'icon' => 'graduation-cap',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'Vestuário',
                'type' => 'expense',
                'icon' => 'shirt',
                'color' => '#ec4899',
            ],
            [
                'name' => 'Serviços',
                'type' => 'expense',
                'icon' => 'wrench',
                'color' => '#64748b',
            ],

            // Income Categories
            [
                'name' => 'Salário',
                'type' => 'income',
                'icon' => 'money-bill-wave',
                'color' => '#10b981',
            ],
            [
                'name' => 'Freelance',
                'type' => 'income',
                'icon' => 'laptop-code',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'Investimentos',
                'type' => 'income',
                'icon' => 'chart-line',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Vendas',
                'type' => 'income',
                'icon' => 'shop',
                'color' => '#f59e0b',
            ],
            [
                'name' => 'Outros',
                'type' => 'income',
                'icon' => 'circle-dollar',
                'color' => '#64748b',
            ],
        ];

        // Create System Defaults (user_id = null)
        foreach ($defaultCategories as $category) {
            Category::firstOrCreate(
                [
                    'user_id' => null, // Is System Default
                    'name' => $category['name'],
                    'type' => $category['type'],
                ],
                [
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                ]
            );
        }

        $this->command->info('System default categories seeded successfully!');
    }
}
