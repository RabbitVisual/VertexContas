@php
    $isPro = auth()->user()?->isPro() ?? false;
    $stockBalance = $stockBalance ?? 0;
    $financialScore = $vertexBot['financial_score'] ?? 0;
    // 0 = em análise (sem dados); 1-40 = em risco; 41-70 = atenção; 71-100 = sem risco
    $scoreLabel = $financialScore === 0 ? 'Em análise' : ($financialScore <= 40 ? 'Em risco' : ($financialScore <= 70 ? 'Atenção' : 'Sem risco'));
    $scoreTextClass = $financialScore === 0 ? 'text-slate-500 dark:text-slate-400' : ($financialScore <= 40 ? 'text-red-600 dark:text-red-400' : ($financialScore <= 70 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400'));
    $scoreStrokeClass = $financialScore === 0 ? 'text-slate-400 dark:text-slate-500' : ($financialScore <= 40 ? 'text-red-500 dark:text-red-400' : ($financialScore <= 70 ? 'text-amber-500 dark:text-amber-400' : 'text-emerald-500 dark:text-emerald-400'));
    $scoreBorderClass = $financialScore === 0 ? 'hover:border-slate-400/30' : ($financialScore <= 40 ? 'hover:border-red-500/30' : ($financialScore <= 70 ? 'hover:border-amber-500/30' : 'hover:border-emerald-500/30'));
    $totalIncome = $monthlyIncome ?? 0;
    $totalExpense = $monthlyExpense ?? 0;
    $flowCapacity = $flowCapacity ?? 0;
    $incomeBreakdown = $incomeBreakdown ?? collect();
    $accounts = $accounts ?? collect();
    $greeting = match (true) {
        now()->hour < 12 => 'Bom dia',
        now()->hour < 18 => 'Boa tarde',
        default => 'Boa noite',
    };
    $firstName = auth()->user()->first_name ?? 'Membro';
@endphp
<x-paneluser::layouts.master :title="'Dashboard'">
<div class="max-w-6xl mx-auto animate-in fade-in slide-in-from-bottom-4 duration-700 px-4 pb-24 sm:pb-28">
    {{-- Layout leigo: Saudação + Card gigante Saldo + Card Entrou/Saiu --}}
    <nav class="flex items-center gap-2 text-xs font-bold text-primary-600 dark:text-primary-500 uppercase tracking-widest mb-4" aria-label="Navegação">
        <a href="{{ route('paneluser.index') }}" class="hover:underline">Painel</a>
        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
        <span class="text-gray-400 dark:text-gray-500">Visão Geral</span>
    </nav>
    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-6">
        {{ $greeting }}, {{ $firstName }}!
    </h1>

    {{-- Card gigante: Seu saldo hoje --}}
    <div class="relative overflow-hidden rounded-[2rem] mb-6 bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-10 shadow-sm dark:shadow-none" data-tour="dashboard-balance">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 bg-primary-500/10 dark:bg-primary-500/20 rounded-full blur-[80px]"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0">
                <x-icon name="wallet" style="duotone" class="w-7 h-7" />
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-0.5">Seu saldo hoje</p>
                <p class="sensitive-value text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tabular-nums leading-tight"><x-core::financial-value :value="$stockBalance" /></p>
            </div>
        </div>
    </div>

    {{-- Card: O que entrou vs O que saiu --}}
    <div class="grid grid-cols-2 gap-4 mb-8" data-tour="dashboard-income">
        <div class="rounded-2xl border border-gray-200 dark:border-white/5 bg-white dark:bg-gray-950 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <x-icon name="arrow-trend-up" style="duotone" class="w-5 h-5" />
                </div>
                <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Entrou</span>
            </div>
            <p class="sensitive-value text-2xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums"><x-core::financial-value :value="$totalIncome" /></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Receitas do mês</p>
        </div>
        <div class="rounded-2xl border border-gray-200 dark:border-white/5 bg-white dark:bg-gray-950 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <x-icon name="arrow-trend-down" style="duotone" class="w-5 h-5" />
                </div>
                <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Saiu</span>
            </div>
            <p class="sensitive-value text-2xl font-black text-rose-600 dark:text-rose-400 tabular-nums"><x-core::financial-value :value="$totalExpense" /></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Despesas do mês</p>
        </div>
    </div>

    {{-- Minhas Contas + Transações - grid 1/3 + 2/3 (estilo VertexCBAV) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-8">
        {{-- Minhas Contas --}}
        <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-primary-500/20">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                    </div>
                    Minhas Contas
                </h3>
                @if(Route::has('core.accounts.create') && !($inspectionReadOnly ?? false) && !($subscriptionReadOnly ?? false))
                    <a href="{{ route('core.accounts.create') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline uppercase tracking-wider">Nova</a>
                @endif
            </div>
            <div class="p-5 pb-6 space-y-2 max-h-64 overflow-y-auto">
                @forelse($accounts as $account)
                    <a href="{{ Route::has('core.accounts.show') ? route('core.accounts.show', $account) : '#' }}" class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-white/5 hover:bg-primary-50 dark:hover:bg-primary-500/10 border border-transparent hover:border-primary-200 dark:hover:border-primary-500/20 transition-all group">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-300 text-sm">{{ $account->name }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider capitalize">{{ $account->type ?? 'Conta' }}</p>
                        </div>
                        <p class="sensitive-value font-bold text-primary-600 dark:text-primary-400 tabular-nums text-sm"><x-core::financial-value :value="$account->balance" /></p>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50">
                        <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                            <x-icon name="building-columns" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                        </div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma conta</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 max-w-xs">Cadastre sua primeira conta para começar.</p>
                        @if(Route::has('core.accounts.create') && !($inspectionReadOnly ?? false) && !($subscriptionReadOnly ?? false))
                            <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar conta
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Transações Recentes --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-primary-500/20">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <x-icon name="receipt" style="duotone" class="w-5 h-5" />
                    </div>
                    Transações Recentes
                </h3>
                <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline uppercase tracking-wider">
                    Ver todas
                    <x-icon name="arrow-right" style="solid" class="w-3.5 h-3.5" />
                </a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-white/5 max-h-80 overflow-y-auto pb-6">
                @forelse($recentTransactions as $transaction)
                    <a href="{{ ($inspectionReadOnly ?? false) ? route('core.transactions.index') : (Route::has('core.transactions.edit') ? route('core.transactions.edit', $transaction) : route('core.transactions.index')) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl {{ $transaction->type === 'income' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }} flex items-center justify-center shrink-0 ring-1 ring-black/5 dark:ring-white/10">
                                <x-icon name="{{ $transaction->type === 'income' ? 'arrow-up' : 'arrow-down' }}" style="solid" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400">{{ $transaction->description }}</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $transaction->date->format('d/m/Y') }} · {{ $transaction->category->name ?? 'Geral' }}</p>
                            </div>
                        </div>
                        <span class="sensitive-value font-mono font-bold tabular-nums text-sm {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }} <x-core::financial-value :value="$transaction->amount" />
                        </span>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-20 h-20 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                            <x-icon name="receipt" style="duotone" class="w-10 h-10 opacity-40 dark:opacity-20" />
                        </div>
                        <p class="font-bold text-gray-900 dark:text-white mb-1">Nenhuma transação recente</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Registre sua primeira movimentação.</p>
                        @if(!($inspectionReadOnly ?? false) && !($subscriptionReadOnly ?? false))
                            <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Nova transação
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Metas - bloco full width --}}
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden mb-8 transition-all duration-300 hover:shadow-xl hover:border-amber-500/20">
        <div class="p-6 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <x-icon name="bullseye" style="duotone" class="w-5 h-5" />
                </div>
                Metas
            </h3>
            @if(Route::has('core.goals.index'))
                <a href="{{ route('core.goals.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline uppercase tracking-wider">
                    Ver todas
                    <x-icon name="arrow-right" style="solid" class="w-3.5 h-3.5" />
                </a>
            @endif
        </div>
        <div class="p-6 pb-8 space-y-6">
            @forelse($goals as $goal)
                @php $pct = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-bold text-gray-900 dark:text-white">{{ $goal->name }}</span>
                        <span class="font-black text-primary-500 tabular-nums">{{ format_percent($pct, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden border border-gray-200/50 dark:border-gray-600/50">
                        <div class="bg-gradient-to-r from-primary-500 to-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50">
                    <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                        <x-icon name="bullseye" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                    </div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma meta cadastrada</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 max-w-xs">Crie metas para acompanhar seus objetivos.</p>
                    @if(Route::has('core.goals.create') && !($inspectionReadOnly ?? false))
                        <a href="{{ route('core.goals.create') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                            <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar meta
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    @if(!$isPro)
        {{-- CTA sutil VertexBot (Pro): análise personalizada --}}
        <div class="rounded-2xl border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 p-5 mb-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Quer uma análise personalizada dos seus gastos? O <strong>Vertex Bot</strong> ({{ plan_pro_name() }}) te ajuda a entender para onde vai seu dinheiro e como melhorar.
            </p>
            <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 mt-3 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                Conhecer {{ plan_pro_name() }}
                <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
            </a>
        </div>
        {{-- CTA único para assinatura PRO (estilo VertexCBAV: card hero + botão destaque) --}}
        <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-10 shadow-sm dark:shadow-none hover:shadow-xl hover:border-amber-500/30 transition-all duration-300">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-amber-500/10 dark:bg-amber-500/20 rounded-full blur-[80px]"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-primary-500/10 dark:bg-primary-500/20 rounded-full blur-[80px]"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0 ring-1 ring-black/5 dark:ring-white/10">
                        <x-icon name="sparkles" style="duotone" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ plan_pro_name() }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ pro_benefits_short_description('cta') }}</p>
                    </div>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-400 text-amber-950 font-black text-sm uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-amber-500/25 shrink-0">
                    Assinar {{ plan_pro_name() }}
                    <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                </a>
            </div>
        </div>
    @endif
</div>

@if(!empty($dashboardTourId) && !empty($dashboardTourSteps))
@push('scripts')
<script>
(function() {
    var tourId = @json($dashboardTourId);
    var steps = @json($dashboardTourSteps);
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
