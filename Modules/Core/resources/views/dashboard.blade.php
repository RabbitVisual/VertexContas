{{-- Dashboard PRO - Vertex CBAV: completo, profissional, ícones Font Awesome locais --}}
@php
    $user = auth()->user();
    $userName = $user->full_name ?? $user->first_name ?? 'Membro PRO';
    $firstName = explode(' ', $userName)[0] ?? $userName;
    $greeting = match (true) {
        now()->hour < 12 => 'Bom dia',
        now()->hour < 18 => 'Boa tarde',
        default => 'Boa noite',
    };
    $photoUrl = $user->photo_url ?? null;
    $hasPhoto = !empty($user->photo);
@endphp
<x-paneluser::layouts.master :title="'Dashboard'">
    <div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 px-4 pb-12">
        {{-- Hero - Vertex CBAV style (card claro, blur orbs, breadcrumb) --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-500/5 dark:bg-amber-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1">
                    <nav class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                        <span>Painel</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
                        <span class="text-gray-400 dark:text-gray-500">Dashboard</span>
                    </nav>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider border border-amber-200 dark:border-amber-500/30">Vertex PRO</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">
                        {{ $greeting }}, {{ $firstName }}!<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-emerald-600 dark:from-amber-400 dark:to-emerald-400">Painel Financeiro</span>
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed mb-6">
                        Bem-vindo ao seu centro de controle financeiro. Aqui está o resumo das suas finanças.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-lg shadow-amber-500/20 transition-all">
                                <x-icon name="plus" style="solid" class="w-5 h-5" />
                                Nova Transação
                            </a>
                            <a href="{{ route('core.transactions.transfer') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                                <x-icon name="right-left" style="duotone" class="w-5 h-5 text-emerald-500" />
                                Transferir
                            </a>
                        @endif
                        <a href="{{ route('core.reports.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                            <x-icon name="chart-simple" style="duotone" class="w-5 h-5 text-amber-500" />
                            Relatórios
                        </a>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0">
                    <div class="flex items-center gap-4 text-left">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                            <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">Saldo Total</p>
                            <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white leading-tight"><x-core::financial-value :value="$totalBalance" /></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid - CBAV style (rounded-3xl, border, ícones duotone) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-primary-500/30 shadow-sm hover:shadow-xl transition-all duration-500 p-6">
                <div class="w-12 h-12 rounded-2xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400 ring-1 ring-black/5 dark:ring-white/10 mb-4 shrink-0">
                    <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Saldo Total</p>
                <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$totalBalance" /></h3>
            </div>

            <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 shadow-sm hover:shadow-xl transition-all duration-500 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 ring-1 ring-black/5 dark:ring-white/10 shrink-0">
                        <x-icon name="arrow-trend-up" style="duotone" class="w-6 h-6" />
                    </div>
                    <span class="flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded-lg">+{{ number_format($incomeTrendPercentage, 1) }}%</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Receitas do Mês</p>
                <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$monthlyIncome" /></h3>
                @if(isset($monthlyCapacity) && $monthlyCapacity > 0)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Capacidade mensal (recorrente): <span class="sensitive-value font-semibold text-gray-700 dark:text-gray-300"><x-core::financial-value :value="$monthlyCapacity" /></span></p>
                @endif
                @if($user->isPro() && isset($incomeBreakdown) && $incomeBreakdown->count() > 1)
                    <div class="mt-2" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-medium flex items-center gap-1">
                            Ver detalhe por fonte
                            <span class="inline-block transition-transform" :class="open ? 'rotate-180' : ''"><x-icon name="chevron-down" style="solid" class="w-3 h-3" /></span>
                        </button>
                        <ul x-show="open" x-collapse class="mt-1.5 space-y-1 text-xs text-gray-600 dark:text-gray-400">
                            @foreach($incomeBreakdown as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item['description'] }}</span>
                                    <span class="sensitive-value tabular-nums"><x-core::financial-value :value="$item['amount']" /></span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-rose-500/30 shadow-sm hover:shadow-xl transition-all duration-500 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 ring-1 ring-black/5 dark:ring-white/10 shrink-0">
                        <x-icon name="arrow-trend-down" style="duotone" class="w-6 h-6" />
                    </div>
                    <span class="flex items-center text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/30 px-2.5 py-1 rounded-lg">{{ number_format($expenseTrendPercentage, 1) }}%</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Despesas do Mês</p>
                <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$monthlyExpenses" /></h3>
            </div>

            <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 shadow-sm hover:shadow-xl transition-all duration-500 p-6">
                <div class="w-12 h-12 rounded-2xl {{ $monthlyBalance >= 0 ? 'bg-emerald-500/10 dark:bg-emerald-500/20' : 'bg-rose-500/10 dark:bg-rose-500/20' }} flex items-center justify-center {{ $monthlyBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} ring-1 ring-black/5 dark:ring-white/10 mb-4 shrink-0">
                    <x-icon name="chart-line" style="duotone" class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Balanço Mensal</p>
                <h3 class="sensitive-value text-2xl lg:text-3xl font-black mt-1 tabular-nums {{ $monthlyBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}"><x-core::financial-value :value="$monthlyBalance" /></h3>
            </div>
        </div>

        {{-- Charts Section - Visão Geral (CBAV cards) --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Minhas Contas --}}
            <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                            </div>
                            Minhas Contas
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $accounts->count() }} conta(s) cadastrada(s)</p>
                    </div>
                    @can('create', \Modules\Core\Models\Account::class)
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.accounts.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova</a>
                        @endif
                    @endcan
                </div>
                <div class="p-4 lg:p-6 space-y-2 max-h-80 overflow-y-auto">
                    @forelse($accounts as $account)
                        <a href="{{ route('core.accounts.show', $account) }}"
                           class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-primary-50 dark:hover:bg-primary-500/10 border border-transparent hover:border-primary-200 dark:hover:border-primary-500/20 transition-all group">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-300">{{ $account->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $account->type }}</p>
                            </div>
                            <p class="sensitive-value font-bold text-primary-600 dark:text-primary-400 tabular-nums"><x-core::financial-value :value="$account->balance" /></p>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="building-columns" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma conta cadastrada</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                    <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar conta
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Gráficos --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900/50 rounded-3xl p-6 lg:p-8 border border-gray-200 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                    <x-icon name="chart-area" style="duotone" class="w-5 h-5" />
                                </div>
                                Fluxo de Caixa
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Últimos 6 meses</p>
                        </div>
                        <a href="{{ route('core.reports.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg transition-colors" title="Ver relatórios">
                            <x-icon name="ellipsis" style="solid" class="w-5 h-5 text-gray-400" />
                        </a>
                    </div>
                    <div id="cashFlowChart" class="sensitive-value w-full {{ \Modules\Core\Services\InspectionGuard::maskClasses() }}" style="min-height: 280px;" @if(\Modules\Core\Services\InspectionGuard::shouldHideFinancialData()) title="Oculto por privacidade durante a inspeção" @endif></div>
                </div>
                <div class="bg-white dark:bg-gray-900/50 rounded-3xl p-6 lg:p-8 border border-gray-200 dark:border-white/5 shadow-sm">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                            </div>
                            Gastos por Categoria
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Distribuição das despesas</p>
                    </div>
                    <div id="categoryChart" class="sensitive-value w-full {{ \Modules\Core\Services\InspectionGuard::maskClasses() }}" style="min-height: 220px;" @if(\Modules\Core\Services\InspectionGuard::shouldHideFinancialData()) title="Oculto por privacidade durante a inspeção" @endif></div>
                </div>
            </div>
        </div>

        {{-- Atividade Recente - 2 cols estilo CBAV --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Transações Recentes --}}
            <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <x-icon name="receipt" style="duotone" class="w-5 h-5" />
                        </div>
                        Transações Recentes
                    </h3>
                    <a href="{{ route('core.transactions.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Ver todas</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-white/5 max-h-96 overflow-y-auto">
                    @forelse($recentTransactions as $transaction)
                        <a href="{{ ($inspectionReadOnly ?? false) ? route('core.transactions.index') : route('core.transactions.edit', $transaction) }}" class="flex items-center justify-between p-4 lg:px-8 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl {{ $transaction->type === 'income' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }} flex items-center justify-center shrink-0">
                                    <x-icon name="{{ $transaction->type === 'income' ? 'arrow-up' : 'arrow-down' }}" style="solid" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $transaction->description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date->format('d/m/Y') }} · {{ $transaction->category->name ?? 'Geral' }}</p>
                                </div>
                            </div>
                            <span class="sensitive-value font-mono font-bold tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} <x-core::financial-value :value="$transaction->amount" />
                            </span>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="receipt" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma transação registrada</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 mt-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">Registrar transação</a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Metas em Destaque --}}
            <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <x-icon name="bullseye" style="duotone" class="w-5 h-5" />
                        </div>
                        Metas em Destaque
                    </h3>
                    @can('create', \Modules\Core\Models\Goal::class)
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.goals.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova Meta</a>
                        @endif
                    @endcan
                </div>
                <div class="p-4 lg:p-6 space-y-4 max-h-96 overflow-y-auto">
                    @forelse($goals as $goal)
                        <x-core::goal-card :goal="$goal" />
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="bullseye" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma meta cadastrada</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.goals.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                    <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar meta
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Orçamentos --}}
        <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                    </div>
                    Orçamentos
                </h3>
                @can('create', \Modules\Core\Models\Budget::class)
                    @if(!($inspectionReadOnly ?? false))
                        <a href="{{ route('core.budgets.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Novo Orçamento</a>
                    @endif
                @endcan
            </div>
            <div class="p-4 lg:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($budgets as $budget)
                        <x-core::budget-card :budget="$budget" />
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="chart-pie" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhum orçamento cadastrado</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.budgets.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                    <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar orçamento
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Dicas PRO / Ações Rápidas - CBAV style --}}
        <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 p-6 lg:p-8">
            <h4 class="font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <x-icon name="lightbulb" style="duotone" class="w-5 h-5" />
                </div>
                Dicas PRO
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('core.reports.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-primary-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="chart-simple" style="duotone" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">Relatórios e exportações</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">PDF, CSV e análise completa</p>
                    </div>
                </a>
                @if(!($inspectionReadOnly ?? false))
                    <a href="{{ route('core.transactions.transfer') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <x-icon name="right-left" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Transferir entre contas</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mova saldo entre suas contas</p>
                        </div>
                    </a>
                @endif
                <a href="{{ route('core.invoices.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-amber-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="file-invoice-dollar" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">Minhas faturas</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Próxima cobrança e histórico</p>
                    </div>
                </a>
                <a href="{{ route('user.tickets.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-blue-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="headset" style="duotone" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">Suporte prioritário</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Abertura de chamados</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            var cashFlowData = {!! json_encode($cashFlowData ?? ['income' => [0, 0, 0, 0, 0, 0], 'expenses' => [0, 0, 0, 0, 0, 0], 'months' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun']]) !!};
            var categoryData = {!! json_encode($categoryData ?? ['labels' => ['Sem dados'], 'values' => [0]]) !!};

            document.addEventListener('DOMContentLoaded', function() {
                if (typeof ApexCharts === 'undefined') return;

                var isDark = document.documentElement.classList.contains('dark');
                var textColor = isDark ? '#94a3b8' : '#64748b';

                if (document.querySelector('#cashFlowChart')) {
                    new ApexCharts(document.querySelector('#cashFlowChart'), {
                        series: [
                            { name: 'Receitas', data: cashFlowData.income || [0,0,0,0,0,0] },
                            { name: 'Despesas', data: cashFlowData.expenses || [0,0,0,0,0,0] }
                        ],
                        chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#ef4444'],
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 2 },
                        fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.1 } },
                        xaxis: { categories: cashFlowData.months || [], labels: { style: { colors: textColor, fontSize: '11px' } } },
                        yaxis: { labels: { formatter: function(v) { return 'R$ ' + v.toLocaleString('pt-BR'); }, style: { colors: textColor } } },
                        legend: { position: 'top', horizontalAlign: 'right', labels: { colors: textColor } },
                        tooltip: { y: { formatter: function(v) { return 'R$ ' + v.toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } } }
                    }).render();
                }

                if (document.querySelector('#categoryChart')) {
                    new ApexCharts(document.querySelector('#categoryChart'), {
                        series: categoryData.values || [0],
                        chart: { type: 'donut', height: 220, fontFamily: 'Inter, sans-serif' },
                        labels: categoryData.labels || ['Sem dados'],
                        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'],
                        dataLabels: { enabled: true, formatter: function(v) { return v.toFixed(1) + '%'; } },
                        legend: { position: 'bottom', labels: { colors: textColor } },
                        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, name: { fontSize: '12px' }, value: { formatter: function(v) { return 'R$ ' + parseFloat(v).toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } }, total: { show: true, label: 'Total', formatter: function(w) { return 'R$ ' + w.globals.seriesTotals.reduce(function(a,b){return a+b},0).toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } } } } } },
                        tooltip: { y: { formatter: function(v) { return 'R$ ' + v.toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } } }
                    }).render();
                }
            });
        })();
    </script>
    @endpush
</x-paneluser::layouts.master>
