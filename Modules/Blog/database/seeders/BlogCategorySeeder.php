<?php

declare(strict_types=1);

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Educação Financeira', 'slug' => 'educacao-financeira', 'icon' => 'book'],
            ['name' => 'Organização Financeira', 'slug' => 'organizacao-financeira', 'icon' => 'wallet'],
            ['name' => 'Investimentos', 'slug' => 'investimentos', 'icon' => 'chart-line'],
            ['name' => 'Metas e Sonhos', 'slug' => 'metas-e-sonhos', 'icon' => 'bullseye'],
            ['name' => 'Dicas Vertex', 'slug' => 'dicas-vertex', 'icon' => 'lightbulb'],
            ['name' => 'Economia no Dia a Dia', 'slug' => 'economia-dia-a-dia', 'icon' => 'piggy-bank'],
            ['name' => 'Planejamento', 'slug' => 'planejamento', 'icon' => 'calendar-lines'],
        ];

        foreach ($categories as $item) {
            BlogCategory::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                ]
            );
        }
    }
}
