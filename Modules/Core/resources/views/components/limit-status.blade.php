@props(['entity', 'label'])

@php
    $limitService = app(\Modules\Core\Services\SubscriptionLimitService::class);
    $stats = $limitService->getUsageStats(auth()->user(), $entity);
    $isPro = $stats['limit'] === 'unlimited';
@endphp

@if(!$isPro)
    <div class="mb-8 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">

        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-colors"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-3">
            <div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg flex items-center">
                    {{ $label }}
                    @if($stats['percentage'] >= 100)
                        <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-[10px] uppercase font-bold rounded-full">Limite Atingido</span>
                    @endif
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Uso do seu plano gratuito atual</p>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['current'] }}</span>
                <span class="text-sm text-slate-400 font-medium">/ {{ $stats['limit'] }}</span>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="relative w-full bg-slate-100 dark:bg-slate-700 rounded-full h-3 overflow-hidden mb-4">
             <div class="absolute top-0 left-0 h-full {{ $stats['percentage'] >= 100 ? 'bg-red-500' : ($stats['percentage'] >= 80 ? 'bg-amber-500' : 'bg-primary') }} rounded-full transition-all duration-1000"
                  style="width: {{ $stats['percentage'] }}%">
            </div>
        </div>

        <!-- CTA -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 border-t border-slate-50 dark:border-slate-700/50 pt-3">
            <span class="text-xs font-semibold {{ $stats['percentage'] >= 100 ? 'text-red-500' : 'text-slate-500' }}">
                {{ $stats['percentage'] }}% da capacidade utilizada
            </span>

            <a href="{{ route('user.subscription.index') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white transition-all shadow-lg shadow-slate-200 dark:shadow-none flex items-center justify-center">
                <x-icon name="crown" class="mr-2 w-3 h-3" />
                Desbloquear Ilimitado
            </a>
        </div>
    </div>
@endif
