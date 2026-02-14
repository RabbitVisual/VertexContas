@props([
    'title' => '',
    'value' => '',
    'icon' => 'chart-line',
    'color' => 'primary',
    'trend' => null,
    'trendValue' => null,
])

@php
    $colorMap = [
        'success' => [
            'bg' => 'bg-emerald-500/10 dark:bg-emerald-500/20',
            'icon' => 'text-emerald-600 dark:text-emerald-400',
            'gradient' => 'from-emerald-500 to-emerald-600',
        ],
        'danger' => [
            'bg' => 'bg-rose-500/10 dark:bg-rose-500/20',
            'icon' => 'text-rose-600 dark:text-rose-400',
            'gradient' => 'from-rose-500 to-rose-600',
        ],
        'warning' => [
            'bg' => 'bg-amber-500/10 dark:bg-amber-500/20',
            'icon' => 'text-amber-600 dark:text-amber-400',
            'gradient' => 'from-amber-500 to-amber-600',
        ],
        'info' => [
            'bg' => 'bg-blue-500/10 dark:bg-blue-500/20',
            'icon' => 'text-blue-600 dark:text-blue-400',
            'gradient' => 'from-blue-500 to-blue-600',
        ],
        'primary' => [
            'bg' => 'bg-primary-500/10 dark:bg-primary-500/20',
            'icon' => 'text-primary-600 dark:text-primary-400',
            'gradient' => 'from-primary-500 to-primary-dark',
        ],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
    $trendIcon = $trend === 'up' ? 'arrow-trend-up' : ($trend === 'down' ? 'arrow-trend-down' : null);
    $trendColor = $trend === 'up' ? 'text-emerald-500' : ($trend === 'down' ? 'text-rose-500' : '');
@endphp

<div {{ $attributes->merge([
    'class' => 'group relative overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 backdrop-blur-sm shadow-sm hover:shadow-xl hover:border-primary-200 dark:hover:border-primary-800 transition-all duration-300'
]) }}>
    {{-- Subtle gradient glow on hover --}}
    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-transparent dark:from-primary-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

    <div class="relative flex items-start justify-between gap-4 p-6">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                {{ $title }}
            </p>
            <h3 class="text-2xl xl:text-3xl font-black text-slate-800 dark:text-white tracking-tight tabular-nums">
                {{ $value }}
            </h3>
            @if($trend && $trendValue)
                <div class="flex items-center gap-2 mt-3">
                    <x-icon :name="$trendIcon" style="duotone" class="w-4 h-4 {{ $trendColor }}" />
                    <span class="text-sm font-bold {{ $trendColor }}">{{ $trendValue }}</span>
                    <span class="text-xs text-slate-400">vs mês anterior</span>
                </div>
            @endif
        </div>

        <div class="shrink-0 w-14 h-14 rounded-2xl {{ $c['bg'] }} flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
            <x-icon :name="$icon" style="duotone" class="w-7 h-7 {{ $c['icon'] }}" />
        </div>
    </div>
</div>
