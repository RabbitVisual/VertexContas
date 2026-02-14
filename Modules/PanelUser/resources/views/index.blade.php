@php
    $isPro = auth()->user()?->isPro() ?? false;
    $balance = $totalBalance ?? 0;
    $totalIncome = $monthlyIncome ?? 0;
    $totalExpense = $monthlyExpense ?? 0;
    $monthlyCapacity = $monthlyCapacity ?? 0;
    $incomeBreakdown = $incomeBreakdown ?? collect();
    $greeting = match (true) {
        now()->hour < 12 => 'Bom dia',
        now()->hour < 18 => 'Boa tarde',
        default => 'Boa noite',
    };
    $firstName = auth()->user()->first_name ?? 'Membro';
@endphp
<x-paneluser::layouts.master :title="'Dashboard'">
    <div class="space-y-8 pb-12">
        {{-- Hero Section - Vertex CBAV style (compacto) --}}
        <div class="relative overflow-hidden rounded-[3rem] bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900/80 text-white shadow-2xl border border-slate-800">
            <div class="absolute inset-0 opacity-40 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-80 h-80 bg-indigo-600 rounded-full blur-[100px]"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-emerald-600 rounded-full blur-[80px] opacity-50"></div>
            </div>
            <div class="relative px-6 py-10 md:px-10 md:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex-1 text-center md:text-left">
                    <div class="inline-flex items-center px-3 py-1.5 bg-white/10 border border-white/20 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="chart-mixed" style="duotone" class="w-4 h-4 text-indigo-300 mr-2" />
                        <span class="text-indigo-200 text-[10px] font-black uppercase tracking-[0.2em]">Visão Geral</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">
                        {{ $greeting }}, {{ $firstName }}!
                    </h1>
                    <p class="text-slate-400 font-medium mt-2 max-w-md">
                        Aqui está o resumo das suas finanças e sua capacidade mensal.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('core.transactions.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 bg-white text-slate-900 font-bold rounded-2xl hover:bg-slate-100 transition-all shadow-lg">
                        <x-icon name="plus" style="solid" class="w-5 h-5 text-primary-600" />
                        Nova Transação
                    </a>
                    @if(Route::has('core.income.index'))
                    <a href="{{ route('core.income.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 bg-white/10 border border-white/20 text-white font-bold rounded-2xl hover:bg-white/20 transition-all">
                        <x-icon name="sack-dollar" style="solid" class="w-5 h-5 text-emerald-400" />
                        Minha Renda
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats Grid - Vertex CBAV style --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Saldo Atual --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden hover:-translate-y-1 transition-all">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary-500/5 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-2xl bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 ring-1 ring-primary-500/20 mb-4">
                        <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Saldo Atual</p>
                    <h3 class="sensitive-value text-2xl font-black text-slate-900 dark:text-white mt-1 tabular-nums">R$ {{ number_format($balance, 2, ',', '.') }}</h3>
                    <div class="flex items-center gap-2 mt-2 px-3 py-1 bg-slate-50 dark:bg-slate-800/50 rounded-full border border-slate-100 dark:border-slate-700/50 w-max">
                        <x-icon name="arrow-trend-up" style="duotone" class="w-3 h-3 text-primary-500" />
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Total Disponível</span>
                    </div>
                </div>
            </div>
            {{-- Receitas (mês) --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden hover:-translate-y-1 transition-all">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/20 mb-4">
                        <x-icon name="money-bill-trend-up" style="duotone" class="w-6 h-6" />
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Receitas (mês)</p>
                    <h3 class="sensitive-value text-2xl font-black text-slate-900 dark:text-white mt-1 tabular-nums">R$ {{ number_format($totalIncome, 2, ',', '.') }}</h3>
                    <div class="flex items-center gap-2 mt-2 px-3 py-1 bg-slate-50 dark:bg-slate-800/50 rounded-full border border-slate-100 dark:border-slate-700/50 w-max">
                        <x-icon name="arrow-trend-up" style="duotone" class="w-3 h-3 text-emerald-500" />
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Recebido este mês</span>
                    </div>
                </div>
            </div>
            {{-- Despesas (mês) --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden hover:-translate-y-1 transition-all">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-500/5 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400 ring-1 ring-rose-500/20 mb-4">
                        <x-icon name="credit-card" style="duotone" class="w-6 h-6" />
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Despesas (mês)</p>
                    <h3 class="sensitive-value text-2xl font-black text-slate-900 dark:text-white mt-1 tabular-nums">R$ {{ number_format($totalExpense, 2, ',', '.') }}</h3>
                    <div class="flex items-center gap-2 mt-2 px-3 py-1 bg-slate-50 dark:bg-slate-800/50 rounded-full border border-slate-100 dark:border-slate-700/50 w-max">
                        <x-icon name="arrow-trend-down" style="duotone" class="w-3 h-3 text-rose-500" />
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Gasto este mês</span>
                    </div>
                </div>
            </div>
            {{-- Capacidade Mensal (receitas recorrentes) --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden hover:-translate-y-1 transition-all">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/20 mb-4">
                        <x-icon name="chart-line" style="duotone" class="w-6 h-6" />
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Capacidade Mensal</p>
                    <h3 class="sensitive-value text-2xl font-black text-slate-900 dark:text-white mt-1 tabular-nums">R$ {{ number_format($monthlyCapacity, 2, ',', '.') }}</h3>
                    <div class="flex items-center gap-2 mt-2 px-3 py-1 bg-slate-50 dark:bg-slate-800/50 rounded-full border border-slate-100 dark:border-slate-700/50 w-max">
                        <x-icon name="arrow-trend-up" style="duotone" class="w-3 h-3 text-indigo-500" />
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Receitas recorrentes</span>
                    </div>
                    @if($monthlyCapacity > 0 && $incomeBreakdown->count() > 0)
                        <div class="mt-3" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium flex items-center gap-1">
                                Ver fontes
                                <span class="inline-block transition-transform" :class="open ? 'rotate-180' : ''"><x-icon name="chevron-down" style="solid" class="w-3 h-3" /></span>
                            </button>
                            <ul x-show="open" x-collapse class="mt-2 space-y-1.5 text-xs text-slate-600 dark:text-slate-400">
                                @foreach($incomeBreakdown as $item)
                                    <li class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800 last:border-0">
                                        <span>{{ $item['description'] }}</span>
                                        <span class="sensitive-value tabular-nums font-semibold">R$ {{ number_format($item['amount'], 2, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Transações e Metas - 2 colunas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Transações Recentes --}}
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-5 md:px-8 border-b border-slate-50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-800/20 flex justify-between items-center">
                    <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center text-primary-500">
                            <x-icon name="clock-rotate-left" style="duotone" class="w-5 h-5" />
                        </div>
                        Transações Recentes
                    </h3>
                    <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-500/10 text-primary-600 dark:text-primary-400 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all">
                        Ver todas
                        <x-icon name="arrow-right" style="duotone" class="w-3.5 h-3.5 ml-2" />
                    </a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($recentTransactions as $transaction)
                        <div class="px-6 py-5 md:px-8 flex justify-between items-center hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors">{{ $transaction->description }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $transaction->category->name ?? 'Geral' }} · {{ $transaction->date->format('d/m/Y') }}</p>
                            </div>
                            <span class="sensitive-value font-black text-lg tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 px-8 text-center">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <x-icon name="receipt" style="duotone" class="w-8 h-8 text-slate-300" />
                            </div>
                            <p class="font-bold text-slate-500 dark:text-slate-400">Nenhuma transação recente</p>
                            <a href="{{ route('core.transactions.create') }}" class="text-primary-600 dark:text-primary-400 hover:underline text-sm font-medium mt-2">Registrar primeira transação</a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Metas --}}
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-5 md:px-8 border-b border-slate-50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-800/20 flex justify-between items-center">
                    <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                            <x-icon name="bullseye" style="duotone" class="w-5 h-5" />
                        </div>
                        Metas
                    </h3>
                    <a href="{{ route('core.goals.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all">
                        Ver todas
                        <x-icon name="arrow-right" style="duotone" class="w-3.5 h-3.5 ml-2" />
                    </a>
                </div>
                <div class="p-6 md:p-8 space-y-6">
                    @forelse($goals as $goal)
                        @php $pct = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $goal->name }}</span>
                                <span class="font-black text-primary-500">{{ number_format($pct, 0) }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden border border-slate-200/50 dark:border-slate-600/50">
                                <div class="bg-gradient-to-r from-primary-500 to-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <x-icon name="bullseye" style="duotone" class="w-8 h-8 text-slate-300" />
                            </div>
                            <p class="font-bold text-slate-500 dark:text-slate-400">Nenhuma meta cadastrada</p>
                            <a href="{{ route('core.goals.create') }}" class="text-primary-600 dark:text-primary-400 hover:underline text-sm font-medium mt-2 inline-block">Criar primeira meta</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- CTA Upgrade (FREE users) --}}
        @if(!$isPro)
            <div class="relative overflow-hidden rounded-[3rem] bg-gradient-to-br from-primary-600 via-indigo-600 to-slate-900 shadow-2xl border border-slate-800">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
                <div class="relative px-8 py-12 md:px-12 md:py-14 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm mb-6">
                        <x-icon name="crown" style="solid" class="w-8 h-8 text-white" />
                    </div>
                    <h4 class="font-black text-white text-2xl mb-2">Desbloqueie o Dashboard Completo</h4>
                    <p class="text-indigo-200 text-sm mb-8 max-w-lg mx-auto leading-relaxed">Gráficos avançados, contas ilimitadas, metas e orçamentos completos. Assine o Vertex PRO e tenha controle total das suas finanças.</p>
                    <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-primary-700 font-black rounded-2xl shadow-xl hover:shadow-2xl hover:bg-slate-50 transition-all hover:-translate-y-1">
                        <x-icon name="bolt" style="solid" class="w-5 h-5" />
                        Ver Planos e Assinar
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-paneluser::layouts.master>
