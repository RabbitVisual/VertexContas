{{-- Dashboard PRO - Inspirado em Vertex CBAV: completo, profissional e estruturado --}}
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
    <div class="space-y-8 pb-8">
        {{-- Hero Section - CBAV style --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-primary-600/20 to-transparent"></div>

            <div class="relative p-6 md:p-10 lg:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-200 text-xs font-bold uppercase tracking-wider">
                            Vertex PRO
                        </span>
                        <span class="px-3 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-bold uppercase tracking-wider">
                            Painel Financeiro
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black tracking-tight mb-2">
                        {{ $greeting }}, {{ $firstName }}!
                    </h1>
                    <p class="text-slate-300 text-base md:text-lg max-w-xl mb-8">
                        Bem-vindo ao seu centro de controle financeiro. Aqui está o resumo das suas finanças.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('core.transactions.create') }}"
                           class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white text-slate-900 font-bold hover:bg-slate-100 transition-colors shadow-lg shadow-white/10">
                            <x-icon name="plus" style="solid" class="w-5 h-5 text-primary-600" />
                            Nova Transação
                        </a>
                        <a href="{{ route('core.transactions.transfer') }}"
                           class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold hover:bg-white/20 transition-colors">
                            <x-icon name="right-left" style="solid" class="w-5 h-5 text-emerald-400" />
                            Transferir
                        </a>
                        <a href="{{ route('core.reports.index') }}"
                           class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold hover:bg-white/20 transition-colors">
                            <x-icon name="file-export" style="solid" class="w-5 h-5 text-amber-400" />
                            Relatórios
                        </a>
                    </div>
                </div>

                <div class="hidden md:block relative shrink-0">
                    <div class="w-28 h-28 lg:w-32 lg:h-32 rounded-2xl bg-gradient-to-br from-amber-500 to-primary-500 p-1 shadow-2xl shadow-amber-500/20 ring-2 ring-white/20">
                        @if($hasPhoto)
                            <img src="{{ $photoUrl }}" alt="{{ $userName }}"
                                 class="w-full h-full rounded-xl object-cover border-2 border-slate-800">
                        @else
                            <div class="w-full h-full rounded-xl bg-slate-800 flex items-center justify-center border-2 border-slate-700">
                                <span class="text-4xl font-black text-amber-400">{{ strtoupper(mb_substr($firstName, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shadow-lg ring-2 ring-slate-900" title="Vertex PRO">
                        <x-icon name="crown" style="solid" class="w-5 h-5 text-white" />
                    </span>
                </div>
            </div>
        </div>

        {{-- Stats Grid - CBAV style com corner shapes --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group transition-all hover:shadow-lg">
                <div class="absolute right-0 top-0 w-32 h-32 bg-primary-50 dark:bg-primary-900/20 rounded-bl-full -mr-8 -mt-8 group-hover:scale-110 transition-transform duration-300"></div>
                <div class="relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                        </div>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">Saldo Total</p>
                    <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-slate-900 dark:text-white mt-1 tabular-nums">R$ {{ number_format($totalBalance, 2, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group transition-all hover:shadow-lg">
                <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 dark:bg-emerald-900/20 rounded-bl-full -mr-8 -mt-8 group-hover:scale-110 transition-transform duration-300"></div>
                <div class="relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <x-icon name="arrow-trend-up" style="duotone" class="w-6 h-6" />
                        </div>
                        <span class="flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded-lg">
                            +{{ number_format($incomeTrendPercentage, 1) }}%
                        </span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">Receitas do Mês</p>
                    <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-slate-900 dark:text-white mt-1 tabular-nums">R$ {{ number_format($monthlyIncome, 2, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group transition-all hover:shadow-lg">
                <div class="absolute right-0 top-0 w-32 h-32 bg-rose-50 dark:bg-rose-900/20 rounded-bl-full -mr-8 -mt-8 group-hover:scale-110 transition-transform duration-300"></div>
                <div class="relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                            <x-icon name="arrow-trend-down" style="duotone" class="w-6 h-6" />
                        </div>
                        <span class="flex items-center text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/30 px-2.5 py-1 rounded-lg">
                            {{ number_format($expenseTrendPercentage, 1) }}%
                        </span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">Despesas do Mês</p>
                    <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-slate-900 dark:text-white mt-1 tabular-nums">R$ {{ number_format($monthlyExpenses, 2, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group transition-all hover:shadow-lg">
                <div class="absolute right-0 top-0 w-32 h-32 {{ $monthlyBalance >= 0 ? 'bg-emerald-50 dark:bg-emerald-900/20' : 'bg-rose-50 dark:bg-rose-900/20' }} rounded-bl-full -mr-8 -mt-8 group-hover:scale-110 transition-transform duration-300"></div>
                <div class="relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl {{ $monthlyBalance >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-rose-100 dark:bg-rose-900/30' }} flex items-center justify-center {{ $monthlyBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            <x-icon name="chart-line" style="duotone" class="w-6 h-6" />
                        </div>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">Balanço Mensal</p>
                    <h3 class="sensitive-value text-2xl lg:text-3xl font-black mt-1 tabular-nums {{ $monthlyBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">R$ {{ number_format($monthlyBalance, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Charts Section - Visão Geral --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Minhas Contas --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                            </div>
                            Minhas Contas
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $accounts->count() }} conta(s) cadastrada(s)</p>
                    </div>
                    @can('create', \Modules\Core\Models\Account::class)
                        <a href="{{ route('core.accounts.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova</a>
                    @endcan
                </div>
                <div class="p-4 lg:p-6 space-y-2 max-h-80 overflow-y-auto">
                    @forelse($accounts as $account)
                        <a href="{{ route('core.accounts.show', $account) }}"
                           class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 hover:bg-primary-50 dark:hover:bg-primary-900/20 border border-transparent hover:border-primary-200 dark:hover:border-primary-800 transition-all group">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-300">{{ $account->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 capitalize">{{ $account->type }}</p>
                            </div>
                            <p class="sensitive-value font-bold text-primary-600 dark:text-primary-400 tabular-nums">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                        </a>
                    @empty
                        <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                            <x-icon name="wallet" style="duotone" class="w-14 h-14 mx-auto mb-3 opacity-40" />
                            <p class="text-sm font-medium">Nenhuma conta cadastrada</p>
                            <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar conta
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Gráficos --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                    <x-icon name="chart-area" style="duotone" class="w-5 h-5" />
                                </div>
                                Fluxo de Caixa
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Últimos 6 meses</p>
                        </div>
                        <a href="{{ route('core.reports.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors" title="Ver relatórios">
                            <x-icon name="ellipsis" style="solid" class="w-5 h-5 text-slate-400" />
                        </a>
                    </div>
                    <div id="cashFlowChart" class="sensitive-value w-full" style="min-height: 280px;"></div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                            </div>
                            Gastos por Categoria
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Distribuição das despesas</p>
                    </div>
                    <div id="categoryChart" class="sensitive-value w-full" style="min-height: 220px;"></div>
                </div>
            </div>
        </div>

        {{-- Atividade Recente - 2 cols estilo CBAV --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Transações Recentes - List style --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <x-icon name="receipt" style="duotone" class="w-5 h-5" />
                        </div>
                        Transações Recentes
                    </h3>
                    <a href="{{ route('core.transactions.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Ver todas</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700 max-h-96 overflow-y-auto">
                    @forelse($recentTransactions as $transaction)
                        <a href="{{ route('core.transactions.edit', $transaction) }}" class="flex items-center justify-between p-4 lg:px-8 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl {{ $transaction->type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' }} flex items-center justify-center shrink-0">
                                    <x-icon name="{{ $transaction->type === 'income' ? 'arrow-up' : 'arrow-down' }}" style="solid" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $transaction->description }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $transaction->date->format('d/m/Y') }} · {{ $transaction->category->name ?? 'Geral' }}</p>
                                </div>
                            </div>
                            <span class="sensitive-value font-mono font-bold tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </a>
                    @empty
                        <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                            <x-icon name="receipt" style="duotone" class="w-12 h-12 mx-auto mb-3 opacity-40" />
                            <p class="font-medium">Nenhuma transação registrada</p>
                            <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 mt-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">Registrar transação</a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Metas em Destaque --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <x-icon name="bullseye" style="duotone" class="w-5 h-5" />
                        </div>
                        Metas em Destaque
                    </h3>
                    @can('create', \Modules\Core\Models\Goal::class)
                        <a href="{{ route('core.goals.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova Meta</a>
                    @endcan
                </div>
                <div class="p-4 lg:p-6 space-y-4 max-h-96 overflow-y-auto">
                    @forelse($goals as $goal)
                        <x-core::goal-card :goal="$goal" />
                    @empty
                        <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                            <x-icon name="bullseye" style="duotone" class="w-14 h-14 mx-auto mb-4 opacity-40" />
                            <p class="font-medium">Nenhuma meta cadastrada</p>
                            <a href="{{ route('core.goals.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar meta
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Orçamentos --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-6 lg:p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                    </div>
                    Orçamentos
                </h3>
                @can('create', \Modules\Core\Models\Budget::class)
                    <a href="{{ route('core.budgets.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Novo Orçamento</a>
                @endcan
            </div>
            <div class="p-4 lg:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($budgets as $budget)
                        <x-core::budget-card :budget="$budget" />
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-600">
                            <x-icon name="calculator" style="duotone" class="w-14 h-14 mx-auto mb-4 opacity-40" />
                            <p class="font-medium">Nenhum orçamento cadastrado</p>
                            <a href="{{ route('core.budgets.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar orçamento
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Dicas PRO / Ações Rápidas --}}
        <div class="rounded-3xl border border-slate-200/80 dark:border-slate-700/80 bg-gradient-to-br from-slate-50 to-primary-50/30 dark:from-slate-800/80 dark:to-primary-900/10 p-6 lg:p-8">
            <h4 class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <x-icon name="lightbulb" style="duotone" class="w-5 h-5" />
                </div>
                Dicas PRO
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('core.reports.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-600 hover:border-primary-300 dark:hover:border-primary-600 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="chart-simple" style="duotone" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-white text-sm">Relatórios e exportações</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">PDF, CSV e análise completa</p>
                    </div>
                </a>
                <a href="{{ route('core.transactions.transfer') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-600 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="right-left" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-white text-sm">Transferir entre contas</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Mova saldo entre suas contas</p>
                    </div>
                </a>
                <a href="{{ route('core.invoices.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-600 hover:border-amber-300 dark:hover:border-amber-600 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="file-invoice-dollar" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-white text-sm">Minhas faturas</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Próxima cobrança e histórico</p>
                    </div>
                </a>
                <a href="{{ route('user.tickets.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="headset" style="duotone" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-white text-sm">Suporte prioritário</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Abertura de chamados</p>
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
