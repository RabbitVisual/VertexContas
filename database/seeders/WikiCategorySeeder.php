<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\WikiCategory;

class WikiCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Primeiros Passos', 'slug' => 'primeiros-passos', 'icon' => 'rocket', 'order' => 1, 'description' => 'Onboarding e configuração inicial'],
            ['name' => 'Contas', 'slug' => 'contas', 'icon' => 'building-columns', 'order' => 2, 'description' => 'Criação e gestão de contas'],
            ['name' => 'Transações', 'slug' => 'transacoes', 'icon' => 'receipt', 'order' => 3, 'description' => 'Entrada de receitas, despesas e transferências'],
            ['name' => 'Minha Renda', 'slug' => 'minha-renda', 'icon' => 'money-bill-trend-up', 'order' => 4, 'description' => 'Planejamento de receitas mensais'],
            ['name' => 'Metas', 'slug' => 'metas', 'icon' => 'bullseye', 'order' => 5, 'description' => 'Objetivos financeiros'],
            ['name' => 'Orçamentos', 'slug' => 'orcamentos', 'icon' => 'chart-pie', 'order' => 6, 'description' => 'Limites por categoria'],
            ['name' => 'Categorias', 'slug' => 'categorias', 'icon' => 'tags', 'order' => 7, 'description' => 'Organização de categorias'],
            ['name' => 'Relatórios', 'slug' => 'relatorios', 'icon' => 'chart-simple', 'order' => 8, 'description' => 'Fluxo de caixa e análises'],
            ['name' => 'Assinatura e Pagamentos', 'slug' => 'assinatura-pagamentos', 'icon' => 'file-invoice-dollar', 'order' => 9, 'description' => 'Planos, faturas e gateways'],
            ['name' => 'Chamados e Suporte', 'slug' => 'chamados-suporte', 'icon' => 'ticket', 'order' => 10, 'description' => 'Tickets e inspeção'],
            ['name' => 'Segurança e LGPD', 'slug' => 'seguranca-lgpd', 'icon' => 'shield-halved', 'order' => 11, 'description' => 'Privacidade e proteção de dados'],
            ['name' => 'Dúvidas Frequentes', 'slug' => 'duvidas-frequentes', 'icon' => 'circle-question', 'order' => 12, 'description' => 'FAQ técnico'],
        ];

        foreach ($categories as $item) {
            WikiCategory::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'description' => $item['description'],
                    'order' => $item['order'],
                ]
            );
        }
    }
}
