@section('title', $account->name)

<x-paneluser::layouts.master>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="font-black text-3xl text-slate-800 dark:text-white flex items-center">
                <div class="bg-primary/10 dark:bg-primary/20 p-2 rounded-xl mr-3">
                    <x-icon name="wallet" class="text-primary" />
                </div>
                {{ $account->name }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 ml-14">Detalhes e histórico da conta.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('core.accounts.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors font-semibold">
                Voltar
            </a>
            <a href="{{ route('core.accounts.edit', $account) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-bold shadow-lg shadow-primary/30">
                <x-icon name="pencil" class="mr-2" /> Editar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Account Card Info -->
        <div class="lg:col-span-1">
             @php
                $cardIcon = match($account->type) {
                    'cash' => 'money-bill-wave',
                    'savings' => 'piggy-bank',
                    'investment' => 'chart-line',
                    default => 'credit-card'
                };
            @endphp
            <div class="relative overflow-hidden rounded-2xl shadow-xl bg-gradient-to-br from-slate-800 to-slate-900 text-white p-8 h-64 flex flex-col justify-between">
                 <!-- Consistent Pattern -->
                 <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
                 <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

                 <div class="relative z-10">
                     <div class="flex justify-between items-start mb-6">
                        <x-icon name="{{ $cardIcon }}" class="text-3xl opacity-80" />
                        <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
                            {{ ucfirst($account->type) }}
                        </span>
                     </div>
                     <div>
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-widest block mb-1">Saldo Atual</span>
                        <span class="text-4xl font-mono font-bold tracking-tight">R$ {{ number_format($account->balance, 2, ',', '.') }}</span>
                     </div>
                 </div>

                 <div class="relative z-10 flex justify-between items-end">
                     <div>
                        <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest block">Titular</span>
                        <span class="font-medium tracking-wide">{{ Str::upper(auth()->user()->name) }}</span>
                     </div>
                     <x-icon name="wifi" class="rotate-90 text-white/50" />
                 </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-slate-800 dark:text-white">Últimas Transações</h3>
                    <a href="{{ route('core.transactions.create') }}" class="text-sm text-primary font-semibold hover:underline">Nova Transação</a>
                </div>

                @if($account->transactions->count() > 0)
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($account->transactions as $transaction)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 rounded-full {{ $transaction->type === 'income' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">
                                        <x-icon name="{{ $transaction->type === 'income' ? 'arrow-down' : 'arrow-up' }}" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $transaction->description }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }} •
                                            @if($transaction->category)
                                                <span class="inline-flex items-center gap-1">
                                                    @if($transaction->category->icon)
                                                        <x-icon name="{{ $transaction->category->icon }}" class="w-3 h-3" />
                                                    @endif
                                                    {{ $transaction->category->name }}
                                                </span>
                                            @else
                                                Sem Categoria
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold font-mono {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                            <x-icon name="list" class="text-2xl text-slate-400" />
                        </div>
                        <p class="text-slate-500 dark:text-slate-400">Nenhuma transação registrada nesta conta.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
