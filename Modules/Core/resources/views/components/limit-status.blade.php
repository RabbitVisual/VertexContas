@props(['entity', 'label'])

@php
    $limitService = app(\Modules\Core\Services\SubscriptionLimitService::class);
    $stats = $limitService->getUsageStats(auth()->user(), $entity);
    $isPro = $stats['limit'] === 'unlimited';
    $pct = min(100, $stats['percentage']);
    $barColor = $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-amber-500' : 'bg-primary');
@endphp

@if(!$isPro)
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 -mx-5 -mt-5 mb-4">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    {{ $label }}
                    @if($pct >= 100)
                        <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold rounded-lg">Limite</span>
                    @endif
                </h3>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 tabular-nums">{{ $stats['current'] }}/{{ $stats['limit'] }}</span>
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
