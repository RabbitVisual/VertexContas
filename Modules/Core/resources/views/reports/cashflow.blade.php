<x-paneluser::layouts.master :title="'Fluxo de Caixa'">
@php
    $isPro = auth()->user()->hasRole('pro_user') || auth()->user()->hasRole('admin');
    $summary = $cashFlowSummary ?? ['income' => 0, 'expense' => 0, 'balance' => 0, 'savings_rate' => 0];
    $accumulatedBalance = $cashFlow->map(function ($item, $i) use ($cashFlow) {
        return $cashFlow->take($i + 1)->sum('balance');
    })->values();
    $topCat = ($topCategories ?? collect())->first();
    $topCategoryPct = ($topCat && ($topCat['total'] ?? 0) > 0 && $summary['expense'] > 0)
        ? (($topCat['total'] / $summary['expense']) * 100) : null;
    $bestMonth = $cashFlow->sortByDesc('balance')->first();
    $worstMonth = $cashFlow->sortBy('balance')->first();
@endphp
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <a href="{{ route('core.reports.index') }}" class="hover:underline">Relatórios</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Fluxo de Caixa</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Fluxo de <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Caixa</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Acompanhe suas receitas e despesas ao longo do tempo. Dados reais, sem transferências internas.</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Use os gráficos para comparar meses, identificar tendências e tomar decisões informadas.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @if($isPro)
                    <a href="{{ route('core.reports.cashflow.view', request()->only(['months','account_id'])) }}" target="_blank" rel="noopener noreferrer" class="flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-bold text-sm" title="Abre em nova aba. Use Ctrl+P para imprimir.">
                        <x-icon name="print" style="solid" class="mr-2" /> Ver / Imprimir
                    </a>
                    <a href="{{ route('core.reports.export.cashflow.xlsx', request()->only(['months','account_id'])) }}" class="flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-bold text-sm" title="Excel com Resumo, Por Conta, Por Categoria e Detalhes.">
                        <x-icon name="file-excel" style="solid" class="mr-2" /> Excel
                    </a>
                    <a href="{{ route('core.reports.export.cashflow.csv', request()->only(['months','account_id'])) }}" class="flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 font-bold text-sm">
                        <x-icon name="file-csv" style="solid" class="mr-2" /> CSV
                    </a>
                @else
                    <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 rounded-lg cursor-not-allowed font-bold text-sm"><x-icon name="lock" style="solid" class="mr-2" /> Ver / Imprimir</button>
                    <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 rounded-lg cursor-not-allowed font-bold text-sm"><x-icon name="lock" style="solid" class="mr-2" /> Excel</button>
                    <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 rounded-lg cursor-not-allowed font-bold text-sm"><x-icon name="lock" style="solid" class="mr-2" /> CSV</button>
                @endif
                <a href="{{ route('core.reports.index') }}" class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 font-bold text-sm">
                    <x-icon name="arrow-left" style="solid" class="mr-2" /> Voltar
                </a>
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-6 border border-emerald-100 dark:border-emerald-800">
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Receitas</span>
            <p class="sensitive-value text-2xl font-black text-emerald-700 dark:text-emerald-300 mt-1"><x-core::financial-value :value="$summary['income']" /></p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 border border-red-100 dark:border-red-800">
            <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider">Despesas</span>
            <p class="sensitive-value text-2xl font-black text-red-700 dark:text-red-300 mt-1"><x-core::financial-value :value="$summary['expense']" /></p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Saldo Líquido</span>
            <p class="sensitive-value text-2xl font-black mt-1 {{ $summary['balance'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}"><x-core::financial-value :value="$summary['balance']" /></p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 border border-blue-100 dark:border-blue-800">
            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Taxa de Poupança</span>
            <p class="text-2xl font-black text-blue-700 dark:text-blue-300 mt-1">{{ number_format($summary['savings_rate'], 1) }}%</p>
            <p class="text-xs text-blue-600/80 dark:text-blue-400/80 mt-1">Meta sugerida: 20%</p>
        </div>
    </div>

    {{-- Dicas + O que fazer --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-core::report-tips variant="cashflow" />
        <x-core::report-actions
            :balance="$summary['balance']"
            :savings-rate="$summary['savings_rate']"
            :top-category-percent="$topCategoryPct"
        />
    </div>

    {{-- Filtros PRO --}}
    @if($isPro)
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 p-6 shadow-sm">
        <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <x-icon name="filter" style="duotone" class="w-5 h-5 text-emerald-600" />
            Filtros
        </h2>
        <form method="GET" action="{{ route('core.reports.cashflow') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meses</label>
                <select name="months" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm">
                    <option value="3" {{ request('months', 6) == 3 ? 'selected' : '' }}>3 meses</option>
                    <option value="6" {{ request('months', 6) == 6 ? 'selected' : '' }}>6 meses</option>
                    <option value="12" {{ request('months', 6) == 12 ? 'selected' : '' }}>12 meses</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conta</label>
                <select name="account_id" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm">
                    <option value="">Todas</option>
                    @foreach($accounts ?? [] as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg flex items-center gap-2">
                <x-icon name="magnifying-glass" style="solid" class="w-4 h-4" /> Filtrar
            </button>
        </form>
    </div>
    @endif

    {{-- Insights do período --}}
    @if($cashFlow->isNotEmpty() && $bestMonth && $worstMonth)
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="rounded-xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50/50 dark:bg-emerald-900/10 p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-600 shrink-0">
                <x-icon name="arrow-trend-up" style="solid" class="w-5 h-5" />
            </div>
            <div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase">Melhor mês</span>
                <p class="font-bold text-slate-800 dark:text-white">{{ $bestMonth['month'] }} — <span class="sensitive-value text-emerald-600"><x-core::financial-value :value="$bestMonth['balance']" /></span></p>
            </div>
        </div>
        <div class="rounded-xl border border-red-200 dark:border-red-800/50 bg-red-50/50 dark:bg-red-900/10 p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-red-500/20 flex items-center justify-center text-red-600 shrink-0">
                <x-icon name="arrow-trend-down" style="solid" class="w-5 h-5" />
            </div>
            <div>
                <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase">Mês mais desafiador</span>
                <p class="font-bold text-slate-800 dark:text-white">{{ $worstMonth['month'] }} — <span class="sensitive-value {{ $worstMonth['balance'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}"><x-core::financial-value :value="$worstMonth['balance']" /></span></p>
            </div>
        </div>
    </div>
    @endif

    {{-- Charts (Vue 3) --}}
    <script>
        window.__CASHFLOW_DATA__ = {
            months: @json($cashFlow->pluck('month')->values()),
            incomes: @json($cashFlow->pluck('income')->values()),
            expenses: @json($cashFlow->pluck('expense')->values()),
            balances: @json($accumulatedBalance),
            totalIncome: {{ $summary['income'] }},
            totalExpense: {{ $summary['expense'] }},
            topCategories: @json($topCategories ?? [])
        };
    </script>
    <div id="cashflow-dashboard"></div>

    {{-- Table: Detalhamento Mensal --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Detalhamento Mensal</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4">Mês</th>
                        <th class="px-6 py-4 text-emerald-600 dark:text-emerald-400">Receitas</th>
                        <th class="px-6 py-4 text-red-600 dark:text-red-400">Despesas</th>
                        <th class="px-6 py-4">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($cashFlow->reverse() as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $item['month'] }}</td>
                            <td class="px-6 py-4 text-emerald-600 font-medium"><span class="sensitive-value"><x-core::financial-value :value="$item['income']" /></span></td>
                            <td class="px-6 py-4 text-red-600 font-medium"><span class="sensitive-value"><x-core::financial-value :value="$item['expense']" /></span></td>
                            <td class="px-6 py-4 font-bold {{ $item['balance'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}"><span class="sensitive-value"><x-core::financial-value :value="$item['balance']" /></span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Table: Por Conta (when multiple accounts) --}}
    @if(($cashFlowByAccount ?? collect())->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Por Conta (Fonte)</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Receitas e despesas por conta bancária</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4">Mês</th>
                        <th class="px-6 py-4">Conta</th>
                        <th class="px-6 py-4 text-emerald-600">Receitas</th>
                        <th class="px-6 py-4 text-red-600">Despesas</th>
                        <th class="px-6 py-4">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($cashFlowByAccount as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-6 py-4 font-medium">{{ $row['month_name'] }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $row['account_name'] }}</td>
                        <td class="px-6 py-4 text-emerald-600"><span class="sensitive-value"><x-core::financial-value :value="$row['income']" /></span></td>
                        <td class="px-6 py-4 text-red-600"><span class="sensitive-value"><x-core::financial-value :value="$row['expense']" /></span></td>
                        <td class="px-6 py-4 font-bold {{ $row['balance'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}"><span class="sensitive-value"><x-core::financial-value :value="$row['balance']" /></span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Table: Top Categorias --}}
    @if(($topCategories ?? collect())->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Top 5 Categorias de Despesa</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4">Categoria</th>
                        <th class="px-6 py-4 text-center">Transações</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-right">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @php $totalExp = $topCategories->sum('total'); @endphp
                    @foreach($topCategories as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full" style="background: {{ $row['color'] }}"></span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $row['category'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">{{ $row['count'] }}</td>
                        <td class="px-6 py-4 text-right font-medium"><span class="sensitive-value"><x-core::financial-value :value="$row['total']" /></span></td>
                        <td class="px-6 py-4 text-right text-slate-500">{{ $totalExp > 0 ? number_format(($row['total'] / $totalExp) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('scripts')
    @vite('resources/js/cashflow-dashboard.js')
@endpush
</x-paneluser::layouts.master>
