<x-paneluser::layouts.master :title="'Dashboard'">
    <!-- Cards de Resumo Financeiro -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Saldo Atual -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-lg hover:border-primary-200 dark:hover:border-primary-800 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <x-icon name="wallet" style="duotone" class="text-6xl text-primary-500" />
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Atual</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                R$ {{ number_format($balance ?? 0, 2, ',', '.') }}
            </h3>
            <div class="mt-4 flex items-center text-xs">
                <span class="flex items-center {{ ($balance ?? 0) >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-lg">
                    <x-icon name="{{ ($balance ?? 0) >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}" style="solid" class="mr-1" />
                    Total Disponível
                </span>
            </div>
        </div>

        <!-- Receitas -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-800 transition-all duration-300">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <x-icon name="arrow-down" style="solid" class="text-6xl text-emerald-500" />
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Receitas</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                R$ {{ number_format($totalIncome ?? 0, 2, ',', '.') }}
            </h3>
             <div class="mt-4 flex items-center text-xs">
                <span class="flex items-center text-emerald-500 font-bold bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-lg">
                    <x-icon name="check" style="solid" class="mr-1" />
                    Recebido este mês
                </span>
            </div>
        </div>

        <!-- Despesas -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-lg hover:border-rose-200 dark:hover:border-rose-800 transition-all duration-300">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <x-icon name="arrow-up" style="solid" class="text-6xl text-rose-500" />
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Despesas</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                R$ {{ number_format($totalExpense ?? 0, 2, ',', '.') }}
            </h3>
             <div class="mt-4 flex items-center text-xs">
                <span class="flex items-center text-rose-500 font-bold bg-rose-50 dark:bg-rose-900/20 px-2 py-1 rounded-lg">
                    <x-icon name="fire" style="solid" class="mr-1" />
                    Gasto este mês
                </span>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Fluxo de Caixa -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
             <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="chart-line" style="solid" class="text-primary-500" />
                    Fluxo de Caixa
                </h3>
             </div>
             <div id="cashFlowChart" class="w-full min-h-[300px]"></div>
        </div>

        <!-- Gastos por Categoria -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <x-icon name="chart-pie" style="solid" class="text-primary-500" />
                Despesas por Categoria
            </h3>
            <div id="spendingChart" class="w-full min-h-[280px] flex items-center justify-center">
                @if($spendingByCategory->isEmpty())
                     <div class="text-center py-8 opacity-50">
                        <x-icon name="chart-pie" style="solid" class="text-4xl text-gray-300 dark:text-gray-600 mb-2" />
                        <p class="text-sm font-bold text-gray-400">Ainda não há dados de despesas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Atividade Recente & Metas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Timeline de Atividade -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                 <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="clock-rotate-left" style="solid" class="text-primary-500" />
                    Atividade Recente
                </h3>
                <a href="{{ route('core.transactions.index') ?? '#' }}" class="text-sm text-primary-600 hover:text-primary-700 font-semibold hover:underline">
                    Ver Tudo
                </a>
            </div>

            <div class="relative pl-4 border-l-2 border-gray-200 dark:border-gray-700 space-y-8">
                @forelse($recentTransactions as $transaction)
                    <div class="relative group">
                        <div class="absolute -left-[21px] bg-white dark:bg-gray-800 p-1">
                            <div class="w-2.5 h-2.5 rounded-full ring-4 ring-white dark:ring-gray-800 {{ $transaction->type === 'income' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                        </div>
                        <div class="flex justify-between items-start pl-4 hover:translate-x-1 transition-transform duration-200">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $transaction->description }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-lg {{ $transaction->type === 'income' ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-rose-600 bg-rose-50 dark:bg-rose-900/20 dark:text-rose-400' }}">
                                        {{ $transaction->category->name ?? 'Geral' }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $transaction->date->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="font-mono font-bold {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 pl-4">
                        <x-icon name="ghost" style="solid" class="text-4xl text-gray-300 dark:text-gray-600 mb-2" />
                        <p class="text-sm font-bold text-gray-400">Nenhuma transação recente</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Metas -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                 <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="bullseye" style="solid" class="text-primary-500" />
                    Metas
                </h3>
                 <a href="{{ route('core.goals.create') ?? '#' }}" class="text-sm text-primary-600 hover:text-primary-700 font-semibold hover:underline">
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
                    <div class="group">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-primary-500 transition-colors">{{ $goal->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Restante <span class="font-semibold text-gray-700 dark:text-gray-300">R$ {{ number_format($remaining, 2, ',', '.') }}</span>
                                </p>
                            </div>
                            <span class="text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-lg">
                                {{ number_format($percentage, 0) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-500 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out shadow-sm" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                         <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <x-icon name="bullseye" style="solid" class="text-gray-400" />
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Defina metas para acompanhar seu progresso</p>
                    </div>
                @endforelse
            </div>

             @if($goals->count() > 0)
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('core.goals.index') ?? '#' }}" class="block w-full text-center py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-500 bg-gray-50 dark:bg-gray-900/50 rounded-xl transition-colors font-medium">
                        Ver todas as metas
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.initCashFlowChart) {
                window.initCashFlowChart(
                    "#cashFlowChart",
                    {!! json_encode($chartLabels) !!},
                    {!! json_encode($incomeData) !!},
                    {!! json_encode($expenseData) !!},
                    document.documentElement.classList.contains('dark')
                );
            }

            @if($spendingByCategory->count() > 0)
                if (window.initSpendingChart) {
                    window.initSpendingChart(
                        "#spendingChart",
                        {!! json_encode($spendingByCategory->pluck('total')) !!},
                        {!! json_encode($spendingByCategory->pluck('name')) !!},
                        {!! json_encode($spendingByCategory->pluck('color')) !!},
                        document.documentElement.classList.contains('dark')
                    );
                }
            @endif
        });
    </script>
</x-paneluser::layouts.master>
