@props([
    'budget',
])

@php
    $usage = $budget->usage_percentage;
    $isExceeded = $budget->is_exceeded;
    $statusColor = $isExceeded ? 'danger' : ($usage > 80 ? 'warning' : 'success');
    $barColor = $isExceeded ? 'bg-rose-500' : ($usage > 80 ? 'bg-amber-500' : 'bg-emerald-500');
    $cat = $budget->category;
    $catColor = $cat?->color ?? '#11c76f';
    $catName = $cat?->name ?? 'Geral';
    $catIcon = $cat?->icon ?? 'wallet';
@endphp

<div {{ $attributes->merge([
    'class' => 'group relative overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 backdrop-blur-sm shadow-sm hover:shadow-lg hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-300 opacity-0-start animate-fade-in-up'
]) }}>
    <div class="flex items-start justify-between gap-4 p-6 mb-4">
        <div class="flex-1 min-w-0">
            <h4 class="text-base font-bold text-slate-800 dark:text-white mb-0.5">
                {{ $catName }}
            </h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Orçamento {{ $budget->period === 'monthly' ? 'Mensal' : 'Anual' }}
            </p>
        </div>

        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-110"
             style="background-color: {{ $catColor }}20; color: {{ $catColor }}">
            <x-icon :name="$catIcon" style="duotone" class="w-5 h-5 [color:inherit]" />
        </div>
    </div>

    <div class="px-6 pb-6 space-y-2">
        <div class="flex justify-between text-sm">
            <span class="font-semibold text-slate-600 dark:text-slate-300">
                R$ {{ number_format($budget->spent_amount, 2, ',', '.') }} / R$ {{ number_format($budget->limit_amount, 2, ',', '.') }}
            </span>
            <span class="font-bold {{ $isExceeded ? 'text-rose-500' : 'text-slate-600 dark:text-slate-300' }}">
                {{ number_format($usage, 1) }}%
            </span>
        </div>

        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
            <div class="{{ $barColor }} h-full rounded-full transition-all duration-700 ease-out"
                 style="width: {{ min(100, $usage) }}%">
            </div>
        </div>

        @if($isExceeded)
            <p class="text-xs text-rose-600 dark:text-rose-400 font-semibold mt-2 flex items-center gap-1.5">
                <x-icon name="triangle-exclamation" style="duotone" class="w-3.5 h-3.5" /> Orçamento excedido!
            </p>
        @elseif($usage > 80)
            <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-2 flex items-center gap-1.5">
                <x-icon name="exclamation-circle" style="duotone" class="w-3.5 h-3.5" /> Atenção: {{ number_format(100 - $usage, 1) }}% restante
            </p>
        @else
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                Restam R$ {{ number_format($budget->remaining_amount, 2, ',', '.') }}
            </p>
        @endif
    </div>
</div>
