<?php

declare(strict_types=1);

/**
 * Professional Insights Seeder – 100+ dicas com terminologia de mercado financeiro.
 * Vertex Bot: imagem de "Plataforma Inteligente" (CDB, IPCA+, juros compostos, aporte mensal, etc.).
 *
 * @author Vertex Solutions LTDA © 2026
 */

namespace Modules\Gamification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gamification\Models\Insight;

class ProfessionalInsightsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = array_merge(
            $this->lowBalanceInsights(),
            $this->budgetReachedInsights(),
            $this->savingsMilestoneInsights(),
            $this->dailyTipInsights()
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

    /** @return array<int, array{trigger_event: string, content: string, level: string, is_active?: bool, is_pro_only?: bool}> */
    private function lowBalanceInsights(): array
    {
        return [
            ['trigger_event' => 'low_balance', 'content' => 'Sua reserva está abaixo do ideal. Priorize recompor em aplicações com liquidez diária (ex.: CDB) para cobrir até 6 meses de despesas.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Saldo baixo aumenta o risco de usar crédito caro. Evite rotativo; prefira repor a reserva de emergência antes de novos compromissos.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Sua reserva de emergência está aquém da metade dos gastos mensais. Manter liquidez evita dívidas e juros compostos contra você.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Atenção: com reserva baixa, qualquer imprevisto pode virar dívida. Foque em recompor em CDB com liquidez diária antes de investir em prazo.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Reserva abaixo do recomendado. O primeiro passo é ter 3 a 6 meses de despesas em aplicação de alta liquidez; depois pense em CDB ou IPCA+.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Seu fluxo de caixa está apertado. Reduzir gastos não essenciais e evitar novos compromissos ajuda a recompor a reserva de emergência.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Saldo baixo em relação aos gastos do mês. Recompor a reserva em aplicações líquidas (CDB liquidez diária) protege você de imprevistos.', 'level' => 'danger'],
            ['trigger_event' => 'low_balance', 'content' => 'Sua reserva está abaixo da metade do que você gasta no mês. Priorize liquidez: reserve de emergência primeiro, depois aporte em investimentos.', 'level' => 'danger'],
        ];
    }

    /** @return array<int, array{trigger_event: string, content: string, level: string, is_active?: bool, is_pro_only?: bool}> */
    private function budgetReachedInsights(): array
    {
        return [
            ['trigger_event' => 'budget_reached', 'content' => 'Você atingiu {{ percent }}% do orçamento de {{ category }}. Ajustar agora preserva o 20% da regra 50/30/20 para poupança e investimento.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Atenção: {{ percent }}% do limite de {{ category }} já foi usado. Controlar o consumo nesta categoria evita comprometer sua taxa de poupança.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Orçamento de {{ category }} em {{ percent }}%. Reduzir aqui pode liberar valor para aporte mensal ou reserva de emergência.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Você chegou a {{ percent }}% do orçamento em {{ category }}. Revisar gastos ajuda a manter a regra 50/30/20 e os juros compostos a seu favor.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => '{{ category }} está em {{ percent }}% do limite. Segurar um pouco agora aumenta o fluxo livre para investir em CDB ou Tesouro IPCA+.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Atenção: você atingiu {{ percent }}% do seu limite de gastos em {{ category }}.', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Opa! Vi que você já usou {{ percent }}% do orçamento de {{ category }}. Que tal um respiro para fechar o mês no azul?', 'level' => 'warning'],
            ['trigger_event' => 'budget_reached', 'content' => 'Você ultrapassou o orçamento de {{ category }} ({{ percent }}% usado). Revisar agora evita surpresas no fim do mês.', 'level' => 'danger'],
            ['trigger_event' => 'budget_reached', 'content' => 'Orçamento de {{ category }} estourado ({{ percent }}%). O excedente poderia ir para reserva de emergência ou aporte; vale revisar o consumo.', 'level' => 'danger'],
            ['trigger_event' => 'budget_reached', 'content' => '{{ category }} acima do planejado ({{ percent }}%). Ajustar o consumo protege sua capacidade de poupar e de aproveitar juros compostos.', 'level' => 'danger'],
        ];
    }

    /** @return array<int, array{trigger_event: string, content: string, level: string, is_active?: bool, is_pro_only?: bool}> */
    private function savingsMilestoneInsights(): array
    {
        return [
            ['trigger_event' => 'savings_milestone', 'content' => 'Parabéns! Você gastou menos de 50% da renda este mês. Esse excedente pode ir para reserva de emergência ou CDB e render juros compostos.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Excelente: sua taxa de poupança está alta. Um aporte mensal em Tesouro IPCA+ ou CDB multiplica o efeito dos juros compostos no longo prazo.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Você está economizando bem. Manter aporte mensal em ativos que batem a inflação (IPCA+, CDB) preserva seu poder de compra e acelera o patrimônio.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Incrível! Gastou menos da metade do que entrou. Direcione o excedente para reserva de emergência (liquidez) ou, se já tiver, para CDB ou IPCA+.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Sua disciplina está gerando sobra. Juros compostos funcionam melhor com regularidade: mantenha o aporte mensal em aplicações adequadas ao seu perfil.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Ótimo mês: você manteve a regra 50/30/20. O 20% poupado pode ir para reserva (liquidez) ou investimentos (CDB, LCI/LCA, Tesouro) conforme seu objetivo.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Parabéns pela taxa de poupança! Com aporte mensal consistente em ativos de taxa real positiva, o efeito dos juros compostos no patrimônio é relevante.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Você está no caminho certo. Gastar menos de 50% da renda permite construir reserva e investir; diversificação e prazo aumentam a rentabilidade.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Excelente controle: sobra de caixa este mês. Considere reforçar a reserva de emergência ou aumentar o aporte em CDB/Selic para aproveitar a taxa real.', 'level' => 'success'],
            ['trigger_event' => 'savings_milestone', 'content' => 'Sua sobra mensal é um diferencial. Aporte regular em IPCA+ protege da inflação e os juros compostos fazem o patrimônio crescer no longo prazo.', 'level' => 'success'],
        ];
    }

    /** @return array<int, array{trigger_event: string, content: string, level: string, is_active?: bool, is_pro_only?: bool}> */
    private function dailyTipInsights(): array
    {
        return [
            // Reserva de emergência e liquidez
            ['trigger_event' => 'daily_tip', 'content' => 'Sua reserva de emergência deve ter liquidez (ex.: CDB com liquidez diária) e cobrir de 3 a 6 meses de despesas antes de investir em prazos longos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reserva de emergência em CDB com liquidez diária garante acesso rápido ao dinheiro sem perder rentabilidade próxima da Selic.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Antes de aportar em IPCA+ ou ações, complete a reserva de emergência em aplicação de alta liquidez para imprevistos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'A reserva de emergência deve ficar em ativos líquidos; CDB ou Tesouro Selic com resgate imediato são opções seguras.', 'level' => 'info'],
            // Juros compostos e aporte mensal
            ['trigger_event' => 'daily_tip', 'content' => 'Um aporte mensal em CDB ou Tesouro IPCA+ protege seu patrimônio da inflação e aproveita juros compostos no longo prazo.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Juros compostos multiplicam seu patrimônio quando o aporte mensal é consistente; escolha ativos com taxa real positiva (acima da inflação).', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reduzir 15% em uma categoria de gasto e direcionar ao investimento aumenta o efeito dos juros compostos no seu patrimônio.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Aporte mensal fixo em CDB ou Tesouro Direto, mesmo que pequeno, gera juros compostos e protege contra a inflação ao longo do tempo.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'A regularidade do aporte mensal vale mais que o valor isolado: juros compostos trabalham a seu favor com o tempo.', 'level' => 'info'],
            // CDB, IPCA+, inflação
            ['trigger_event' => 'daily_tip', 'content' => 'CDB e Tesouro IPCA+ oferecem proteção à inflação; a taxa real (acima do IPCA) preserva seu poder de compra.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Manter dinheiro parado perde para a inflação. CDB ou Tesouro IPCA+ são opções para ganhar taxa real com risco controlado.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Investimentos atrelados ao IPCA+ (Tesouro ou CDB) ajudam a superar a inflação e a construir patrimônio com juros compostos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'CDB com liquidez diária combina rentabilidade próxima da Selic e acesso ao dinheiro quando precisar.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Diversificar entre CDB, LCI/LCA e Tesouro Direto reduz risco e pode melhorar a taxa real da carteira.', 'level' => 'info'],
            // Regra 50/30/20
            ['trigger_event' => 'daily_tip', 'content' => 'Na regra 50/30/20, os 20% de poupança podem ir para reserva de emergência (liquidez) e depois para CDB ou IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Otimize seu fluxo: destine 20% da renda a reserva e investimentos; mesmo com renda moderada, aporte mensal gera juros compostos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Os 30% da regra 50/30/20 são para estilo de vida; manter os 20% de investimento/poupança acelera sua independência financeira.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Ajustar gastos para caber nos 50% necessários e 30% desejos libera os 20% para reserva de emergência e aporte em CDB ou IPCA+.', 'level' => 'info'],
            // Psicologia e consumo
            ['trigger_event' => 'daily_tip', 'content' => 'Anotar cada gasto no dia ajuda a tomar decisão consciente e a sobrar mais para reserva de emergência e aporte mensal.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reserve um valor fixo para lazer (parte do 30% da regra 50/30/20); assim você curte sem culpa e sem comprometer o 20% de investimento.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Revisar assinaturas e gastos recorrentes a cada 3 meses pode liberar valor para aporte mensal em CDB ou reserva de emergência.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Evitar gastos por impulso preserva o fluxo para reserva de emergência e para aproveitar juros compostos no longo prazo.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Comparar preços e adiar compras não urgentes aumenta a sobra para reserva de emergência e investimentos com taxa real.', 'level' => 'info'],
            // LCI/LCA e impostos (alguns PRO)
            ['trigger_event' => 'daily_tip', 'content' => 'LCI e LCA são isentos de IR para pessoa física; podem complementar CDB na carteira, com liquidez e prazos variados.', 'level' => 'info', 'is_pro_only' => true],
            ['trigger_event' => 'daily_tip', 'content' => 'Para otimizar impostos, compare a rentabilidade líquida do CDB com a de LCI/LCA (já isentos); o maior líquido no prazo costuma vencer.', 'level' => 'info', 'is_pro_only' => true],
            ['trigger_event' => 'daily_tip', 'content' => 'LCI e LCA têm isenção de IR; em prazos longos, podem entregar taxa real atrativa com menor custo fiscal que parte dos CDBs.', 'level' => 'info', 'is_pro_only' => true],
            // Selic e taxa real
            ['trigger_event' => 'daily_tip', 'content' => 'Quando a Selic está alta, CDB e Tesouro Selic oferecem boa taxa real com liquidez; aproveite para reforçar reserva e curto prazo.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'A taxa real (retorno menos inflação) é o que importa: prefira ativos que batem o IPCA, como CDB IPCA+ ou Tesouro IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Selic é a referência para renda fixa; CDBs indexados a ela costumam oferecer liquidez e rentabilidade previsível.', 'level' => 'info'],
            // Diversificação
            ['trigger_event' => 'daily_tip', 'content' => 'Diversificação reduz risco: combine reserva em liquidez, CDB, e se fizer sentido para seu perfil, Tesouro IPCA+ ou LCI/LCA.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Não concentre tudo em um único ativo; distribua entre reserva (liquidez), CDB, e títulos atrelados ao IPCA conforme seu objetivo.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Diversificar prazos e emissores (CDB de bancos, Tesouro) ajuda a ter liquidez e rentabilidade com menos risco concentrado.', 'level' => 'info'],
            // Gestão de dívidas
            ['trigger_event' => 'daily_tip', 'content' => 'Pagar dívidas com juros altos (rotativo) antes de investir costuma dar melhor "retorno" que CDB; depois foque em reserva e aporte.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Amortizar dívidas caras libera fluxo para reserva de emergência e, em seguida, para aporte mensal em CDB ou IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Manter reserva de emergência evita usar crédito caro em imprevistos; juros de rotativo anulam o ganho de muitos investimentos.', 'level' => 'info'],
            // Análise de consumo
            ['trigger_event' => 'daily_tip', 'content' => 'Analisar gastos por categoria mostra onde cortar para aumentar o aporte mensal sem perder qualidade de vida.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reduzir 5% em uma categoria de consumo e direcionar ao investimento acelera o efeito dos juros compostos no patrimônio.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Controlar o percentual do orçamento em cada categoria ajuda a manter a regra 50/30/20 e a sobra para reserva e investimentos.', 'level' => 'info'],
            // Mais dicas gerais com termos de mercado
            ['trigger_event' => 'daily_tip', 'content' => 'Tesouro Direto IPCA+ é uma opção para longo prazo com proteção à inflação e juros compostos previsíveis.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Aporte mensal automático (débito em conta) em CDB ou Tesouro ajuda a manter a disciplina e a aproveitar juros compostos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reserva de emergência não é investimento de risco: priorize liquidez e segurança; depois diversifique com CDB e IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Inflação corrói o valor do dinheiro parado; CDB ou Tesouro IPCA+ ajudam a preservar e a ganhar taxa real.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Liquidez é poder resgatar quando precisar; para reserva use CDB liquidez diária; para metas longas, considere IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Juros compostos exigem tempo e regularidade: aporte mensal constante em ativos adequados faz a diferença.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Otimize seu fluxo livre em 5% para acelerar sua meta: reserve de emergência primeiro, depois aporte em CDB ou IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Manter 20% da renda para poupança e investimento permite construir reserva e aproveitar juros compostos em CDB ou Tesouro.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Compare a taxa real (líquida de inflação) dos investimentos; CDB e Tesouro IPCA+ costumam oferecer boa relação risco/retorno.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reserva de emergência em aplicação de liquidez diária evita resgatar investimentos de longo prazo em caso de imprevisto.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Aporte mensal em CDB indexado à Selic mantém rentabilidade e liquidez para quem ainda está formando a reserva.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Diversificar entre CDB, LCI/LCA e Tesouro Direto ajuda a balancear liquidez, prazo e rentabilidade na carteira.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Aumentar o aporte mensal em 5% da renda acelera a formação da reserva e o efeito dos juros compostos no patrimônio.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Taxa real positiva significa ganhar acima da inflação; CDB IPCA+ e Tesouro IPCA+ são exemplos de ativos com essa característica.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Antes de investir em ativos de longo prazo, garanta 3 a 6 meses de despesas em aplicação de alta liquidez.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Juros compostos no investimento superam inflação quando você mantém aporte mensal e prazo adequado ao objetivo.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Regra 50/30/20: 50% necessários, 30% desejos, 20% poupança e investimento. O 20% pode ir para reserva e depois CDB ou IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reserva de emergência em CDB com liquidez diária oferece segurança e rentabilidade próxima da Selic.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Aporte mensal em Tesouro IPCA+ protege da inflação e gera juros compostos no longo prazo com risco soberano.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Evite deixar dinheiro na poupança além do mínimo; CDB e Tesouro Selic costumam render mais com liquidez.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Diversificação inclui prazos: reserve para curto prazo (liquidez), metas médias (CDB) e longas (IPCA+ ou outros).', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Otimize o 20% da regra 50/30/20: primeiro complete a reserva de emergência, depois direcione ao aporte em CDB ou IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Liquidez diária em CDB permite resgatar a reserva de emergência a qualquer momento sem perda de rentabilidade acumulada.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Juros compostos funcionam a seu favor quando você mantém aporte mensal e reinveste os rendimentos em ativos adequados.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Compare CDB e LCI/LCA no mesmo prazo: a isenção de IR das LCI/LCA pode resultar em maior retorno líquido.', 'level' => 'info', 'is_pro_only' => true],
            ['trigger_event' => 'daily_tip', 'content' => 'Simule o retorno líquido após IR em CDBs de diferentes prazos antes de travar capital por muito tempo.', 'level' => 'info', 'is_pro_only' => true],
            ['trigger_event' => 'daily_tip', 'content' => 'Para prazos longos, Tesouro IPCA+ e CDB IPCA+ ajudam a ganhar da inflação com juros compostos previsíveis.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Sua capacidade de poupar aumenta quando você controla as categorias de gasto e mantém o 20% para reserva e investimento.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Aporte mensal automático reduz a tentação de gastar e reforça o hábito de investir com juros compostos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reserva de emergência não é para render máximo, é para estar disponível; use CDB liquidez diária ou equivalente.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Inflação reduz o poder de compra; investir em ativos que batem o IPCA (CDB IPCA+, Tesouro) preserva e multiplica o patrimônio.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Selic define a base da renda fixa; CDBs atrelados a ela costumam ter liquidez e rentabilidade atrativas.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Diversificar entre liquidez (reserva), CDB e IPCA+ equilibra acesso ao dinheiro e crescimento real do patrimônio.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Reduzir gastos em uma categoria e destinar ao aporte mensal acelera a formação da reserva e dos juros compostos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Taxa real (acima da inflação) é o que enriquece no longo prazo; priorize CDB ou Tesouro com retorno real positivo.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Anotar gastos no dia mantém o controle e libera margem para reserva de emergência e aporte em CDB ou IPCA+.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Complete a reserva de emergência antes de travar dinheiro em prazos longos; liquidez evita dívidas em imprevistos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Aporte mensal em ativos com taxa real positiva (CDB IPCA+, Tesouro) multiplica o patrimônio com juros compostos.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'LCI e LCA são isentos de IR e podem complementar CDB na carteira; compare rentabilidade líquida e liquidez.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Manter 6 meses de despesas em aplicação líquida (CDB liquidez diária) é o padrão para reserva de emergência.', 'level' => 'info'],
            ['trigger_event' => 'daily_tip', 'content' => 'Juros compostos exigem paciência: aporte mensal constante em CDB ou IPCA+ gera diferença relevante em anos.', 'level' => 'info'],
        ];
    }
}
