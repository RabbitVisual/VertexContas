@props(['entity', 'label', 'showOnlyWhenUnderLimit' => false, 'compact' => false, 'mode' => 'card'])

@php
    $limitService = app(\Modules\Core\Services\SubscriptionLimitService::class);
    $stats = $limitService->getUsageStats(auth()->user(), $entity);
    $detail = $limitService->checkLimit(auth()->user(), $entity);
    $isPro = ($stats['limit'] ?? null) === 'unlimited';
    $pct = min(100, $stats['percentage']);
    $atLimit = ($stats['limit'] ?? null) !== 'unlimited' && ($stats['current'] ?? 0) >= ($stats['limit'] ?? 0);
    $barColor = $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-amber-500' : 'bg-emerald-500');
    $limitDisplay = $stats['limit_display'] ?? ($stats['limit'] === 'unlimited' ? 'Ilimitado' : $stats['limit']);
    $shouldShow = !$isPro && (!($showOnlyWhenUnderLimit && $atLimit));

    $upsellMessage = $detail['upsell_message'] ?? '';
    if ($upsellMessage === '' && $atLimit) {
        $upsellMessage = $limitService->getLimitReachedMessage(auth()->user(), $entity);
    }
@endphp

@if($shouldShow)
    @if($mode === 'banner' && $atLimit)
        <div class="mb-6 rounded-2xl border border-amber-300/70 dark:border-amber-500/60 bg-amber-50/80 dark:bg-amber-950/40 px-5 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500/15 text-amber-700 dark:text-amber-300">
                    <x-icon name="triangle-exclamation" style="duotone" class="w-4 h-4" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-100">
                        Você atingiu o limite de {{ \Illuminate\Support\Str::lower($label) }} do plano Grátis.
                    </p>
                    <p class="mt-1 text-xs text-amber-900/80 dark:text-amber-100/80">
                        Organize sua vida financeira sem limites com o <span class="font-semibold">{{ plan_pro_name() }}</span>.
                        {!! $upsellMessage ? ' ' . e($upsellMessage) : '' !!}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-amber-900/80 dark:text-amber-100/80 font-medium tabular-nums">
                    {{ $stats['current'] }}/{{ $limitDisplay }}
                </span>
                <a href="{{ route('user.subscription.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-400 shadow-sm transition-colors">
                    <x-icon name="crown" style="solid" class="w-3.5 h-3.5" />
                    Desbloquear com Pro
                </a>
            </div>
        </div>
    @endif

    @if($compact)
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
            <span class="text-xs tabular-nums font-medium {{ $pct >= 100 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">{{ $stats['current'] }}/{{ $limitDisplay }}</span>
            <div class="flex-1 min-w-0 max-w-20 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                <div class="{{ $barColor }} h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
            </div>
            <a href="{{ route('user.subscription.index') }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline shrink-0">{{ plan_pro_name() }}</a>
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
