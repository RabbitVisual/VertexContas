<x-paneluser::layouts.master :title="'Extrato Vertex'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-teal-600 dark:text-teal-500 uppercase tracking-widest mb-4">
                    <a href="{{ route('core.reports.index') }}" class="hover:underline">Relatórios</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Extrato Vertex</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Extrato <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600 dark:from-teal-400 dark:to-emerald-400">Vertex</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Lista de transações com data, descrição, débito, crédito e saldo acumulado.</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Use para conferir contra o banco, reconciliar ou exportar para análise.</p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('core.reports.index') }}" class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 font-bold text-sm">
                    <x-icon name="arrow-left" style="solid" class="mr-2" /> Voltar
                </a>
            </div>
        </div>
    </div>

    {{-- KPIs do extrato --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-5 border border-emerald-100 dark:border-emerald-800">
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Total Créditos</span>
            <p class="sensitive-value text-xl font-black text-emerald-700 dark:text-emerald-300 mt-1"><x-core::financial-value :value="$totals['total_credit'] ?? 0" /></p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-5 border border-red-100 dark:border-red-800">
            <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider">Total Débitos</span>
            <p class="sensitive-value text-xl font-black text-red-700 dark:text-red-300 mt-1"><x-core::financial-value :value="$totals['total_debit'] ?? 0" /></p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Saldo Final</span>
            <p class="sensitive-value text-xl font-black mt-1 {{ ($totals['final_balance'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}"><x-core::financial-value :value="$totals['final_balance'] ?? 0" /></p>
        </div>
    </div>

    {{-- Dicas + O que fazer --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-core::report-tips variant="extrato" />
        <x-core::report-actions
            :balance="$totals['final_balance'] ?? 0"
            :savings-rate="0"
            :top-category-percent="null"
        />
    </div>

    {{-- Filtros PRO --}}
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 p-6 shadow-sm">
        <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <x-icon name="filter" style="duotone" class="w-5 h-5 text-teal-600" />
            Filtros
        </h2>
        <form method="GET" action="{{ route('core.reports.extrato') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Início</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Fim</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conta</label>
                <select name="account_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm">
                    <option value="">Todas</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm">
                    <option value="">Todos</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Receitas</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Despesas</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg flex items-center justify-center gap-2">
                    <x-icon name="magnifying-glass" style="solid" class="w-4 h-4" />
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    {{-- Ações de export PRO --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('core.reports.extrato.view', request()->query()) }}" target="_blank" rel="noopener noreferrer" class="flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-bold text-sm" title="Abre em nova aba. Use Ctrl+P para imprimir ou salvar como PDF.">
            <x-icon name="print" style="solid" class="mr-2" /> Ver / Imprimir
        </a>
        <a href="{{ route('core.reports.export.extrato.xlsx', request()->query()) }}" class="flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-bold text-sm" title="Excel formatado com cores e totais em destaque.">
            <x-icon name="file-excel" style="solid" class="mr-2" /> Excel
        </a>
        <a href="{{ route('core.reports.export.extrato.csv', request()->query()) }}" class="flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 font-bold text-sm" title="Dados em formato CSV (texto).">
            <x-icon name="file-csv" style="solid" class="mr-2" /> CSV
        </a>
        <a href="{{ route('core.reports.index') }}" class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 font-bold text-sm">
            <x-icon name="arrow-left" style="solid" class="mr-2" /> Voltar
        </a>
    </div>

    {{-- Tabela --}}
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl shadow-lg border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/30 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">Transações</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Período: {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }} — {{ $statement->count() }} {{ Str::plural('transação', $statement->count()) }}</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-100 dark:bg-slate-800/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-300 sticky top-0">
                    <tr>
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Descrição</th>
                        <th class="px-6 py-4">Categoria</th>
                        <th class="px-6 py-4">Conta</th>
                        <th class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400">Crédito</th>
                        <th class="px-6 py-4 text-right text-red-600 dark:text-red-400">Débito</th>
                        <th class="px-6 py-4 text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($statement as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                            <td class="px-6 py-4 font-mono text-xs">{{ $item['transaction']->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $item['transaction']->description ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $item['transaction']->category?->name ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $item['transaction']->account?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-right text-emerald-600 font-medium"><span class="sensitive-value">{{ $item['credit'] > 0 ? 'R$ ' . number_format($item['credit'], 2, ',', '.') : '—' }}</span></td>
                            <td class="px-6 py-4 text-right text-red-600 font-medium"><span class="sensitive-value">{{ $item['debit'] > 0 ? 'R$ ' . number_format($item['debit'], 2, ',', '.') : '—' }}</span></td>
                            <td class="px-6 py-4 text-right font-bold {{ $item['balance'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}"><span class="sensitive-value">R$ {{ number_format($item['balance'], 2, ',', '.') }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">
                                Nenhuma transação no período selecionado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($statement->isNotEmpty())
                <tfoot class="bg-slate-100 dark:bg-slate-800/50 font-bold">
                    <tr>
                        <td colspan="4" class="px-6 py-4">TOTAL</td>
                        <td class="px-6 py-4 text-right text-emerald-600"><span class="sensitive-value">R$ {{ number_format($totals['total_credit'], 2, ',', '.') }}</span></td>
                        <td class="px-6 py-4 text-right text-red-600"><span class="sensitive-value">R$ {{ number_format($totals['total_debit'], 2, ',', '.') }}</span></td>
                        <td class="px-6 py-4 text-right {{ $totals['final_balance'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}"><span class="sensitive-value">R$ {{ number_format($totals['final_balance'], 2, ',', '.') }}</span></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
</x-paneluser::layouts.master>
