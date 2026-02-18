<?php

declare(strict_types=1);

/**
 * Dicas contextuais do Mentor Vertex por página (Metas, Categorias, Relatórios, Orçamentos, Minha Renda, etc.).
 * Quando o usuário navega em uma seção, a dica exibida é específica daquela tela.
 */

namespace Modules\Gamification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gamification\Models\Insight;

class PageContextInsightsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // Dashboard
            ['trigger_event' => 'page_dashboard', 'content' => 'No painel você vê o resumo do mês: receitas, despesas e saldo. Use os filtros de período para comparar meses e acompanhar a evolução.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_dashboard', 'content' => 'Revisar o dashboard semanalmente ajuda a identificar gastos que fogem do planejado antes que o mês feche no vermelho.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_dashboard', 'content' => 'O indicador financeiro reflete reserva, taxa de poupança e uso de orçamentos. Metas claras aqui tornam as decisões mais fáceis.', 'level' => 'info', 'is_active' => true],
            // Metas
            ['trigger_event' => 'page_goals', 'content' => 'Metas com valor e prazo definidos (ex.: reserva R$ 24 mil em 12 meses) tornam a economia mais concreta e mensurável.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_goals', 'content' => 'Quebre metas grandes em etapas mensais: assim você acompanha o progresso e ajusta o aporte se precisar.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_goals', 'content' => 'Priorize primeiro a meta de reserva de emergência (3 a 6 meses de despesas); depois defina metas de médio e longo prazo.', 'level' => 'info', 'is_active' => true],
            // Categorias
            ['trigger_event' => 'page_categories', 'content' => 'Categorias bem definidas permitem ver para onde vai seu dinheiro e onde cortar ou ajustar. Use relatórios por categoria depois.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_categories', 'content' => 'Classifique cada transação na categoria correta: relatórios e orçamentos dependem disso para serem úteis.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_categories', 'content' => 'No Vertex Pro você pode criar categorias personalizadas com ícone e cor para organizar do seu jeito.', 'level' => 'info', 'is_active' => true],
            // Relatórios
            ['trigger_event' => 'page_reports', 'content' => 'Relatórios mostram receitas e despesas por período e por categoria. Use para enxergar padrões e tomar decisões.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_reports', 'content' => 'Compare meses diferentes para ver se está gastando mais em alguma categoria e onde há espaço para poupar.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_reports', 'content' => 'No plano PRO você tem fluxo de caixa, relatório por categoria e consultoria em PDF para aprofundar a análise.', 'level' => 'info', 'is_active' => true],
            // Orçamentos
            ['trigger_event' => 'page_budgets', 'content' => 'Defina um limite por categoria (ex.: Alimentação R$ 800). O sistema avisa quando você se aproxima ou ultrapassa o limite.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_budgets', 'content' => 'Orçamentos realistas evitam frustração: use a média dos últimos meses como base e ajuste aos poucos.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_budgets', 'content' => 'Revisar orçamentos no início do mês ajuda a manter os 20% de poupança da regra 50/30/20.', 'level' => 'info', 'is_active' => true],
            // Minha Renda
            ['trigger_event' => 'page_income', 'content' => 'Cadastre todas as fontes de renda (salário, freelance, aluguel). O Vertex usa isso para calcular indicadores e metas.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_income', 'content' => 'Renda recorrente e eventual em separado ajudam a planejar: gastos fixos com o que é estável, extras com o que varia.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_income', 'content' => 'Atualize a renda quando houver mudança (novo emprego, aumento). Os relatórios e o score financeiro refletem isso.', 'level' => 'info', 'is_active' => true],
            // Transações / Extrato
            ['trigger_event' => 'page_transactions', 'content' => 'Registre cada movimentação no extrato. Quanto mais completo, melhores os relatórios e as dicas do Mentor.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_transactions', 'content' => 'Use transferências entre contas quando o dinheiro só muda de lugar; assim o saldo e os totais ficam corretos.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_transactions', 'content' => 'Transações recorrentes podem ser agendadas para não esquecer de lançar despesas fixas todo mês.', 'level' => 'info', 'is_active' => true],
            // Tickets / Suporte
            ['trigger_event' => 'page_tickets', 'content' => 'Aqui você acompanha chamados enviados ao suporte. Responder às perguntas do agente acelera a resolução.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'page_tickets', 'content' => 'No plano PRO você tem prioridade nas respostas e pode exportar o histórico do atendimento.', 'level' => 'info', 'is_active' => true],
        ];

        foreach ($rows as $row) {
            Insight::firstOrCreate(
                [
                    'trigger_event' => $row['trigger_event'],
                    'content' => $row['content'],
                ],
                ['level' => $row['level'], 'is_active' => $row['is_active']]
            );
        }
    }
}
