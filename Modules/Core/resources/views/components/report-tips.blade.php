@props(['variant' => 'cashflow', 'compact' => false])

@php
$variants = [
    'cashflow' => [
        'icon' => 'lightbulb',
        'boxClass' => 'border-emerald-200 dark:border-emerald-800/50 bg-emerald-50/50 dark:bg-emerald-900/10',
        'iconClass' => 'bg-emerald-500/20 dark:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400',
        'dotClass' => 'text-emerald-500',
        'title' => 'Como interpretar o Fluxo de Caixa',
        'tips' => [
            ['title' => 'O que é Taxa de Poupança?', 'text' => 'É o percentual da sua receita que sobra após pagar as despesas. Uma taxa de 20% ou mais é considerada saudável.'],
            ['title' => 'Saldo negativo no mês?', 'text' => 'Se despesas superam receitas, revise as categorias com maior gasto e identifique cortes possíveis.'],
            ['title' => 'Dica: Compare períodos', 'text' => 'Use os filtros de 3, 6 ou 12 meses para enxergar tendências e sazonalidade.'],
            ['title' => 'Transferências', 'text' => 'Não entram no cálculo. Apenas receitas e despesas reais são consideradas para um cenário mais fiel.'],
        ],
    ],
    'categories' => [
        'icon' => 'chart-pie',
        'boxClass' => 'border-purple-200 dark:border-purple-800/50 bg-purple-50/50 dark:bg-purple-900/10',
        'iconClass' => 'bg-purple-500/20 dark:bg-purple-500/30 text-purple-600 dark:text-purple-400',
        'dotClass' => 'text-purple-500',
        'title' => 'Como usar o Ranking de Categorias',
        'tips' => [
            ['title' => 'Priorize o Top 3', 'text' => 'As 3 categorias com mais gastos concentram a maior parte do seu orçamento. Foque nelas primeiro.'],
            ['title' => 'Regra 50-30-20', 'text' => 'Objetivo: 50% necessidades, 30% desejos, 20% poupança. Compare suas categorias com essa referência.'],
            ['title' => 'Revisar periodicamente', 'text' => 'Filtre por mês ou trimestre e compare com períodos anteriores para identificar mudanças.'],
            ['title' => 'Categorias sem uso', 'text' => 'Se alguma categoria não aparece, considere consolidar ou removê-la para simplificar.'],
        ],
    ],
    'extrato' => [
        'icon' => 'building-columns',
        'boxClass' => 'border-teal-200 dark:border-teal-800/50 bg-teal-50/50 dark:bg-teal-900/10',
        'iconClass' => 'bg-teal-500/20 dark:bg-teal-500/30 text-teal-600 dark:text-teal-400',
        'dotClass' => 'text-teal-500',
        'title' => 'Como usar o Extrato Vertex',
        'tips' => [
            ['title' => 'Reconciliar contas', 'text' => 'Use o extrato para conferir transações contra seu banco e identificar lançamentos pendentes.'],
            ['title' => 'Saldo acumulado', 'text' => 'O saldo mostra o resultado de todas as transações até aquela linha. Útil para detectar divergências.'],
            ['title' => 'Exportar para análise', 'text' => 'Baixe em Excel ou CSV para análises externas, conciliação ou backup.'],
            ['title' => 'Imprimir / PDF', 'text' => 'Abra em nova aba e use Ctrl+P para imprimir ou salvar como PDF para documentação.'],
        ],
    ],
];
$v = $variants[$variant] ?? $variants['cashflow'];
@endphp

<div class="rounded-2xl border {{ $v['boxClass'] }} p-6 {{ $compact ? 'py-4' : '' }}">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl {{ $v['iconClass'] }} flex items-center justify-center shrink-0">
            <x-icon name="{{ $v['icon'] }}" style="duotone" class="w-5 h-5" />
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="font-bold text-slate-800 dark:text-white mb-3">{{ $v['title'] }}</h4>
            <ul class="space-y-2">
                @foreach($v['tips'] as $tip)
                <li class="flex gap-2 text-sm">
                    <span class="{{ $v['dotClass'] }} mt-0.5 shrink-0">•</span>
                    <div>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $tip['title'] }}</span>
                        <span class="text-slate-600 dark:text-slate-400"> — {{ $tip['text'] }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
