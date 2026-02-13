@props([
    'title' => '',
    'value' => '',
    'icon' => 'chart-line',
    'color' => 'primary',
    'trend' => null,
    'trendValue' => null,
])

@php
    $colorClasses = match($color) {
        'success' => 'from-emerald-500 to-emerald-600',
        'danger' => 'from-red-500 to-red-600',
        'warning' => 'from-amber-500 to-amber-600',
        'info' => 'from-blue-500 to-blue-600',
        default => 'from-primary to-primary-dark',
    };

    $trendIcon = $trend === 'up' ? 'arrow-trend-up' : ($trend === 'down' ? 'arrow-trend-down' : null);
    $trendColor = $trend === 'up' ? 'text-emerald-500' : ($trend === 'down' ? 'text-red-500' : '');
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700 transition-all hover:shadow-xl']) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                {{ $title }}
            </p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white mb-1">
                {{ $value }}
            </h3>

            @if($trend && $trendValue)
                <div class="flex items-center gap-2 mt-2">
                    <i class="fa-solid fa-{{ $trendIcon }} {{ $trendColor }} text-sm"></i>
                    <span class="text-sm font-bold {{ $trendColor }}">{{ $trendValue }}</span>
                    <span class="text-xs text-slate-400">vs mês anterior</span>
                </div>
            @endif
        </div>

        <div class="w-14 h-14 bg-gradient-to-br {{ $colorClasses }} rounded-xl flex items-center justify-center shadow-lg">
            <i class="fa-solid fa-{{ $icon }} text-white text-2xl"></i>
        </div>
    </div>
</div>
