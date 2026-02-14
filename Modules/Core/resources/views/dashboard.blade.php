{{-- Dashboard Financeiro Pro - Flowbite Admin Dashboard style - Vertex Pro only --}}
<x-paneluser::layouts.master :title="'Dashboard Financeiro Pro'">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Financeiro Pro</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Visão completa das suas finanças</p>
    </div>

    {{-- 4 Statistics Cards - Flowbite Admin style --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-core::financial-card
            title="Saldo Total"
            :value="'R$ ' . number_format($totalBalance, 2, ',', '.')"
            icon="wallet"
            color="primary"
        />
        <x-core::financial-card
            title="Receitas do Mês"
            :value="'R$ ' . number_format($monthlyIncome, 2, ',', '.')"
            icon="arrow-trend-up"
            color="success"
            trend="up"
            :trend-value="'+' . number_format($incomeTrendPercentage, 1) . '%'"
        />
        <x-core::financial-card
            title="Despesas do Mês"
            :value="'R$ ' . number_format($monthlyExpenses, 2, ',', '.')"
            icon="arrow-trend-down"
            color="danger"
            trend="down"
            :trend-value="number_format($expenseTrendPercentage, 1) . '%'"
        />
        <x-core::financial-card
            title="Balanço Mensal"
            :value="'R$ ' . number_format($monthlyBalance, 2, ',', '.')"
            icon="chart-line"
            :color="$monthlyBalance >= 0 ? 'success' : 'danger'"
        />
    </div>

    {{-- Accounts + Charts row --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        {{-- Accounts cards --}}
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-icon name="building-columns" style="duotone" class="w-5 h-5 text-primary-500" />
                        Minhas Contas
                    </h3>
                    @can('create', \Modules\Core\Models\Account::class)
                        <a href="{{ route('core.accounts.create') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">Nova</a>
                    @endcan
                </div>
                <div class="p-4 space-y-3 max-h-64 overflow-y-auto">
                    @forelse($accounts as $account)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $account->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $account->type }}</p>
                            </div>
                            <p class="font-bold text-primary-600 dark:text-primary-400">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                            <x-icon name="wallet" style="duotone" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                            <p class="text-sm">Nenhuma conta cadastrada</p>
                            <a href="{{ route('core.accounts.create') }}" class="text-primary-600 dark:text-primary-400 text-sm font-medium mt-2 inline-block">Criar conta</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Charts - 2 cols --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-icon name="chart-area" style="duotone" class="w-5 h-5 text-primary-500" />
                    Fluxo de Caixa (6 meses)
                </h3>
                <div id="cashFlowChart" class="w-full" style="min-height: 280px;"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-icon name="chart-pie" style="duotone" class="w-5 h-5 text-primary-500" />
                    Gastos por Categoria
                </h3>
                <div id="categoryChart" class="w-full" style="min-height: 220px;"></div>
            </div>
        </div>
    </div>

    {{-- Transactions table - Flowbite Admin style --}}
    <div class="mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="receipt" style="duotone" class="w-5 h-5 text-primary-500" />
                    Últimas Transações
                </h3>
                <a href="{{ route('core.transactions.index') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">Ver todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Transação</th>
                            <th scope="col" class="px-6 py-3">Data</th>
                            <th scope="col" class="px-6 py-3">Categoria</th>
                            <th scope="col" class="px-6 py-3">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('core.transactions.edit', $transaction) }}" class="hover:underline">{{ $transaction->description }}</a>
                                </td>
                                <td class="px-6 py-4">{{ $transaction->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ $transaction->category->name ?? 'Geral' }}</td>
                                <td class="px-6 py-4 font-semibold {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Nenhuma transação registrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Goals and Budgets - 2 cols --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="bullseye" style="duotone" class="w-5 h-5 text-primary-500" />
                    Metas
                </h3>
                @can('create', \Modules\Core\Models\Goal::class)
                    <a href="{{ route('core.goals.create') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">Nova Meta</a>
                @endcan
            </div>
            <div class="space-y-4">
                @forelse($goals as $goal)
                    <x-core::goal-card :goal="$goal" />
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
                        <x-icon name="bullseye" style="duotone" class="w-12 h-12 mx-auto mb-3 text-gray-400 opacity-50" />
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhuma meta cadastrada</p>
                        <a href="{{ route('core.goals.create') }}" class="text-primary-600 dark:text-primary-400 text-sm font-medium mt-2 inline-block">Criar meta</a>
                    </div>
                @endforelse
            </div>
        </div>
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="chart-pie" style="duotone" class="w-5 h-5 text-primary-500" />
                    Orçamentos
                </h3>
                @can('create', \Modules\Core\Models\Budget::class)
                    <a href="{{ route('core.budgets.create') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">Novo Orçamento</a>
                @endcan
            </div>
            <div class="space-y-4">
                @forelse($budgets as $budget)
                    <x-core::budget-card :budget="$budget" />
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
                        <x-icon name="calculator" style="duotone" class="w-12 h-12 mx-auto mb-3 text-gray-400 opacity-50" />
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum orçamento cadastrado</p>
                        <a href="{{ route('core.budgets.create') }}" class="text-primary-600 dark:text-primary-400 text-sm font-medium mt-2 inline-block">Criar orçamento</a>
                    </div>
                @endforelse
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
