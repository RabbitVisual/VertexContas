<x-paneluser::layouts.master>
    <x-paneluser::onboarding-tour :user="$user" />

    <!-- Header & Quick Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                Olá, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-600">{{ explode(' ', $user->name)[0] }}</span>! 👋
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">
                Aqui está o panorama completo das suas finanças hoje.
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('core.transactions.create', ['type' => 'income']) }}" class="group flex items-center px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-xl transition-all border border-slate-200 dark:border-slate-700 hover:border-emerald-200 dark:hover:border-emerald-800 shadow-sm hover:shadow-md">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <x-icon name="arrow-up" class="text-emerald-600 dark:text-emerald-400 text-sm" />
                </div>
                <span class="font-semibold">Nova Receita</span>
            </a>
            <a href="{{ route('core.transactions.create', ['type' => 'expense']) }}" class="group flex items-center px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-900/10 text-slate-700 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 rounded-xl transition-all border border-slate-200 dark:border-slate-700 hover:border-rose-200 dark:hover:border-rose-800 shadow-sm hover:shadow-md">
                <div class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <x-icon name="arrow-down" class="text-rose-600 dark:text-rose-400 text-sm" />
                </div>
                <span class="font-semibold">Nova Despesa</span>
            </a>
             <a href="{{ route('core.accounts.create') }}" class="group flex items-center px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 shadow-sm hover:shadow-md">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <x-icon name="building-columns" class="text-indigo-600 dark:text-indigo-400 text-sm" />
                </div>
                <span class="font-semibold">Nova Conta</span>
            </a>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Balance -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-xl shadow-slate-900/10 group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-colors duration-500"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-primary/20 rounded-full blur-2xl group-hover:bg-primary/30 transition-colors duration-500"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm border border-white/10">
                        <x-icon name="wallet" class="text-white text-xl" />
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 bg-white/10 rounded-lg backdrop-blur-sm border border-white/5 text-slate-200">
                        Saldo Total
                    </span>
                </div>
                <h3 class="text-3xl font-bold tracking-tight mb-1 text-white">
                    <x-core::financial-value :value="$totalBalance ?? 0" />
                </h3>
                <p class="text-sm text-slate-400">Patrimônio acumulado</p>
            </div>
        </div>

        <!-- Monthly Income -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                     <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                        <x-icon name="arrow-up" class="text-emerald-600 dark:text-emerald-400 text-xl" />
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 rounded-lg">
                        Este Mês
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">
                    <x-core::financial-value :value="$monthlyIncome ?? 0" />
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total de Receitas</p>
            </div>
        </div>

        <!-- Monthly Expense -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow group relative overflow-hidden">
             <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/5 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                     <div class="p-3 bg-rose-100 dark:bg-rose-900/30 rounded-xl">
                        <x-icon name="arrow-down" class="text-rose-600 dark:text-rose-400 text-xl" />
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-300 rounded-lg">
                        Este Mês
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">
                    <x-core::financial-value :value="$monthlyExpense ?? 0" />
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total de Despesas</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Cash Flow Area Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                        <x-icon name="chart-mixed" class="mr-2 text-primary" />
                        Fluxo de Caixa
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Receitas vs Despesas (Últimos 6 meses)</p>
                </div>
                <div class="flex gap-2">
                    <span class="flex items-center text-xs font-medium text-slate-500 dark:text-slate-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-1"></span> Receita
                    </span>
                    <span class="flex items-center text-xs font-medium text-slate-500 dark:text-slate-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mr-1"></span> Despesa
                    </span>
                </div>
            </div>
            <div id="cashFlowChart" class="w-full h-[300px]"></div>
        </div>

        <!-- Spending Donut -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1 flex items-center">
                <x-icon name="chart-pie" class="mr-2 text-primary" />
                Gastos por Categoria
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Onde seu dinheiro está indo este mês</p>

            <div class="flex-1 flex items-center justify-center relative">
                 @if($spendingByCategory->count() > 0)
                    <div id="spendingChart" class="w-full h-full min-h-[250px]"></div>
                 @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                            <x-icon name="chart-pie" class="text-slate-400 text-2xl" />
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Sem dados este mês</p>
                    </div>
                 @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity & Goals -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity Feed -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                    <x-icon name="list-check" class="mr-2 text-primary" />
                    Atividade Recente
                </h3>
                <a href="{{ route('core.transactions.index') }}" class="text-sm text-primary hover:text-primary-dark font-semibold hover:underline">
                    Ver tudo
                </a>
            </div>

            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent dark:before:via-slate-700">
                @forelse($recentTransactions as $transaction)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                         <!-- Icon -->
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white dark:border-slate-800 bg-slate-100 dark:bg-slate-700 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 absolute left-0 md:left-1/2 -translate-x-1/2">
                            <x-icon name="{{ $transaction->category->icon ?? 'circle' }}" class="{{ $transaction->type === 'income' ? 'text-emerald-500' : 'text-rose-500' }}" />
                        </div>

                        <!-- Card -->
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm ml-14 md:ml-0 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-semibold {{ $transaction->type === 'income' ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-rose-600 bg-rose-50 dark:bg-rose-900/20 dark:text-rose-400' }} px-2 py-0.5 rounded">
                                    {{ $transaction->category->name ?? 'Geral' }}
                                </span>
                                <time class="text-xs text-slate-400">{{ $transaction->date->format('d/m/Y') }}</time>
                            </div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-1 truncate">{{ $transaction->description }}</h4>
                            <div class="font-mono font-semibold {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 pl-12 md:pl-0">
                        <p class="text-slate-500 dark:text-slate-400">Nenhuma transação recente.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Goals -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                 <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                    <x-icon name="bullseye" class="mr-2 text-primary" />
                    Metas
                </h3>
                 <a href="{{ route('core.goals.create') }}" class="text-sm text-primary hover:text-primary-dark font-semibold hover:underline">
                    Nova
                </a>
            </div>

            <div class="space-y-6">
                @forelse($goals as $goal)
                    @php
                        $percentage = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                        $percentage = min($percentage, 100);
                        $remaining = max(0, $goal->target_amount - $goal->current_amount);
                    @endphp
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm">{{ $goal->name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Faltam <span class="font-semibold text-slate-700 dark:text-slate-300">R$ {{ number_format($remaining, 2, ',', '.') }}</span>
                                </p>
                            </div>
                            <span class="text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-1 rounded-lg">
                                {{ number_format($percentage, 0) }}%
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(59,130,246,0.5)]" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                         <div class="w-12 h-12 bg-slate-50 dark:bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <x-icon name="bullseye-arrow" class="text-slate-400" />
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Defina objetivos para ver seu progresso aqui.</p>
                    </div>
                @endforelse
            </div>

             @if($goals->count() > 0)
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('core.goals.index') }}" class="block w-full text-center py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary bg-slate-50 dark:bg-slate-900/50 rounded-lg transition-colors font-medium">
                        Ver todas as metas
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Chart Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cash Flow Chart
            const cashFlowOptions = {
                series: [{
                    name: 'Receitas',
                    data: {!! json_encode($incomeData) !!}
                }, {
                    name: 'Despesas',
                    data: {!! json_encode($expenseData) !!}
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    fontFamily: 'Instrument Sans, ui-sans-serif, system-ui, sans-serif',
                    toolbar: { show: false },
                    animations: { enabled: true, easing: 'easeinout', speed: 800 }
                },
                colors: ['#10b981', '#f43f5e'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: {!! json_encode($chartLabels) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '12px' }
                    }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '12px' },
                        formatter: (val) => `R$ ${val}`
                    }
                },
                grid: {
                    borderColor: document.documentElement.className.includes('dark') ? '#334155' : '#e2e8f0',
                    strokeDashArray: 4,
                },
                tooltip: {
                    theme: document.documentElement.className.includes('dark') ? 'dark' : 'light',
                    y: { formatter: (val) => `R$ ${val.toFixed(2)}` }
                },
                legend: { position: 'top', horizontalAlign: 'right' }
            };

            const cashFlowChart = new window.ApexCharts(document.querySelector("#cashFlowChart"), cashFlowOptions);
            cashFlowChart.render();

            // Spending Chart
            @if($spendingByCategory->count() > 0)
                const spendingOptions = {
                    series: {!! json_encode($spendingByCategory->pluck('total')) !!},
                    chart: {
                        type: 'donut',
                        height: 280,
                        fontFamily: 'Instrument Sans, ui-sans-serif, system-ui, sans-serif',
                    },
                    labels: {!! json_encode($spendingByCategory->pluck('name')) !!},
                    colors: {!! json_encode($spendingByCategory->pluck('color')) !!},
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    name: { show: true, fontSize: '14px', fontFamily: 'Inter', color: '#64748b' },
                                    value: {
                                        show: true,
                                        fontSize: '16px',
                                        fontFamily: 'Inter',
                                        fontWeight: 600,
                                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#1e293b',
                                        formatter: (val) => `R$ ${parseFloat(val).toFixed(2)}`
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        color: '#64748b',
                                        formatter: function (w) {
                                            const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            return `R$ ${total.toFixed(2)}`;
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: false },
                    legend: { position: 'bottom' },
                    tooltip: {
                         theme: document.documentElement.className.includes('dark') ? 'dark' : 'light',
                        y: { formatter: (val) => `R$ ${val.toFixed(2)}` }
                    }
                };

                const spendingChart = new window.ApexCharts(document.querySelector("#spendingChart"), spendingOptions);
                spendingChart.render();
            @endif
        });
    </script>
</x-paneluser::layouts.master>
