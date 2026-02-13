@props([
    'goal',
])

@php
    $progress = $goal->progress_percentage;
    $isCompleted = $goal->is_completed;
    $progressColor = $isCompleted ? 'bg-emerald-500' : ($progress > 75 ? 'bg-primary' : ($progress > 50 ? 'bg-blue-500' : 'bg-amber-500'));
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700']) }}>
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h4 class="text-lg font-black text-slate-800 dark:text-white mb-1">
                {{ $goal->name }}
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Meta: R$ {{ number_format($goal->target_amount, 2, ',', '.') }}
            </p>
        </div>

        @if($isCompleted)
            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                <x-icon name="check" style="solid" class="text-emerald-600 dark:text-emerald-400" />
            </div>
        @else
            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                <x-icon name="bullseye" style="solid" class="text-primary" />
            </div>
        @endif
    </div>

    <div class="space-y-2">
        <div class="flex justify-between text-sm">
            <span class="font-bold text-slate-600 dark:text-slate-300">
                R$ {{ number_format($goal->current_amount, 2, ',', '.') }}
            </span>
            <span class="font-bold text-primary">
                {{ number_format($progress, 1) }}%
            </span>
        </div>

        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
            <div class="{{ $progressColor }} h-full rounded-full transition-all duration-500"
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
            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mt-2">
                <x-icon name="trophy" style="solid" /> Meta alcançada!
            </p>
        @endif
    </div>
</div>

