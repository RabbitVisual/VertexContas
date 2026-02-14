@php
    $isPro = auth()->user()?->isPro() ?? false;
    $currentMonth = request()->filled('month') ? (int) request('month') : null;
    $currentYear = request()->filled('year') ? (int) request('year') : now()->year;
    $incomeTotal = $transactions->where('type', 'income')->sum('amount');
    $expenseTotal = $transactions->where('type', 'expense')->sum('amount');
    $balance = $incomeTotal - $expenseTotal;
    $plannedIncome = $recurringTransactions->where('type', 'income')->sum('amount');
    $plannedExpense = $recurringTransactions->where('type', 'expense')->sum('amount');
    $incomeProgress = $plannedIncome > 0 ? min(100, ($incomeTotal / $plannedIncome) * 100) : 0;
    $expenseProgress = $plannedExpense > 0 ? min(100, ($expenseTotal / $plannedExpense) * 100) : 0;
@endphp

<x-paneluser::layouts.master :title="'Extrato Financeiro'">
<div x-data="{ filtersOpen: false }" class="contents">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-slate-600/5 dark:bg-slate-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <span>Financeiro</span>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Extrato</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Seu <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Extrato Mensal</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Todas as movimentações reais nas suas contas. Use os filtros para ver um período ou tipo específico.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <button type="button" @click="filtersOpen = true" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                    <x-icon name="filter" style="duotone" class="w-5 h-5" />
                    Filtros
                </button>
                @can('create', \Modules\Core\Models\Transaction::class)
                    <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                        <x-icon name="plus" style="solid" class="w-5 h-5" />
                        Nova transação
                    </a>
                @endcan
            </div>
        </div>

        {{-- Mini widget: planejamento (sempre visível) --}}
        @if($plannedIncome > 0 || $plannedExpense > 0)
            <div class="relative z-10 mt-8 pt-8 border-t border-gray-200 dark:border-white/5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <span>Renda planejada</span>
                        <span class="text-gray-900 dark:text-white">{{ number_format($incomeProgress, 0) }}%</span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $incomeProgress }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-500">Meta: R$ {{ number_format($plannedIncome, 2, ',', '.') }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <span>Gastos fixos</span>
                        <span class="text-gray-900 dark:text-white">{{ number_format($expenseProgress, 0) }}%</span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: {{ $expenseProgress }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-500">Limite: R$ {{ number_format($plannedExpense, 2, ',', '.') }}</p>
                </div>
                <div class="hidden lg:flex flex-col justify-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Referência</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $currentMonth ? Str::ucfirst(now()->month($currentMonth)->translatedFormat('F')) . ' ' . $currentYear : 'Todos os meses ' . $currentYear }}</span>
                </div>
                <div class="flex items-center justify-end">
                    <a href="{{ route('core.income.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 hover:underline uppercase tracking-wider">
                        Ajustar planejamento
                        <x-icon name="arrow-right" style="solid" class="w-3 h-3" />
                    </a>
                </div>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/10 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <x-icon name="circle-check" style="solid" class="w-5 h-5" />
                </div>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ session('success') }}</span>
            </div>
            <button type="button" @click="show = false" class="p-2 rounded-lg hover:bg-emerald-500/20 text-gray-500 hover:text-gray-700 transition-colors">
                <x-icon name="xmark" style="solid" class="w-5 h-5" />
            </button>
        </div>
    @endif

    {{-- Cards: Receitas, Despesas, Saldo --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <x-icon name="arrow-up" style="solid" class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Total receitas</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white tabular-nums sensitive-value">R$ {{ number_format($incomeTotal, 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">No período filtrado</p>
                </div>
            </div>
        </div>
        <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-rose-500/5 rounded-full blur-2xl"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-600/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0">
                    <x-icon name="arrow-down" style="solid" class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Total despesas</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white tabular-nums sensitive-value">R$ {{ number_format($expenseTotal, 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">No período filtrado</p>
                </div>
            </div>
        </div>
        <div class="relative overflow-hidden rounded-3xl bg-gray-900 dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-6 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center {{ $balance >= 0 ? 'text-emerald-400' : 'text-rose-400' }} shrink-0">
                    <x-icon name="scale-balanced" style="duotone" class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Saldo do período</p>
                    <p class="text-2xl font-black tabular-nums sensitive-value {{ $balance >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">R$ {{ number_format($balance, 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Resultado líquido</p>
                </div>
            </div>
        </div>
    </div>

    @if($isPro)
        <div class="rounded-3xl border border-amber-200 dark:border-amber-900/30 bg-gradient-to-r from-amber-500/10 to-amber-600/10 dark:from-amber-500/5 dark:to-amber-600/5 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                    <x-icon name="crown" style="solid" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Vertex Pro ativo</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Exporte relatórios e faça transferências entre contas.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" title="Exportar PDF" class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:text-rose-500 flex items-center justify-center transition-colors">
                    <x-icon name="file-pdf" style="solid" class="w-5 h-5" />
                </button>
                <button type="button" title="Exportar Excel" class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:text-emerald-500 flex items-center justify-center transition-colors">
                    <x-icon name="file-excel" style="solid" class="w-5 h-5" />
                </button>
                <a href="{{ route('core.transactions.transfer') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-xs uppercase tracking-wider hover:opacity-90 transition-opacity">
                    <x-icon name="right-left" style="solid" class="w-4 h-4" />
                    Transferência
                </a>
            </div>
        </div>
    @endif

    {{-- Lista de transações --}}
    <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-white dark:bg-gray-900/50 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/30 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-500 dark:text-gray-400 ring-1 ring-black/5 dark:ring-white/10">
                    <x-icon name="list-ul" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 dark:text-white">Movimentações</h2>
                    <p class="text-xs text-gray-500">{{ $transactions->total() }} registros</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            @php
                $groupedTransactions = $transactions->groupBy(function($tx) {
                    return \Carbon\Carbon::parse($tx->date, config('app.timezone'))->locale(config('app.locale', 'pt_BR'))->translatedFormat('j \d\e F \d\e Y');
                });
            @endphp

            @forelse($groupedTransactions as $date => $dailyTransactions)
                <div class="mb-10 last:mb-0">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider bg-gray-900 dark:bg-gray-800 text-white">
                            <x-icon name="calendar-days" style="duotone" class="w-4 h-4 text-emerald-400" />
                            {{ Str::title($date) }}
                        </span>
                        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    <div class="space-y-4">
                        @foreach($dailyTransactions as $transaction)
                            <div class="group flex flex-col sm:flex-row sm:items-center gap-4 p-5 rounded-2xl border border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/30 hover:bg-white dark:hover:bg-gray-900 hover:border-emerald-500/30 transition-all duration-300">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $transaction->type === 'income' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }} ring-1 ring-black/5 dark:ring-white/10">
                                        <x-icon name="{{ $transaction->category->icon ?? ($transaction->type === 'income' ? 'arrow-trend-up' : 'arrow-trend-down') }}" style="solid" class="w-5 h-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $transaction->description ?: 'Sem descrição' }}</h3>
                                            @if($transaction->status === 'pending')
                                                <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase">Pendente</span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="inline-flex items-center gap-1">
                                                <x-icon name="tag" style="duotone" class="w-3 h-3" />
                                                {{ $transaction->category->name ?? 'Geral' }}
                                            </span>
                                            <span class="inline-flex items-center gap-1">
                                                <x-icon name="building-columns" style="duotone" class="w-3 h-3" />
                                                {{ $transaction->account->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                                    <p class="sensitive-value text-xl font-black tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </p>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('core.transactions.edit', $transaction) }}" class="w-9 h-9 rounded-lg flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-white/10 text-gray-500 hover:bg-emerald-500 hover:text-white transition-colors" title="Editar">
                                            <x-icon name="pen" style="solid" class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('core.transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta transação?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 rounded-lg flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-white/10 text-gray-500 hover:bg-rose-500 hover:text-white transition-colors" title="Excluir">
                                                <x-icon name="trash-can" style="solid" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-700 mb-6 ring-1 ring-black/5 dark:ring-white/10">
                        <x-icon name="receipt" style="duotone" class="w-12 h-12 opacity-50" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhuma movimentação</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto text-sm mb-8">Não há transações no período ou filtros selecionados. Registre uma receita ou despesa para começar.</p>
                    <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-emerald-500/20">
                        <x-icon name="plus" style="solid" class="w-5 h-5" />
                        Nova transação
                    </a>
                </div>
            @endforelse
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-5 border-t border-gray-200 dark:border-white/5 bg-gray-50/30 dark:bg-gray-950/30">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    {{-- Dica --}}
    <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">Como funciona o Extrato no Vertex Contas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">O extrato mostra todas as <strong>transações reais</strong> (receitas e despesas) que movimentam o saldo das suas contas. Os totais acima referem-se ao período dos filtros. Sua <strong>capacidade mensal</strong> vem do planejamento (Minha Renda), não da soma do extrato. Use os filtros para ver um mês específico ou só receitas/despesas. @if($isPro) Como Vertex Pro, você pode exportar relatórios e fazer transferências entre contas. @endif</p>
            </div>
        </div>
    </div>
</div>

{{-- Drawer de filtros --}}
<div x-show="filtersOpen" x-cloak class="fixed inset-0 z-[100] overflow-hidden" aria-hidden="true" aria-label="Painel de filtros">
    <div x-show="filtersOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/80 dark:bg-black/60 backdrop-blur-sm" @click="filtersOpen = false" aria-hidden="true"></div>
    <div class="pointer-events-none fixed inset-0 flex justify-end sm:max-w-full">
        <div x-show="filtersOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-full sm:w-[min(100%,22rem)] sm:max-w-md min-h-full flex flex-col bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-white/10 shadow-2xl overflow-y-auto safe-area-pb">
            <form method="GET" action="{{ route('core.transactions.index') }}" class="flex flex-col min-h-full">
                {{-- Cabeçalho --}}
                <div class="flex items-center justify-between gap-4 px-4 sm:px-6 py-5 sm:py-6 border-b border-gray-200 dark:border-white/5 shrink-0 bg-white dark:bg-gray-900 sticky top-0 z-10">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center shrink-0 text-emerald-600 dark:text-emerald-400">
                            <x-icon name="filter" style="duotone" class="w-5 h-5 sm:w-6 sm:h-6" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate">Filtros</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Período e tipo @if($isPro) · Pro @endif</p>
                        </div>
                    </div>
                    <button type="button" @click="filtersOpen = false" class="w-10 h-10 rounded-xl border border-gray-200 dark:border-white/10 text-gray-500 hover:text-rose-500 hover:border-rose-200 dark:hover:border-rose-800 flex items-center justify-center shrink-0 transition-colors" aria-label="Fechar filtros">
                        <x-icon name="xmark" style="solid" class="w-5 h-5" />
                    </button>
                </div>

                {{-- Conteúdo --}}
                <div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-5 sm:space-y-6">
                    {{-- Período: Mês + Ano em linha no desktop --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div class="space-y-2">
                            <label for="filter-month" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mês</label>
                            <select id="filter-month" name="month" class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-shadow font-medium text-sm">
                                <option value="" {{ !request()->filled('month') ? 'selected' : '' }}>Todos</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') !== null && (int) request('month') === $i ? 'selected' : '' }}>{{ Str::ucfirst(\Carbon\Carbon::create()->month($i)->translatedFormat('F')) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label for="filter-year" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ano</label>
                            <select id="filter-year" name="year" class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-shadow font-medium text-sm">
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ (string) request('year', now()->year) === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Tipo: coluna em mobile, linha em desktop --}}
                    <div class="space-y-3">
                        <span class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</span>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 rounded-xl border border-gray-200 dark:border-white/10 p-1.5 sm:p-2 bg-gray-50 dark:bg-gray-950">
                            <label class="flex-1 cursor-pointer min-w-0">
                                <input type="radio" name="type" value="" class="peer sr-only" {{ !request('type') ? 'checked' : '' }}>
                                <span class="flex items-center justify-center gap-2 py-3 sm:py-2.5 px-4 rounded-lg text-sm font-bold text-gray-600 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-gray-900 dark:peer-checked:text-white peer-checked:shadow-sm border border-transparent peer-checked:border-gray-200 dark:peer-checked:border-white/10 transition-all">Todos</span>
                            </label>
                            <label class="flex-1 cursor-pointer min-w-0">
                                <input type="radio" name="type" value="income" class="peer sr-only" {{ request('type') == 'income' ? 'checked' : '' }}>
                                <span class="flex items-center justify-center gap-2 py-3 sm:py-2.5 px-4 rounded-lg text-sm font-bold text-gray-600 dark:text-gray-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-sm transition-all">Receitas</span>
                            </label>
                            <label class="flex-1 cursor-pointer min-w-0">
                                <input type="radio" name="type" value="expense" class="peer sr-only" {{ request('type') == 'expense' ? 'checked' : '' }}>
                                <span class="flex items-center justify-center gap-2 py-3 sm:py-2.5 px-4 rounded-lg text-sm font-bold text-gray-600 dark:text-gray-400 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-sm transition-all">Despesas</span>
                            </label>
                        </div>
                    </div>

                    @if($isPro)
                        <div class="p-4 rounded-xl bg-amber-500/5 dark:bg-amber-500/10 border border-amber-200/50 dark:border-amber-800/30 space-y-4">
                            <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider flex items-center gap-2">
                                <x-icon name="sparkles" style="duotone" class="w-4 h-4 shrink-0" />
                                Vertex Pro
                            </p>
                            <div class="space-y-2">
                                <label for="filter-search" class="block text-xs font-bold text-gray-500 dark:text-gray-400">Buscar na descrição</label>
                                <input id="filter-search" type="text" name="search" value="{{ request('search') }}" placeholder="Ex: Mercado" class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label for="filter-min-amount" class="block text-xs font-bold text-gray-500 dark:text-gray-400">Valor mínimo (R$)</label>
                                <input id="filter-min-amount" type="number" name="min_amount" value="{{ request('min_amount') }}" placeholder="0,00" step="0.01" min="0" class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Rodapé fixo --}}
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-t border-gray-200 dark:border-white/5 flex flex-col-reverse sm:flex-row gap-3 shrink-0 bg-white dark:bg-gray-900">
                    <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center justify-center py-3 px-4 rounded-xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-semibold text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Limpar</a>
                    <button type="submit" class="inline-flex items-center justify-center py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-colors shadow-lg shadow-emerald-500/20 flex-1 sm:flex-initial">Aplicar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</x-paneluser::layouts.master>
