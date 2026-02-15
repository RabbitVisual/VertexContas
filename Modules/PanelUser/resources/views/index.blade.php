@php
    $isPro = auth()->user()?->isPro() ?? false;
    $stockBalance = $stockBalance ?? 0;
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
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 px-4 pb-12">
    {{-- Hero - Vertex CBAV style --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary-500/5 dark:bg-primary-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-primary-600 dark:text-primary-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                    <a href="{{ route('paneluser.index') }}" class="hover:underline">Painel</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
                    <span class="text-gray-400 dark:text-gray-500">Visão Geral</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">
                    {{ $greeting }}, {{ $firstName }}!<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-emerald-600 dark:from-primary-400 dark:to-emerald-400">Suas Finanças</span>
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Aqui está o resumo das suas finanças e sua capacidade mensal.</p>
            </div>

            <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 rounded-2xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0">
                        <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">Saldo Atual</p>
                        <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white leading-tight"><x-core::financial-value :value="$stockBalance" /></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid - CBAV style --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-primary-500/30 shadow-sm hover:shadow-xl">
            <div class="relative p-6">
                <div class="w-12 h-12 rounded-2xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400 ring-1 ring-black/5 dark:ring-white/10 mb-4 shrink-0">
                    <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Estoque (Saldo)</p>
                <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$stockBalance" /></p>
            </div>
        </div>
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 shadow-sm hover:shadow-xl">
            <div class="relative p-6">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 ring-1 ring-black/5 dark:ring-white/10 mb-4 shrink-0">
                    <x-icon name="money-bill-trend-up" style="duotone" class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Receitas (mês)</p>
                <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$totalIncome" /></p>
            </div>
        </div>
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-rose-500/30 shadow-sm hover:shadow-xl">
            <div class="relative p-6">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 ring-1 ring-black/5 dark:ring-white/10 mb-4 shrink-0">
                    <x-icon name="credit-card" style="duotone" class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Despesas (mês)</p>
                <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$totalExpense" /></p>
            </div>
        </div>
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-indigo-500/30 shadow-sm hover:shadow-xl">
            <div class="relative p-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ring-1 ring-black/5 dark:ring-white/10 mb-4 shrink-0">
                    <x-icon name="chart-line" style="duotone" class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Capacidade Mensal</p>
                <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$flowCapacity" /></p>
                @if($flowCapacity > 0 && $incomeBreakdown->count() > 0)
                    <div class="mt-3" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium flex items-center gap-1">
                            Ver fontes
                            <span class="inline-block transition-transform" x-bind:class="open ? 'rotate-180' : ''">
                                <x-icon name="chevron-down" style="solid" class="w-3 h-3" />
                            </span>
                        </button>
                        <ul x-show="open" x-collapse class="mt-2 space-y-1.5 text-xs text-gray-600 dark:text-gray-400">
                            @foreach($incomeBreakdown as $item)
                                <li class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800 last:border-0">
                                    <span>{{ $item['description'] }}</span>
                                    <span class="sensitive-value tabular-nums font-semibold"><x-core::financial-value :value="$item['amount']" /></span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Minhas Contas + Transações e Metas - 3 colunas no desktop --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Minhas Contas --}}
        <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                    </div>
                    Minhas Contas
                </h3>
                @if(Route::has('core.accounts.create') && !($inspectionReadOnly ?? false))
                    <a href="{{ route('core.accounts.create') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline uppercase tracking-wider">Nova</a>
                @endif
            </div>
            <div class="p-4 space-y-2 max-h-64 overflow-y-auto">
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
                        @if(Route::has('core.accounts.create') && !($inspectionReadOnly ?? false))
                            <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar conta
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Transações Recentes --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
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
            <div class="divide-y divide-gray-100 dark:divide-white/5 max-h-80 overflow-y-auto">
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
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Nova transação
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Metas --}}
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
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
        <div class="p-6 space-y-6">
            @forelse($goals as $goal)
                @php $pct = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-bold text-gray-900 dark:text-white">{{ $goal->name }}</span>
                        <span class="font-black text-primary-500 tabular-nums">{{ number_format($pct, 0) }}%</span>
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

    {{-- Ações rápidas + CTA Upgrade (FREE) --}}
    <div class="flex flex-col sm:flex-row items-stretch gap-6">
        <div class="flex-1 flex flex-wrap items-center gap-3">
            @if(!($inspectionReadOnly ?? false))
                <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition-all shadow-lg shadow-primary-500/20">
                    <x-icon name="plus" style="solid" class="w-5 h-5" />
                    Nova Transação
                </a>
                @if(Route::has('core.transactions.transfer'))
                    <a href="{{ route('core.transactions.transfer') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                        <x-icon name="right-left" style="duotone" class="w-5 h-5 text-emerald-500" />
                        Transferir
                    </a>
                @endif
            @endif
            @if(Route::has('core.income.index') && !($inspectionReadOnly ?? false))
                <a href="{{ route('core.income.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                    <x-icon name="sack-dollar" style="duotone" class="w-5 h-5 text-emerald-500" />
                    Minha Renda
                </a>
            @endif
        </div>
        @if(!$isPro)
            <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-black text-sm shadow-xl shadow-amber-500/20 transition-all hover:scale-[1.02] active:scale-95 shrink-0">
                <x-icon name="crown" style="solid" class="w-5 h-5" />
                Assinar Vertex PRO
            </a>
        @endif
    </div>

    @if(!$isPro)
        {{-- CTA Upgrade card - CBAV style --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-500/10 dark:bg-amber-500/20 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-orange-500/10 dark:bg-orange-500/20 rounded-full blur-[100px]"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/20 dark:bg-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <x-icon name="crown" style="duotone" class="w-7 h-7" />
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 dark:text-white">Desbloqueie o Dashboard Completo</h4>
                            <p class="text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest">Vertex PRO</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 max-w-lg leading-relaxed">Gráficos avançados, contas ilimitadas, metas e orçamentos completos. Assine o Vertex PRO e tenha controle total das suas finanças.</p>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-amber-500/20 shrink-0">
                    <x-icon name="bolt" style="solid" class="w-5 h-5" />
                    Ver Planos e Assinar
                </a>
            </div>
        </div>
    @endif
</div>
</x-paneluser::layouts.master>
