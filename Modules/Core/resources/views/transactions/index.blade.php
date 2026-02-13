<x-paneluser::layouts.master>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4" x-data="{ filterOpen: false }">
        <div>
            <h2 class="font-black text-3xl text-slate-800 dark:text-white flex items-center">
                <div class="bg-primary/10 dark:bg-primary/20 p-2 rounded-xl mr-3">
                    <x-icon name="money-bill-transfer" class="text-primary" />
                </div>
                Extrato
            </h2>
             <p class="text-slate-500 dark:text-slate-400 mt-1 ml-14">Acompanhe suas receitas e despesas detalhadas.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button @click="filterOpen = true" class="flex items-center px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <x-icon name="filter" class="mr-2" /> Filtros
            </button>
            @can('create', \Modules\Core\Models\Transaction::class)
                <a href="{{ route('core.transactions.create') }}"
                   class="flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg font-bold shadow-lg shadow-primary/25 transition-all">
                    <x-icon name="plus" class="mr-2" /> Nova
                </a>
            @endcan
        </div>

        <!-- Filter Drawer (Right Side) -->
        <div x-show="filterOpen" style="display: none;" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div x-show="filterOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="filterOpen = false"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="filterOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-md">
                            <form method="GET" action="{{ route('core.transactions.index') }}" class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-slate-900 shadow-xl">
                                <div class="px-6 py-6 sm:px-6 bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-lg font-bold text-slate-900 dark:text-white" id="slide-over-title">Filtrar Transações</h2>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" @click="filterOpen = false" class="rounded-md bg-transparent text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                                <span class="sr-only">Close panel</span>
                                                <x-icon name="xmark" class="text-xl" />
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Refine sua busca por período, tipo ou categoria.</p>
                                </div>
                                <div class="relative mt-6 flex-1 px-4 sm:px-6 space-y-6">
                                    <!-- Filter Content -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Período</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <select name="month" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                <option value="">Todo o Ano</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <select name="year" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                                <option value="">Todos os Anos</option>
                                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipo de Transação</label>
                                        <div class="flex rounded-md shadow-sm">
                                            <div class="relative flex-grow focus-within:z-10">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <x-icon name="circle-half-stroke" class="text-slate-400" />
                                                </div>
                                                <select name="type" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 pl-10 focus:border-primary focus:ring-primary sm:text-sm">
                                                    <option value="">Todos</option>
                                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Receitas</option>
                                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Despesas</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-slate-200 dark:border-slate-700 px-4 py-6 sm:px-6">
                                    <div class="flex gap-3">
                                        <a href="{{ route('core.transactions.index') }}" class="flex-1 rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2 text-base font-medium text-slate-700 dark:text-slate-300 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center">Limpar</a>
                                        <button type="submit" class="flex-1 rounded-md border border-transparent bg-primary px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Aplicar Filtros</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center"><x-icon name="circle-check" class="mr-2" /> {{ session('success') }}</div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><x-icon name="xmark" /></button>
        </div>
    @endif

    <div class="space-y-8">
        @php
            $groupedTransactions = $transactions->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->date)->isoFormat('ODD [de] MMMM [de] YYYY');
            });
        @endphp

        @forelse($groupedTransactions as $date => $dailyTransactions)
            <div class="relative">
                <div class="sticky top-20 z-10 mb-4 ml-12">
                     <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shadow-sm">
                        {{ $date }}
                    </span>
                </div>

                <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-6 space-y-6 pb-4">
                    @foreach($dailyTransactions as $transaction)
                        <div class="relative pl-10 group">
                            <!-- Timeline Dot -->
                            <div class="absolute -left-[9px] top-1/2 -translate-y-1/2 w-4 h-4 rounded-full border-2 border-white dark:border-slate-900 {{ $transaction->type === 'income' ? 'bg-emerald-500' : 'bg-rose-500' }} shadow-sm z-10 transition-transform group-hover:scale-125"></div>

                            <!-- Card -->
                            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-all flex items-center justify-between group-hover:border-primary/30">
                                <div class="flex items-center gap-4">
                                     <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-xl {{ $transaction->type === 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
                                        <x-icon name="{{ $transaction->category->icon ?? 'circle-dollar' }}" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 dark:text-white">{{ $transaction->description }}</h4>
                                        <div class="flex items-center text-xs text-slate-500 dark:text-slate-400 mt-1 space-x-2">
                                            <span class="bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded text-slate-600 dark:text-slate-300 font-medium">
                                                {{ $transaction->category->name ?? 'Geral' }}
                                            </span>
                                            <span>•</span>
                                            <span>{{ $transaction->account->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-lg font-bold font-mono {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </span>

                                     <div class="flex justify-end gap-2 mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('core.transactions.edit', $transaction) }}" class="text-slate-400 hover:text-primary transition-colors" title="Editar">
                                            <x-icon name="pen" class="text-sm" />
                                        </a>
                                        <form action="{{ route('core.transactions.destroy', $transaction) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Excluir transação?')" class="text-slate-400 hover:text-red-500 transition-colors" title="Excluir">
                                                <x-icon name="trash" class="text-sm" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 mb-4">
                    <x-icon name="list-ul" class="text-4xl text-slate-300 dark:text-slate-600" />
                </div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300">Nenhuma transação encontrada</h3>
                <p class="text-slate-500 dark:text-slate-500 mt-2">Tente ajustar os filtros ou crie uma nova transação.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="mt-8">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-paneluser::layouts.master>
