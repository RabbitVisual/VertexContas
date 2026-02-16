<?php

declare(strict_types=1);

/**
 * Didactic Insights – dicas em linguagem simples para usuários leigos.
 * Vertex Bot: tom humanizado, termos do dia a dia, explicações claras.
 *
 * @author Vertex Solutions LTDA © 2026
 */

namespace Modules\Gamification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gamification\Models\Insight;

class DidacticInsightsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = array_merge(
            $this->lowBalanceDidactic(),
            $this->budgetReachedDidactic(),
            $this->savingsMilestoneDidactic(),
            $this->dailyTipDidactic()
        );

        foreach ($rows as $row) {
            Insight::firstOrCreate(
                [
                    'trigger_event' => $row['trigger_event'],
                    'content' => $row['content'],
                ],
                [
                    'level' => $row['level'],
                    'is_active' => $row['is_active'] ?? true,
                    'is_pro_only' => $row['is_pro_only'] ?? false,
                ]
            );
        }
    }

    private function lowBalanceDidactic(): array
    {
        return [
            ['trigger_event' => 'low_balance', 'content' => 'Seu dinheiro em conta está baixo comparado ao que você gasta por mês. Antes de assumir novos compromissos, tente guardar um pouco mais.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Quando o saldo está baixo, qualquer imprevisto pode virar dívida. Guarde um dinheirinho para emergências antes de gastar com coisas que podem esperar.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Saldo baixo aumenta o risco de usar crédito caro. Evite deixar a fatura do cartão para o mês seguinte; prefira repor sua reserva primeiro.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Sua reserva (dinheiro guardado para imprevistos) está abaixo do ideal. Tente guardar pelo menos o equivalente a metade do que você gasta no mês.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'O ideal é ter dinheiro guardado para 3 a 6 meses de gastos. Enquanto não chegar lá, evite compromissos novos e tente poupar um pouco todo mês.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Que tal revisar onde está indo seu dinheiro? Cortar pequenos gastos pode liberar uma sobra para você guardar e ficar mais tranquilo.', 'level' => 'danger'],
        ];
    }

    private function budgetReachedDidactic(): array
    {
        return [
            ['trigger_event' => 'budget_reached', 'content' => 'Você já usou {{ percent }}% do limite que definiu para {{ category }}. Vale a pena dar uma pausa para não estourar o orçamento.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Opa! {{ category }} está em {{ percent }}% do que você planejou. Segurar um pouco agora ajuda a fechar o mês no azul.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Atenção: você passou do limite em {{ category }} ({{ percent }}%). Revisar os gastos agora evita surpresas desagradáveis no fim do mês.', 'level' => 'danger'],
            ['trigger_event' => 'budget_reached', 'content' => 'Você chegou perto do limite em {{ category }} ({{ percent }}%). Que tal esperar um pouco antes de gastar de novo nessa categoria?', 'level' => 'warning'],
        ];
    }

    private function savingsMilestoneDidactic(): array
    {
        return [
            ['trigger_event' => 'savings_milestone', 'content' => 'Parabéns! Você gastou menos da metade do que entrou este mês. Esse dinheiro que sobrou pode ir para uma reserva de emergências.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Excelente! Você está controlando bem os gastos. Guardar essa sobra todo mês ajuda a construir uma reserva para imprevistos.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Muito bem! Gastar menos de 50% da sua renda mostra que você está no caminho certo. Continue assim!', 'level' => 'success'],
        ];
    }

    private function dailyTipDidactic(): array
    {
        return [
            ['trigger_event' => 'daily_tip', 'content' => 'Anotar cada gasto no mesmo dia ajuda a não perder o controle. Quando você vê por escrito, fica mais fácil entender para onde vai o dinheiro.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reserve um valor fixo para lazer. Assim você curte sem culpa e sem estourar o orçamento no fim do mês.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Revisar assinaturas (streaming, apps) a cada 3 meses pode liberar dinheiro que você nem lembrava que gastava.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Dinheiro guardado para imprevistos (reserva de emergência) deve ficar em lugar de fácil acesso, como poupança ou conta que rende.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Antes de comprar por impulso, espere 24 horas. Muitas vezes a vontade passa e você evita um gasto desnecessário.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Evite deixar a fatura do cartão para o mês seguinte. Os juros são altos e podem virar uma bola de neve de dívidas.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Guardar um pouquinho todo mês faz diferença. Mesmo que seja pouco, a constância vale mais do que esperar sobrar um valor alto.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Separe primeiro o que vai guardar, e gaste o que sobrou. Assim você prioriza sua reserva em vez de esperar sobrar no fim do mês.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Compare preços antes de comprar. Uma pesquisada rápida pode economizar uma boa grana sem você abrir mão do que quer.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'O ideal é guardar o equivalente a 3 a 6 meses de gastos para emergências. Comece com 1 mês e vá aumentando aos poucos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Pagar dívidas com juros altos (cartão, cheque especial) antes de investir costuma ser a melhor decisão financeira.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Defina metas realistas. Guardar 10% da renda já é um ótimo começo. Depois você pode aumentar aos poucos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Evite parcelar muitas compras no cartão ao mesmo tempo. O valor das parcelas soma e pode comprometer seu orçamento.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Regra simples: 50% para necessidades (conta, comida, transporte), 30% para lazer, 20% para guardar. Ajuste conforme sua realidade.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Quando receber um aumento ou extra, guarde uma parte antes de acostumar com o novo padrão de gastos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Ter dinheiro guardado evita usar crédito caro quando surgir um imprevisto, como conserto de carro ou ida ao médico.', 'level' => 'info'],
        ];
    }
}
