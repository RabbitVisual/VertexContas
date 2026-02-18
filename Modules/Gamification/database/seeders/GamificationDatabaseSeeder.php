<?php

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\Gamification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gamification\Models\Insight;

class GamificationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insights = [
            // low_balance
            ['trigger_event' => 'low_balance', 'content' => 'Seu saldo está baixo em relação aos gastos do mês. Que tal revisar despesas ou adiar algum gasto não essencial?', 'level' => 'danger', 'is_active' => true],
            ['trigger_event' => 'low_balance', 'content' => 'Atenção: sua reserva está abaixo da metade do que você costuma gastar no mês. Considere repor ou evitar novos compromissos.', 'level' => 'danger', 'is_active' => true],
            // budget_reached (placeholders: {{ category }}, {{ percent }})
            ['trigger_event' => 'budget_reached', 'content' => 'Atenção: você atingiu {{ percent }}% do seu limite de gastos em {{ category }}.', 'level' => 'warning', 'is_active' => true],
            ['trigger_event' => 'budget_reached', 'content' => 'Opa! Vi que você já usou {{ percent }}% do orçamento de {{ category }}. Que tal um respiro para fechar o mês no azul?', 'level' => 'warning', 'is_active' => true],
            ['trigger_event' => 'budget_reached', 'content' => 'Você ultrapassou o orçamento de {{ category }} ({{ percent }}% usado). Revisar agora evita surpresas no fim do mês.', 'level' => 'danger', 'is_active' => true],
            // savings_milestone
            ['trigger_event' => 'savings_milestone', 'content' => 'Parabéns! Você gastou menos de 50% da sua renda este mês!', 'level' => 'success', 'is_active' => true],
            ['trigger_event' => 'savings_milestone', 'content' => 'Incrível! Você está economizando bem: gastou menos da metade do que entrou. Continue assim!', 'level' => 'success', 'is_active' => true],
            // daily_tip
            ['trigger_event' => 'daily_tip', 'content' => 'Dica do dia: anotar cada gasto assim que acontece ajuda a não perder o controle no fim do mês.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'daily_tip', 'content' => 'Dica do dia: reserve um valor fixo para lazer. Assim você curte sem culpa e sem estourar o orçamento.', 'level' => 'info', 'is_active' => true],
            ['trigger_event' => 'daily_tip', 'content' => 'Dica do dia: revisar assinaturas a cada 3 meses pode liberar uma grana que você nem lembrava que gastava.', 'level' => 'info', 'is_active' => true],
        ];

        foreach ($insights as $row) {
            Insight::firstOrCreate(
                [
                    'trigger_event' => $row['trigger_event'],
                    'content' => $row['content'],
                ],
                ['level' => $row['level'], 'is_active' => $row['is_active']]
            );
        }

        $this->call([
            DidacticInsightsSeeder::class,
            ProfessionalInsightsSeeder::class,
            PageContextInsightsSeeder::class,
            MedalsSeeder::class,
            CoachingRulesSeeder::class,
        ]);
    }
}
