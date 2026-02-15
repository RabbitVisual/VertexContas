@php
    $dashboardRoute = (auth()->user()?->isPro() ?? false) && Route::has('core.dashboard') ? route('core.dashboard') : route('paneluser.index');
    $earnedCount = $earnedCount ?? 0;
    $totalCount = $totalCount ?? 0;
@endphp
<x-paneluser::layouts.master :title="'Gabinete de Medalhas'">
<style>
    @keyframes platinum-border {
        0%, 100% { border-color: rgba(129, 140, 248, 0.5); }
        50% { border-color: rgba(192, 132, 252, 0.8); }
    }
    .medal-platinum-animated {
        animation: platinum-border 2s ease-in-out infinite;
    }
</style>
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-800/90 to-slate-900/90 dark:from-slate-800/95 dark:to-slate-950/95 border border-slate-700/50 dark:border-slate-600/30 p-8 sm:p-12 shadow-xl backdrop-blur-xl">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-500/20 dark:bg-amber-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-500/15 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-amber-500 dark:text-amber-400 uppercase tracking-widest mb-4">
                    <a href="{{ $dashboardRoute }}" class="hover:underline">Painel</a>
                    <span class="w-1 h-1 rounded-full bg-slate-500"></span>
                    <span class="text-slate-400">Conquistas</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-[1.1] mb-3">
                    Gabinete de <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400">Medalhas</span>
                </h1>
                <p class="text-slate-300 dark:text-slate-400 text-lg max-w-md leading-relaxed mb-2">
                    Suas conquistas e medalhas desbloqueadas. Continue lançando transações e seguindo as regras 50/30/20 para desbloquear mais.
                </p>
                <p class="text-amber-400/90 dark:text-amber-300/90 font-bold text-sm">
                    Você já desbloqueou {{ $earnedCount }} de {{ $totalCount }} medalhas
                </p>
            </div>
            <a href="{{ $dashboardRoute }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/10 dark:bg-white/5 border border-slate-600/50 text-slate-200 font-medium hover:bg-white/15 transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar
            </a>
        </div>
    </div>

    {{-- Vertex Bot message --}}
    <div class="flex items-center gap-4 rounded-2xl bg-emerald-500/10 dark:bg-emerald-900/20 border border-emerald-500/20 dark:border-emerald-600/30 p-5 backdrop-blur-xl">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center shrink-0">
            <x-icon name="robot" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
        </div>
        <p class="text-emerald-800 dark:text-emerald-200 font-bold">Olha só essa coleção! Qual será o próximo nível?</p>
    </div>

    {{-- Medal grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($medals ?? [] as $medal)
            @php
                $rarity = $medal['rarity'] ?? 'silver';
                $unlocked = $medal['unlocked'] ?? false;
                $rarityCardClass = match($rarity) {
                    'bronze' => 'bg-gradient-to-br from-amber-800/80 to-amber-900/90 dark:from-amber-900/80 dark:to-amber-950/90 border-amber-700/50 dark:border-amber-600/30',
                    'silver' => 'bg-gradient-to-br from-slate-300 to-slate-500 dark:from-slate-600 dark:to-slate-700 border-slate-400 dark:border-slate-500/50 shadow-inner',
                    'gold' => 'bg-gradient-to-br from-amber-400 via-yellow-300 to-amber-600 dark:from-amber-500 dark:via-yellow-400 dark:to-amber-600 border-amber-500/50 shadow-lg shadow-amber-500/30 dark:shadow-amber-500/20',
                    'platinum' => 'bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400 dark:from-indigo-500 dark:via-purple-500 dark:to-pink-500 border-purple-400/60 medal-platinum-animated',
                    default => 'bg-gradient-to-br from-slate-400 to-slate-600 border-slate-500/50',
                };
                $rarityIconClass = match($rarity) {
                    'bronze' => 'bg-amber-900/40 text-amber-200',
                    'silver' => 'bg-slate-200/50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200',
                    'gold' => 'bg-amber-200/40 dark:bg-yellow-500/30 text-amber-900 dark:text-amber-100 animate-pulse',
                    'platinum' => 'bg-white/30 text-white',
                    default => 'bg-slate-500/30 text-slate-200',
                };
            @endphp
            <div
                x-data="{ clicked: false, showTooltip: false }"
                @mouseenter="showTooltip = true"
                @mouseleave="showTooltip = false"
                class="relative rounded-2xl border backdrop-blur-xl p-6 transition-all duration-300 {{ $unlocked ? "{$rarityCardClass} hover:scale-[1.02] cursor-pointer" : 'bg-slate-800/50 dark:bg-slate-900/50 border-slate-700/50 grayscale opacity-50' }}"
                :class="{ 'scale-110': clicked }"
                @click="if ({{ $unlocked ? 'true' : 'false' }}) { clicked = true; setTimeout(() => clicked = false, 600) }"
            >
                @if(!$unlocked)
                    <div class="absolute inset-0 flex items-center justify-center z-10">
                        <div class="w-12 h-12 rounded-full bg-slate-800/80 flex items-center justify-center">
                            <x-icon name="lock" style="solid" class="w-6 h-6 text-slate-400" />
                        </div>
                    </div>
                @endif
                <div class="relative flex flex-col items-center text-center {{ !$unlocked ? 'pointer-events-none' : '' }}">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4 {{ $unlocked ? $rarityIconClass : 'bg-slate-700/50' }}">
                        <x-icon name="{{ $medal['icon_name'] ?? 'medal' }}" style="duotone" class="w-10 h-10 {{ $unlocked ? '' : 'text-slate-500' }}" />
                    </div>
                    <h3 class="font-bold text-slate-200 dark:text-slate-100 text-sm leading-tight">{{ $medal['title'] }}</h3>
                    @if($medal['description'] ?? null)
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 line-clamp-2">{{ $medal['description'] }}</p>
                        <div x-show="showTooltip" x-transition class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 rounded-xl bg-slate-900 dark:bg-slate-800 text-slate-100 text-xs max-w-xs shadow-xl z-20 border border-slate-700" x-cloak>
                            {{ $medal['description'] }}
                            <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900 dark:border-t-slate-800"></span>
                        </div>
                    @endif
                    @if($unlocked)
                        <span class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase">
                            <x-icon name="check" style="solid" class="w-3 h-3" /> Conquistada
                        </span>
                        @if($medal['unlocked_at'] ?? null)
                            <span class="mt-1 text-[9px] text-slate-400 dark:text-slate-500">em {{ $medal['unlocked_at']->format('d/m/Y') }}</span>
                        @endif
                        <button type="button" disabled class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 dark:bg-white/5 text-slate-300 dark:text-slate-400 text-[10px] font-bold uppercase cursor-not-allowed" title="Em breve">
                            <x-icon name="share-nodes" style="solid" class="w-3.5 h-3.5" />
                            Compartilhar
                        </button>
                    @else
                        <span class="mt-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Bloqueada</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if(($medals ?? collect())->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center rounded-3xl border-2 border-dashed border-slate-600/50 bg-slate-800/30 dark:bg-slate-900/30">
            <div class="w-24 h-24 rounded-full bg-slate-700/50 flex items-center justify-center text-slate-500 mb-6">
                <x-icon name="medal" style="duotone" class="w-12 h-12 opacity-50" />
            </div>
            <h3 class="text-2xl font-black text-slate-200 dark:text-white mb-2 leading-tight">Nenhuma medalha cadastrada</h3>
            <p class="text-slate-400 max-w-sm mx-auto">As medalhas serão exibidas aqui assim que forem configuradas pelo administrador.</p>
        </div>
    @endif
</div>
</x-paneluser::layouts.master>
