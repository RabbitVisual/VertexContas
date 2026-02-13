@props([
    'budget',
])

@php
    $usage = $budget->usage_percentage;
    $isExceeded = $budget->is_exceeded;
    $statusColor = $isExceeded ? 'danger' : ($usage > 80 ? 'warning' : 'success');
    $barColor = $isExceeded ? 'bg-red-500' : ($usage > 80 ? 'bg-amber-500' : 'bg-emerald-500');
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700']) }}>
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h4 class="text-lg font-black text-slate-800 dark:text-white mb-1">
                {{ $budget->category->name }}
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Orçamento {{ $budget->period === 'monthly' ? 'Mensal' : 'Anual' }}
            </p>
        </div>

        <div class="w-10 h-10 rounded-full flex items-center justify-center"
             style="background-color: {{ $budget->category->color }}20">
            <i class="fa-solid fa-{{ $budget->category->icon ?? 'wallet' }}"
               style="color: {{ $budget->category->color }}"></i>
        </div>
    </div>

    <div class="space-y-2">
        <div class="flex justify-between text-sm">
            <span class="font-bold text-slate-600 dark:text-slate-300">
                R$ {{ number_format($budget->spent_amount, 2, ',', '.') }} / R$ {{ number_format($budget->limit_amount, 2, ',', '.') }}
            </span>
            <span class="font-bold {{ $isExceeded ? 'text-red-500' : 'text-slate-600 dark:text-slate-300' }}">
                {{ number_format($usage, 1) }}%
            </span>
        </div>

        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
            <div class="{{ $barColor }} h-full rounded-full transition-all duration-500"
                 style="width: {{ min(100, $usage) }}%">
            </div>
        </div>

        @if($isExceeded)
            <p class="text-xs text-red-600 dark:text-red-400 font-bold mt-2">
                <x-icon name="triangle-exclamation" style="solid" /> Orçamento excedido!
            </p>
        @elseif($usage > 80)
            <p class="text-xs text-amber-600 dark:text-amber-400 font-bold mt-2">
                <x-icon name="exclamation-circle" style="solid" /> Atenção: {{ number_format(100 - $usage, 1) }}% restante
            </p>
        @else
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                Restam R$ {{ number_format($budget->remaining_amount, 2, ',', '.') }}
            </p>
        @endif
    </div>
</div>

