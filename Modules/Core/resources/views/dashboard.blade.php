{{-- Dashboard PRO - UI/UX Flowbite + Tailwind v4 + Font Awesome Duotone --}}
@php
    $user = auth()->user();
    $userName = $user->full_name ?? $user->first_name ?? 'Membro PRO';
    $greeting = match (true) {
        now()->hour < 12 => 'Bom dia',
        now()->hour < 18 => 'Boa tarde',
        default => 'Boa noite',
    };
@endphp
<x-paneluser::layouts.master :title="'Dashboard'">
        <div class="space-y-8 pb-8">
        {{-- Hero Welcome - Glassmorphism + Animated --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/60 dark:border-slate-700/60 bg-gradient-to-br from-white via-primary-50/30 to-emerald-50/20 dark:from-slate-800/90 dark:via-primary-900/10 dark:to-emerald-900/10 backdrop-blur-sm shadow-lg opacity-0-start animate-fade-in-up"
             style="animation-delay: 0ms;">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-400/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary-300/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 p-6 md:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="relative shrink-0 group">
                            <div class="absolute -inset-1 bg-gradient-to-br from-primary-500 to-emerald-500 rounded-2xl opacity-20 group-hover:opacity-40 blur transition-opacity duration-300"></div>
                            <img src="{{ $user->photo_url }}" alt="{{ $userName }}"
                                 class="relative w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover ring-2 ring-white/80 dark:ring-slate-700/80 shadow-xl">
                            <span class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg ring-2 ring-white dark:ring-slate-800"
                                  title="Vertex PRO">
                                <x-icon name="crown" style="duotone" class="w-4 h-4 text-white" />
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Vertex PRO</p>
                            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ $greeting }}, {{ explode(' ', $userName)[0] }}!
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1 text-sm">
                                Seu painel financeiro. Controle total em um só lugar.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <a href="{{ route('core.transactions.create') }}"
                           class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white text-sm font-semibold shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                            <x-icon name="plus" style="duotone" class="w-4 h-4" />
                            Nova transação
                        </a>
                        <a href="{{ route('core.reports.index') }}"
                           class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-600 hover:border-primary-300 dark:hover:border-primary-600 text-slate-700 dark:text-slate-200 text-sm font-medium hover:shadow-md transition-all duration-200">
                            <x-icon name="file-export" style="duotone" class="w-4 h-4 text-primary-500" />
                            Exportar relatório
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards - Flowbite style com animação escalonada --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 xl:gap-6 dashboard-stats">
            <div class="dashboard-stats-card opacity-0-start animate-fade-in-up">
                <x-core::financial-card
                    title="Saldo Total"
                    :value="'R$ ' . number_format($totalBalance, 2, ',', '.')"
                    icon="wallet"
                    color="primary"
                />
            </div>
            <div class="dashboard-stats-card opacity-0-start animate-fade-in-up">
                <x-core::financial-card
                    title="Receitas do Mês"
                    :value="'R$ ' . number_format($monthlyIncome, 2, ',', '.')"
                    icon="arrow-trend-up"
                    color="success"
                    trend="up"
                    :trend-value="'+' . number_format($incomeTrendPercentage, 1) . '%'"
                />
            </div>
            <div class="dashboard-stats-card opacity-0-start animate-fade-in-up">
                <x-core::financial-card
                    title="Despesas do Mês"
                    :value="'R$ ' . number_format($monthlyExpenses, 2, ',', '.')"
                    icon="arrow-trend-down"
                    color="danger"
                    trend="down"
                    :trend-value="number_format($expenseTrendPercentage, 1) . '%'"
                />
            </div>
            <div class="dashboard-stats-card opacity-0-start animate-fade-in-up">
                <x-core::financial-card
                    title="Balanço Mensal"
                    :value="'R$ ' . number_format($monthlyBalance, 2, ',', '.')"
                    icon="chart-line"
                    :color="$monthlyBalance >= 0 ? 'success' : 'danger'"
                />
            </div>
        </div>

        {{-- Contas + Gráficos - Layout dinâmico --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Minhas Contas --}}
            <div class="opacity-0-start animate-fade-in-up" style="animation-delay: 200ms;">
                <div class="h-full rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 backdrop-blur-sm shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="p-5 border-b border-slate-200/80 dark:border-slate-700/80 flex justify-between items-center">
                        <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
                                <x-icon name="building-columns" style="duotone" class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                            </div>
                            Minhas Contas
                        </h3>
                        @can('create', \Modules\Core\Models\Account::class)
                            <a href="{{ route('core.accounts.create') }}"
                               class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova</a>
                        @endcan
                    </div>
                    <div class="p-4 space-y-2 max-h-72 overflow-y-auto">
                        @forelse($accounts as $account)
                            <a href="{{ route('core.accounts.show', $account) }}"
                               class="flex items-center justify-between p-4 rounded-xl bg-slate-50/80 dark:bg-slate-700/30 hover:bg-primary-50/50 dark:hover:bg-primary-900/20 border border-transparent hover:border-primary-200/50 dark:hover:border-primary-800/50 transition-all duration-200 group">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-300">{{ $account->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 capitalize">{{ $account->type }}</p>
                                </div>
                                <p class="font-bold text-primary-600 dark:text-primary-400 tabular-nums">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                            </a>
                        @empty
                            <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                                <x-icon name="wallet" style="duotone" class="w-14 h-14 mx-auto mb-3 opacity-40" />
                                <p class="text-sm font-medium">Nenhuma conta cadastrada</p>
                                <a href="{{ route('core.accounts.create') }}"
                                   class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                    <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar conta
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Gráficos --}}
            <div class="xl:col-span-2 space-y-6 opacity-0-start animate-fade-in-up" style="animation-delay: 280ms;">
                <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 backdrop-blur-sm shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
                            <x-icon name="chart-area" style="duotone" class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                        </div>
                        Fluxo de Caixa (6 meses)
                    </h3>
                    <div id="cashFlowChart" class="w-full" style="min-height: 280px;"></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 backdrop-blur-sm shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
                            <x-icon name="chart-pie" style="duotone" class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                        </div>
                        Gastos por Categoria
                    </h3>
                    <div id="categoryChart" class="w-full" style="min-height: 220px;"></div>
                </div>
            </div>
        </div>

        {{-- Transações Recentes - Tabela Flowbite --}}
        <div class="opacity-0-start animate-fade-in-up" style="animation-delay: 360ms;">
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 backdrop-blur-sm shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-5 border-b border-slate-200/80 dark:border-slate-700/80 flex justify-between items-center">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
                            <x-icon name="receipt" style="duotone" class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                        </div>
                        Últimas Transações
                    </h3>
                    <a href="{{ route('core.transactions.index') }}"
                       class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Ver todas</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                        <thead class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase bg-slate-50/80 dark:bg-slate-700/50 sticky top-0">
                            <tr>
                                <th scope="col" class="px-6 py-4">Transação</th>
                                <th scope="col" class="px-6 py-4">Data</th>
                                <th scope="col" class="px-6 py-4">Categoria</th>
                                <th scope="col" class="px-6 py-4 text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($recentTransactions as $i => $transaction)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors duration-150"
                                    style="animation: fade-in 0.3s ease-out {{ $i * 30 }}ms both;">
                                    <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                        <a href="{{ route('core.transactions.edit', $transaction) }}"
                                           class="hover:text-primary-600 dark:hover:text-primary-400 hover:underline">{{ $transaction->description }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $transaction->date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $transaction->category->name ?? 'Geral' }}</td>
                                    <td class="px-6 py-4 text-right font-semibold tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">
                                        <x-icon name="receipt" style="duotone" class="w-12 h-12 mx-auto mb-3 opacity-40" />
                                        <p class="font-medium">Nenhuma transação registrada</p>
                                        <a href="{{ route('core.transactions.create') }}"
                                           class="inline-flex items-center gap-2 mt-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">Registrar transação</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Metas e Orçamentos --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="opacity-0-start animate-fade-in-up" style="animation-delay: 400ms;">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
                            <x-icon name="bullseye" style="duotone" class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                        </div>
                        Metas
                    </h3>
                    @can('create', \Modules\Core\Models\Goal::class)
                        <a href="{{ route('core.goals.create') }}"
                           class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova Meta</a>
                    @endcan
                </div>
                <div class="space-y-4">
                    @forelse($goals as $goal)
                        <x-core::goal-card :goal="$goal" />
                    @empty
                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 p-10 text-center">
                            <x-icon name="bullseye" style="duotone" class="w-14 h-14 mx-auto mb-4 text-slate-400 opacity-40" />
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhuma meta cadastrada</p>
                            <a href="{{ route('core.goals.create') }}"
                               class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar meta
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="opacity-0-start animate-fade-in-up" style="animation-delay: 440ms;">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
                            <x-icon name="chart-pie" style="duotone" class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                        </div>
                        Orçamentos
                    </h3>
                    @can('create', \Modules\Core\Models\Budget::class)
                        <a href="{{ route('core.budgets.create') }}"
                           class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Novo Orçamento</a>
                    @endcan
                </div>
                <div class="space-y-4">
                    @forelse($budgets as $budget)
                        <x-core::budget-card :budget="$budget" />
                    @empty
                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 p-10 text-center">
                            <x-icon name="calculator" style="duotone" class="w-14 h-14 mx-auto mb-4 text-slate-400 opacity-40" />
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum orçamento cadastrado</p>
                            <a href="{{ route('core.budgets.create') }}"
                               class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar orçamento
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Dicas PRO - Cards interativos --}}
        <div class="opacity-0-start animate-fade-in-up" style="animation-delay: 500ms;">
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-gradient-to-br from-slate-50/80 to-primary-50/30 dark:from-slate-800/80 dark:to-primary-900/10 p-6 backdrop-blur-sm">
                <h4 class="font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/20 flex items-center justify-center">
                        <x-icon name="lightbulb" style="duotone" class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                    </div>
                    Dicas PRO
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('core.reports.index') }}"
                       class="group flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-600/80 hover:border-primary-300 dark:hover:border-primary-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <x-icon name="chart-simple" style="duotone" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 dark:text-white text-sm">Relatórios e exportações</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">PDF, CSV e análise completa</p>
                        </div>
                    </a>
                    <a href="{{ route('core.transactions.transfer') }}"
                       class="group flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-600/80 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <x-icon name="right-left" style="duotone" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 dark:text-white text-sm">Transferir entre contas</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Mova saldo entre suas contas</p>
                        </div>
                    </a>
                    <a href="{{ route('core.invoices.index') }}"
                       class="group flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-600/80 hover:border-amber-300 dark:hover:border-amber-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <x-icon name="file-invoice-dollar" style="duotone" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 dark:text-white text-sm">Minhas faturas</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Próxima cobrança e histórico</p>
                        </div>
                    </a>
                    <a href="{{ route('user.tickets.index') }}"
                       class="group flex items-start gap-4 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-600/80 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <x-icon name="headset" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 dark:text-white text-sm">Suporte prioritário</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Abertura de chamados</p>
                        </div>
                    </a>
                </div>
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
