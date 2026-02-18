@php
    $dashboardRoute = (auth()->user()?->isPro() ?? false) && Route::has('core.dashboard') ? route('core.dashboard') : route('paneluser.index');
    $rarity = $medal->rarity ?? 'silver';
    $rarityCardClass = match($rarity) {
        'bronze' => 'bg-gradient-to-br from-amber-800/80 to-amber-900/90 dark:from-amber-900/80 dark:to-amber-950/90 border-amber-700/50 dark:border-amber-600/30',
        'silver' => 'bg-gradient-to-br from-slate-300 to-slate-500 dark:from-slate-600 dark:to-slate-700 border-slate-400 dark:border-slate-500/50 shadow-inner',
        'gold' => 'bg-gradient-to-br from-amber-400 via-yellow-300 to-amber-600 dark:from-amber-500 dark:via-yellow-400 dark:to-amber-600 border-amber-500/50 shadow-lg shadow-amber-500/30 dark:shadow-amber-500/20',
        'platinum' => 'bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400 dark:from-indigo-500 dark:via-purple-500 dark:to-pink-500 border-purple-400/60',
        default => 'bg-gradient-to-br from-slate-400 to-slate-600 border-slate-500/50',
    };
    $rarityIconClass = match($rarity) {
        'bronze' => 'bg-amber-900/40 text-amber-200',
        'silver' => 'bg-slate-200/50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200',
        'gold' => 'bg-amber-200/40 dark:bg-yellow-500/30 text-amber-900 dark:text-amber-100',
        'platinum' => 'bg-white/30 text-white',
        default => 'bg-slate-500/30 text-slate-200',
    };
    $rarityTitleClass = match($rarity) {
        'bronze', 'platinum' => 'text-white drop-shadow-sm',
        'silver' => 'text-slate-800 dark:text-slate-200',
        'gold' => 'text-amber-950 dark:text-amber-100 drop-shadow-sm',
        default => 'text-slate-200 dark:text-slate-100',
    };
    $rarityDescClass = match($rarity) {
        'bronze', 'platinum' => 'text-white/90',
        'silver' => 'text-slate-600 dark:text-slate-400',
        'gold' => 'text-amber-900/90 dark:text-amber-100/90',
        default => 'text-slate-400 dark:text-slate-500',
    };
@endphp
<x-paneluser::layouts.master :title="replace_plan_name_in_text($medal->title) . ' | Conquistas'">
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-12 px-4">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs font-bold text-amber-500 dark:text-amber-400 uppercase tracking-widest" aria-label="Navegação">
        <a href="{{ $dashboardRoute }}" class="hover:underline">Painel</a>
        <span class="w-1 h-1 rounded-full bg-slate-500" aria-hidden="true"></span>
        <a href="{{ route('user.achievements.index') }}" class="hover:underline">Conquistas</a>
        <span class="w-1 h-1 rounded-full bg-slate-500" aria-hidden="true"></span>
        <span class="text-slate-400 dark:text-slate-500 truncate">{{ replace_plan_name_in_text($medal->title) }}</span>
    </nav>

    {{-- Card principal --}}
    <div class="relative overflow-hidden rounded-[2rem] border backdrop-blur-xl p-8 sm:p-12 {{ $rarityCardClass }}" data-tour="achievements-show-detail">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="w-24 h-24 rounded-2xl flex items-center justify-center shrink-0 {{ $rarityIconClass }}">
                <x-icon name="{{ $medal->icon_name ?? 'medal' }}" style="duotone" class="w-14 h-14" />
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-black {{ $rarityTitleClass }} tracking-tight mb-2">{{ replace_plan_name_in_text($medal->title) }}</h1>
                @if($medal->description)
                    <p class="{{ $rarityDescClass }} text-base leading-relaxed">{{ replace_plan_name_in_text($medal->description) }}</p>
                @endif
                @if($unlocked && $userMedal?->unlocked_at)
                    <span class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-500/25 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-sm font-bold">
                        <x-icon name="check" style="solid" class="w-4 h-4" /> Conquistada em {{ $userMedal->unlocked_at->format('d/m/Y') }}
                    </span>
                @endif
            </div>
            <a href="{{ route('user.achievements.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/20 dark:bg-white/10 border border-white/30 text-white font-medium hover:bg-white/30 transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar
            </a>
        </div>
    </div>

    {{-- Conteúdo educativo --}}
    <div class="space-y-6">
        @if($medal->explanation)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 backdrop-blur p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                    <x-icon name="circle-question" style="duotone" class="w-5 h-5 text-amber-500" />
                    O que quer dizer?
                </h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ replace_plan_name_in_text($medal->explanation) }}</p>
            </div>
        @endif

        @if($medal->tips)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 backdrop-blur p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                    <x-icon name="lightbulb" style="duotone" class="w-5 h-5 text-emerald-500" />
                    Por que você conquistou
                </h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ replace_plan_name_in_text($medal->tips) }}</p>
            </div>
        @endif

        @if($medal->incentive_message)
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50/80 dark:bg-emerald-900/20 backdrop-blur p-6 sm:p-8">
                <h2 class="text-lg font-bold text-emerald-800 dark:text-emerald-200 mb-3 flex items-center gap-2">
                    <x-icon name="rocket" style="duotone" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    Continue assim!
                </h2>
                <p class="text-emerald-800/90 dark:text-emerald-200/90 leading-relaxed">{{ replace_plan_name_in_text($medal->incentive_message) }}</p>
            </div>
        @endif
    </div>

    @if($unlocked)
        {{-- Compartilhar --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 backdrop-blur p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <x-icon name="share-nodes" style="duotone" class="w-5 h-5 text-primary-500" />
                Compartilhar conquista
            </h2>
            <div class="flex flex-wrap gap-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#1877f2] hover:bg-[#166fe5] text-white font-bold text-sm transition-colors shadow-md hover:shadow-lg">
                    <x-icon name="facebook" style="brands" class="w-5 h-5" />
                    Facebook
                </a>
                <a href="https://wa.me/?text={{ urlencode($shareText) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#25d366] hover:bg-[#20bd5a] text-white font-bold text-sm transition-colors shadow-md hover:shadow-lg">
                    <x-icon name="whatsapp" style="brands" class="w-5 h-5" />
                    WhatsApp
                </a>
            </div>
        </div>
    @else
        {{-- CTA bloqueada --}}
        <div class="rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/50 p-8 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                <x-icon name="lock" style="solid" class="w-8 h-8 text-slate-500" />
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Desbloqueie esta medalha</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-6 max-w-md mx-auto">
                Lançando transações, mantendo reserva e seguindo o método 50/30/20 você desbloqueia novas conquistas.
            </p>
            <a href="{{ route('user.achievements.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold transition-colors">
                <x-icon name="medal" style="duotone" class="w-5 h-5" />
                Ver outras conquistas
            </a>
        </div>
    @endif
</div>

@if(!empty($pageTourId) && !empty($pageTourSteps))
@push('scripts')
<script>
(function() {
    var tourId = @json($pageTourId);
    var steps = @json($pageTourSteps);
    function register() {
        if (window.registerVertexTourSteps && steps && steps.length) {
            window.registerVertexTourSteps(tourId, steps);
            return;
        }
        setTimeout(register, 50);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', register);
    } else {
        register();
    }
})();
</script>
@endpush
@endif
</x-paneluser::layouts.master>
