@props(['entity', 'label', 'showOnlyWhenUnderLimit' => false, 'compact' => false])

@php
    $limitService = app(\Modules\Core\Services\SubscriptionLimitService::class);
    $stats = $limitService->getUsageStats(auth()->user(), $entity);
    $isPro = ($stats['limit'] ?? null) === 'unlimited';
    $pct = min(100, $stats['percentage']);
    $atLimit = ($stats['limit'] ?? null) !== 'unlimited' && ($stats['current'] ?? 0) >= ($stats['limit'] ?? 0);
    $barColor = $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-amber-500' : 'bg-emerald-500');
    $limitDisplay = $stats['limit_display'] ?? ($stats['limit'] === 'unlimited' ? 'Ilimitado' : $stats['limit']);
    $shouldShow = !$isPro && (!($showOnlyWhenUnderLimit && $atLimit));
@endphp

@if($shouldShow)
    @if($compact)
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
            <span class="text-xs tabular-nums font-medium {{ $pct >= 100 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">{{ $stats['current'] }}/{{ $limitDisplay }}</span>
            <div class="flex-1 min-w-0 max-w-20 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                <div class="{{ $barColor }} h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
            </div>
            <a href="{{ route('user.subscription.index') }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline shrink-0">Vertex Pro</a>
        </div>
    @else
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 -mx-5 -mt-5 mb-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                        {{ $label }}
                        @if($pct >= 100)
                            <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold rounded-lg">Limite</span>
                        @endif
                    </h3>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 tabular-nums">{{ $stats['current'] }}/{{ $limitDisplay }}</span>
                </div>
            </div>
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Uso</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 tabular-nums">{{ $pct }}%</span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 mb-4">
                <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-200 dark:border-slate-700">
                <span class="text-xs {{ $pct >= 100 ? 'text-red-600 dark:text-red-400' : 'text-slate-500 dark:text-slate-400' }} font-medium">
                    {{ $pct }}% utilizado
                </span>
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white text-xs font-semibold rounded-xl transition-colors">
                    <x-icon name="crown" style="solid" class="w-3.5 h-3.5" />
                    Desbloquear Ilimitado
                </a>
            </div>
        </div>
    @endif
@endif
