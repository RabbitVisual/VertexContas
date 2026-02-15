@props([
    'balance' => 0,
    'savingsRate' => 0,
    'topCategoryPercent' => null,
    'variant' => 'cashflow',
])

@php
$actions = [];
if ($balance < 0) {
    $actions[] = ['icon' => 'triangle-exclamation', 'color' => 'red', 'title' => 'Saldo negativo', 'text' => 'Revise suas despesas fixas e identifique gastos que podem ser reduzidos ou adiados.'];
}
if ($savingsRate >= 0 && $savingsRate < 10) {
    $actions[] = ['icon' => 'piggy-bank', 'color' => 'amber', 'title' => 'Poupança baixa', 'text' => 'Tente destinar pelo menos 10% da renda para reserva. Comece pequeno e aumente gradualmente.'];
}
if ($savingsRate >= 10 && $savingsRate < 20) {
    $actions[] = ['icon' => 'chart-line', 'color' => 'blue', 'title' => 'Bom progresso', 'text' => 'Você está no caminho. Meta sugerida: 20% para uma situação financeira mais confortável.'];
}
if ($savingsRate >= 20) {
    $actions[] = ['icon' => 'check-circle', 'color' => 'emerald', 'title' => 'Parabéns!', 'text' => 'Sua taxa de poupança está em nível saudável. Considere investir o excedente.'];
}
if ($topCategoryPercent !== null && $topCategoryPercent > 40) {
    $actions[] = ['icon' => 'magnifying-glass', 'color' => 'purple', 'title' => 'Concentração alta', 'text' => "Uma categoria concentra mais de 40% dos gastos. Vale revisar se há espaço para otimização."];
}
// Fallback when no actions
if (empty($actions)) {
    $actions[] = ['icon' => 'circle-info', 'color' => 'slate', 'title' => 'Próximos passos', 'text' => 'Explore os gráficos e tabelas para entender melhor seus padrões de gastos.'];
}
$iconClasses = [
    'red' => 'bg-red-500/20 text-red-600 dark:text-red-400',
    'amber' => 'bg-amber-500/20 text-amber-600 dark:text-amber-400',
    'blue' => 'bg-blue-500/20 text-blue-600 dark:text-blue-400',
    'emerald' => 'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400',
    'purple' => 'bg-purple-500/20 text-purple-600 dark:text-purple-400',
    'slate' => 'bg-slate-500/20 text-slate-600 dark:text-slate-400',
];
@endphp

@if(!empty($actions))
<div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-6 shadow-sm">
    <h4 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
        <x-icon name="list-check" style="duotone" class="w-5 h-5 text-emerald-600" />
        O que fazer agora
    </h4>
    <ul class="space-y-3">
        @foreach($actions as $a)
        @php $ic = $iconClasses[$a['color']] ?? $iconClasses['slate']; @endphp
        <li class="flex gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
            <div class="w-8 h-8 rounded-lg {{ $ic }} flex items-center justify-center shrink-0">
                <x-icon name="{{ $a['icon'] }}" style="solid" class="w-4 h-4" />
            </div>
            <div>
                <span class="font-semibold text-slate-800 dark:text-white">{{ $a['title'] }}</span>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">{{ $a['text'] }}</p>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endif
