@props([
    'goal',
])

@php
    $progress = $goal->progress_percentage;
    $isCompleted = $goal->is_completed;
    $progressColor = $isCompleted ? 'bg-emerald-500' : ($progress > 75 ? 'bg-primary-500' : ($progress > 50 ? 'bg-blue-500' : 'bg-amber-500'));
@endphp

<div {{ $attributes->merge([
    'class' => 'group relative overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 backdrop-blur-sm shadow-sm hover:shadow-lg hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-300 opacity-0-start animate-fade-in-up'
]) }}>
    <div class="flex items-start justify-between gap-4 p-6 mb-4">
        <div class="flex-1 min-w-0">
            <h4 class="text-base font-bold text-slate-800 dark:text-white mb-0.5">
                {{ $goal->name }}
            </h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Meta: R$ {{ number_format($goal->target_amount, 2, ',', '.') }}
            </p>
        </div>

        @if($isCompleted)
            <div class="w-11 h-11 rounded-xl bg-emerald-500/20 dark:bg-emerald-500/30 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                <x-icon name="check-circle" style="duotone" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            </div>
        @else
            <div class="w-11 h-11 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                <x-icon name="bullseye" style="duotone" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
            </div>
        @endif
    </div>

    <div class="px-6 pb-6 space-y-2">
        <div class="flex justify-between text-sm">
            <span class="font-semibold text-slate-600 dark:text-slate-300">
                R$ {{ number_format($goal->current_amount, 2, ',', '.') }}
            </span>
            <span class="font-bold text-primary-600 dark:text-primary-400">
                {{ number_format($progress, 1) }}%
            </span>
        </div>

        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
            <div class="{{ $progressColor }} h-full rounded-full transition-all duration-700 ease-out"
                 style="width: {{ min(100, $progress) }}%">
            </div>
        </div>

        @if(!$isCompleted)
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                Faltam R$ {{ number_format($goal->remaining_amount, 2, ',', '.') }}
                @if($goal->deadline)
                    · Prazo: {{ $goal->deadline->format('d/m/Y') }}
                @endif
            </p>
        @else
            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-2 flex items-center gap-1.5">
                <x-icon name="trophy" style="duotone" class="w-3.5 h-3.5" /> Meta alcançada!
            </p>
        @endif
    </div>
</div>
