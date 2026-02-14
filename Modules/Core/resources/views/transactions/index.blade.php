@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Extrato Financeiro'">
    <div class="space-y-8 pb-8">
        {{-- Hero Header - Vertex Premium Style --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-primary-600/20 to-transparent"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500/20 border border-primary-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="money-bill-transfer" class="w-4 h-4 text-primary-300" />
                        <span class="text-primary-200 text-xs font-black uppercase tracking-[0.2em]">Financeiro</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Extrato Financeiro</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base md:text-lg leading-relaxed">Acompanhe suas movimentações com precisão</p>
                </div>
                <div class="flex flex-wrap gap-3 shrink-0" x-data="{}">
                    <button @click="$dispatch('open-filters')" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold transition-all backdrop-blur-md">
                        <x-icon name="filter" class="w-4 h-4 text-white/70" />
                        Filtros
                    </button>
                    @can('create', \Modules\Core\Models\Transaction::class)
                        <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-primary-600 text-white font-bold hover:bg-primary-500 transition-colors shadow-lg shadow-primary-900/40">
                            <x-icon name="plus" class="w-4 h-4" />
                            Nova Transação
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-200 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800/30 flex items-center justify-center">
                        <x-icon name="circle-check" style="solid" class="text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="p-2 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-800/30 transition-colors text-emerald-400">
                    <x-icon name="xmark" />
                </button>
            </div>
        @endif

        {{-- Stats Summary --}}
        @php
            $incomeTotal = $transactions->where('type', 'income')->sum('amount');
            $expenseTotal = $transactions->where('type', 'expense')->sum('amount');
            $balance = $incomeTotal - $expenseTotal;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <x-icon name="arrow-up" style="solid" class="text-sm" />
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Receitas (Página)</p>
                    </div>
                    <p class="sensitive-value text-2xl font-black text-slate-900 dark:text-white font-mono tabular-nums">R$ {{ number_format($incomeTotal, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-rose-50 dark:bg-rose-900/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                            <x-icon name="arrow-down" style="solid" class="text-sm" />
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Despesas (Página)</p>
                    </div>
                    <p class="sensitive-value text-2xl font-black text-slate-900 dark:text-white font-mono tabular-nums">R$ {{ number_format($expenseTotal, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-primary-50 dark:bg-primary-900/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <x-icon name="scale-balanced" class="text-sm" />
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Saldo Período</p>
                    </div>
                    <p class="sensitive-value text-2xl font-black {{ $balance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} font-mono tabular-nums">R$ {{ number_format($balance, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- PRO Actions Bar --}}
        @if($isPro)
            <div class="flex items-center justify-between gap-4 p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/30 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                        <x-icon name="crown" class="text-sm" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Recursos PRO Liberados</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Exporte seus dados e visualize relatórios avançados.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[11px] font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center gap-2 shadow-sm">
                        <x-icon name="file-pdf" style="solid" class="text-rose-500 text-xs" />
                        PDF
                    </button>
                    <button class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[11px] font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center gap-2 shadow-sm">
                        <x-icon name="file-excel" style="solid" class="text-emerald-500 text-xs" />
                        Excel
                    </button>
                    <a href="{{ route('core.transactions.transfer') }}" class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-wider hover:opacity-90 transition-all shadow-sm">
                        Transferência
                    </a>
                </div>
            </div>
        @endif

        {{-- Transaction List --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/20">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-slate-600 dark:text-slate-400 shadow-sm">
                        <x-icon name="list-ul" style="solid" class="text-xs" />
                    </div>
                    Movimentações
                </h3>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $transactions->total() }} registros encontrados</span>
            </div>

            <div class="p-6">
                @php
                    $groupedTransactions = $transactions->groupBy(function($tx) {
                        return \Carbon\Carbon::parse($tx->date, config('app.timezone'))
                            ->locale(config('app.locale', 'pt_BR'))
                            ->translatedFormat('j \d\e F \d\e Y');
                    });
                @endphp

                @forelse($groupedTransactions as $date => $dailyTransactions)
                    <div class="mb-10 last:mb-0">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shadow-sm backdrop-blur-md">
                                {{ Str::title($date) }}
                            </span>
                            <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                        </div>

                        <div class="space-y-4">
                            @foreach($dailyTransactions as $transaction)
                                <div class="group relative flex items-center gap-4 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/20 hover:bg-white dark:hover:bg-slate-800 hover:border-primary-200 dark:hover:border-primary-900/30 hover:shadow-xl hover:shadow-primary-600/5 transition-all">
                                    <div class="shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center {{ $transaction->type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' }} border {{ $transaction->type === 'income' ? 'border-emerald-200 dark:border-emerald-800/50' : 'border-rose-200 dark:border-rose-800/50' }} shadow-sm transition-transform group-hover:scale-110">
                                        <x-icon name="{{ $transaction->category->icon ?? ($transaction->type === 'income' ? 'arrow-trend-up' : 'arrow-trend-down') }}" style="solid" class="text-xl" />
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-slate-900 dark:text-white truncate text-base">{{ $transaction->description ?: 'Sem descrição' }}</h4>
                                            @if($transaction->status === 'pending')
                                                <span class="px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-wider">Pendente</span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                            <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ $transaction->category->name ?? 'Geral' }}</span>
                                            <div class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-500 flex items-center gap-1.5">
                                                <x-icon name="piggy-bank" class="text-[10px] opacity-70" />
                                                {{ $transaction->account->name }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="sensitive-value text-lg font-black font-mono tabular-nums tracking-tight {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                        </p>

                                        <div class="flex items-center justify-end gap-1.5 mt-2 opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                                            <a href="{{ route('core.transactions.edit', $transaction) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-primary-600 hover:text-white transition-all shadow-sm">
                                                <x-icon name="pen" style="solid" class="text-[10px]" />
                                            </a>
                                            <form action="{{ route('core.transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Deseja realmente excluir esta transação?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                    <x-icon name="trash" style="solid" class="text-[10px]" />
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-slate-50 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 text-slate-300 dark:text-slate-800 mb-6">
                            <x-icon name="receipt" style="solid" class="text-4xl" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Sem movimentações</h3>
                        <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-8 font-medium">Você ainda não registrou nenhuma transação neste período ou com estes filtros.</p>
                        <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-black uppercase tracking-wider text-sm rounded-2xl shadow-xl shadow-primary-900/20 transition-all transform hover:-translate-y-1">
                            <x-icon name="plus" />
                            Nova Transação
                        </a>
                    </div>
                @endforelse
            </div>

            @if($transactions->hasPages())
                <div class="px-6 py-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/10">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Filter Drawer (Slide-over) --}}
    <div x-data="{ open: false }"
         @open-filters.window="open = true"
         x-show="open"
         class="fixed inset-0 z-[100] overflow-hidden"
         aria-labelledby="slide-over-title"
         role="dialog"
         aria-modal="true"
         style="display: none;">
        <div class="absolute inset-0 overflow-hidden">
            {{-- Backdrop --}}
            <div x-show="open"
                 x-transition:enter="ease-in-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                 @click="open = false"
                 aria-hidden="true"></div>

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="open"
                     x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="pointer-events-auto w-screen max-w-md">

                    <div class="flex h-full flex-col bg-white dark:bg-slate-900 shadow-2xl overflow-y-scroll">
                        <div class="px-6 py-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 sticky top-0 z-10 backdrop-blur-md">
                            <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight" id="slide-over-title">Filtrar Extrato</h2>
                                <button type="button" @click="open = false" class="rounded-xl p-2 text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                    <x-icon name="xmark" class="text-xl" />
                                </button>
                            </div>
                            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">Refine os dados por período, tipo ou categoria.</p>
                        </div>

                        <form method="GET" action="{{ route('core.transactions.index') }}" class="relative flex-1 px-6 py-8 space-y-8">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-4">Período de Tempo</label>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="space-y-2">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Mês</p>
                                        <select name="month" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl px-4 py-3.5 text-sm font-bold text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all">
                                            <option value="">Todos os meses</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                                    {{ Str::ucfirst(\Carbon\Carbon::create()->month($i)->translatedFormat('F')) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Ano</p>
                                        <select name="year" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl px-4 py-3.5 text-sm font-bold text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all">
                                            <option value="">Todos os anos</option>
                                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                                                <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-4">Tipo de Movimentação</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="" class="peer sr-only" {{ !request('type') ? 'checked' : '' }}>
                                        <div class="w-full text-center py-4 px-2 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-500 group-hover:bg-slate-100 dark:group-hover:bg-slate-800 peer-checked:bg-white dark:peer-checked:bg-slate-700 peer-checked:border-primary-500 peer-checked:text-primary-600 transition-all">
                                            <p class="text-[10px] font-black uppercase tracking-tighter">Todos</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="income" class="peer sr-only" {{ request('type') == 'income' ? 'checked' : '' }}>
                                        <div class="w-full text-center py-4 px-2 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-500 group-hover:bg-slate-100 dark:group-hover:bg-slate-800 peer-checked:bg-white dark:peer-checked:bg-slate-700 peer-checked:border-emerald-500 peer-checked:text-emerald-600 transition-all">
                                            <p class="text-[10px] font-black uppercase tracking-tighter">Receitas</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="expense" class="peer sr-only" {{ request('type') == 'expense' ? 'checked' : '' }}>
                                        <div class="w-full text-center py-4 px-2 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-500 group-hover:bg-slate-100 dark:group-hover:bg-slate-800 peer-checked:bg-white dark:peer-checked:bg-slate-700 peer-checked:border-rose-500 peer-checked:text-rose-600 transition-all">
                                            <p class="text-[10px] font-black uppercase tracking-tighter">Despesas</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            @if($isPro)
                                <div class="p-6 bg-primary-600/5 dark:bg-primary-400/5 rounded-3xl border border-primary-200/50 dark:border-primary-800/50">
                                    <div class="flex items-center gap-3 mb-4">
                                        <x-icon name="sparkles" class="text-primary-600 dark:text-primary-400" />
                                        <p class="text-xs font-black uppercase tracking-widest text-primary-700 dark:text-primary-300">Filtros Avançados PRO</p>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest ml-1">Valor Mínimo</p>
                                            <input type="number" name="min_amount" placeholder="0.00" class="w-full bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-primary-500 transition-all">
                                        </div>
                                        <div class="space-y-2">
                                            <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest ml-1">Descrição</p>
                                            <input type="text" name="search" placeholder="Buscar por texto..." class="w-full bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-primary-500 transition-all">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="pt-8 grid grid-cols-2 gap-4 sticky bottom-0 bg-white dark:bg-slate-900 py-6 mt-auto border-t border-slate-100 dark:border-slate-800">
                                <a href="{{ route('core.transactions.index') }}" class="flex items-center justify-center px-6 py-4 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">Limpar</a>
                                <button type="submit" class="flex items-center justify-center px-6 py-4 rounded-2xl bg-primary-600 text-white font-black uppercase tracking-wider hover:bg-primary-700 shadow-lg shadow-primary-900/30 transition-all">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
