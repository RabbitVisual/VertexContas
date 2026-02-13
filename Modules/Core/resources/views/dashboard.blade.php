<x-paneluser::layouts.master>
    <div class="mb-8">
        <h2 class="font-black text-3xl text-slate-800 dark:text-white">
            Dashboard Financeiro
        </h2>
    </div>

    <div class="py-12 font-['Poppins']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Financial Overview Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

            {{-- Accounts Section --}}
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">
                        <x-icon name="building-columns" style="solid" class="text-primary" /> Minhas Contas
                    </h3>
                    @can('create', \Modules\Core\Models\Account::class)
                        <a href="{{ route('core.accounts.create') }}"
                           class="bg-primary hover:bg-primary-dark text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                            <x-icon name="plus" style="solid" /> Nova Conta
                        </a>
                    @endcan
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($accounts as $account)
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-lg font-black text-slate-800 dark:text-white">{{ $account->name }}</h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 capitalize">{{ $account->type }}</p>
                                </div>
                                <i class="fa-solid fa-{{ $account->type === 'cash' ? 'money-bill-wave' : ($account->type === 'savings' ? 'piggy-bank' : 'credit-card') }} text-primary text-2xl"></i>
                            </div>
                            <p class="text-3xl font-black text-primary">
                                R$ {{ number_format($account->balance, 2, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <x-icon name="wallet" style="solid" size="6xl" class="text-slate-300 dark:text-slate-600 mb-4" />
                            <p class="text-slate-500 dark:text-slate-400 font-bold">Nenhuma conta cadastrada</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Goals and Budgets Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Goals Section --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white">
                            <x-icon name="bullseye" style="solid" class="text-primary" /> Metas
                        </h3>
                        @can('create', \Modules\Core\Models\Goal::class)
                            <a href="{{ route('core.goals.create') }}"
                               class="bg-primary hover:bg-primary-dark text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                                <x-icon name="plus" style="solid" /> Nova Meta
                            </a>
                        @endcan
                    </div>

                    <div class="space-y-4">
                        @forelse($goals as $goal)
                            <x-core::goal-card :goal="$goal" />
                        @empty
                            <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-2xl">
                                <x-icon name="flag-checkered" style="solid" size="6xl" class="text-slate-300 dark:text-slate-600 mb-4" />
                                <p class="text-slate-500 dark:text-slate-400 font-bold">Nenhuma meta cadastrada</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Budgets Section --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white">
                            <x-icon name="chart-pie" style="solid" class="text-primary" /> Orçamentos
                        </h3>
                        @can('create', \Modules\Core\Models\Budget::class)
                            <a href="{{ route('core.budgets.create') }}"
                               class="bg-primary hover:bg-primary-dark text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                                <x-icon name="plus" style="solid" /> Novo Orçamento
                            </a>
                        @endcan
                    </div>

                    <div class="space-y-4">
                        @forelse($budgets as $budget)
                            <x-core::budget-card :budget="$budget" />
                        @empty
                            <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-2xl">
                                <x-icon name="calculator" style="solid" size="6xl" class="text-slate-300 dark:text-slate-600 mb-4" />
                                <p class="text-slate-500 dark:text-slate-400 font-bold">Nenhum orçamento cadastrado</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Subscription Limits Info (Free Users Only) --}}
            @if(auth()->user()->hasRole('free_user'))
                <div class="mt-8 bg-gradient-to-r from-primary/10 to-primary-dark/10 border-2 border-primary/20 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center flex-shrink-0">
                            <x-icon name="crown" style="solid" class="text-white text-xl" />
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-black text-slate-800 dark:text-white mb-2">
                                Plano Gratuito - Limites de Uso
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
                                <div>
                                    <p class="text-slate-600 dark:text-slate-400 font-bold">Receitas</p>
                                    <p class="text-primary font-black">{{ $limits['income']['current'] }}/{{ $limits['income']['limit'] }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-600 dark:text-slate-400 font-bold">Despesas</p>
                                    <p class="text-primary font-black">{{ $limits['expense']['current'] }}/{{ $limits['expense']['limit'] }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-600 dark:text-slate-400 font-bold">Contas</p>
                                    <p class="text-primary font-black">{{ $limits['account']['current'] }}/{{ $limits['account']['limit'] }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-600 dark:text-slate-400 font-bold">Metas</p>
                                    <p class="text-primary font-black">{{ $limits['goal']['current'] }}/{{ $limits['goal']['limit'] }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-600 dark:text-slate-400 font-bold">Orçamentos</p>
                                    <p class="text-primary font-black">{{ $limits['budget']['current'] }}/{{ $limits['budget']['limit'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upgrade to Pro Widget --}}
                <div class="mt-6 bg-gradient-to-br from-amber-400 via-orange-500 to-pink-500 rounded-2xl p-8 shadow-2xl text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

                    <div class="relative z-10">
                        <div class="flex items-start gap-6">
                            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0">
                                <x-icon name="rocket" style="solid" class="text-5xl" />
                            </div>
                            <div class="flex-1">
                                <h3 class="text-3xl font-black mb-3">Desbloqueie Todo o Potencial!</h3>
                                <p class="text-lg mb-6 opacity-90">
                                    Migre para o <strong>Plano PRO</strong> e tenha acesso ilimitado a todas as funcionalidades:
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <x-icon name="check-circle" style="solid" class="text-2xl" />
                                        <span class="font-bold">Transações Ilimitadas</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icon name="check-circle" style="solid" class="text-2xl" />
                                        <span class="font-bold">Metas e Orçamentos Ilimitados</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icon name="check-circle" style="solid" class="text-2xl" />
                                        <span class="font-bold">Relatórios Avançados</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icon name="check-circle" style="solid" class="text-2xl" />
                                        <span class="font-bold">Exportação CSV/PDF</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icon name="check-circle" style="solid" class="text-2xl" />
                                        <span class="font-bold">Transações Recorrentes</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icon name="check-circle" style="solid" class="text-2xl" />
                                        <span class="font-bold">Suporte Prioritário</span>
                                    </div>
                                </div>

                                <a href="#" class="inline-block bg-white text-orange-600 px-8 py-4 rounded-full text-lg font-black shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                                    <x-icon name="crown" style="solid" /> Migrar para PRO Agora
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Analytics Charts (Pro Users or All Users) --}}
            @if(auth()->user()->hasRole('pro_user') || true) {{-- Change to pro_user only if needed --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">

                    {{-- Cash Flow Chart --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
                        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6">
                            <x-icon name="chart-area" style="solid" class="text-primary" /> Fluxo de Caixa (6 meses)
                        </h3>
                        <div id="cashFlowChart"></div>
                    </div>

                    {{-- Category Spending Chart --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
                        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6">
                            <x-icon name="chart-pie" style="solid" class="text-primary" /> Gastos por Categoria
                        </h3>
                        <div id="categoryChart"></div>
                    </div>

                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        // Expose chart data to global scope for charts.js
        window.cashFlowData = {!! json_encode($cashFlowData ?? ['income' => [0, 0, 0, 0, 0, 0], 'expenses' => [0, 0, 0, 0, 0, 0], 'months' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun']]) !!};
        window.categoryData = {!! json_encode($categoryData ?? ['labels' => ['Sem dados'], 'values' => [0]]) !!};
    </script>
    @endpush
</x-paneluser::layouts.master>
