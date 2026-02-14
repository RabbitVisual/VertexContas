@php
    $isPro = auth()->user()?->isPro() ?? false;
    $cardIcon = match($account->type) {
        'cash' => 'money-bill-wave',
        'savings' => 'piggy-bank',
        default => 'credit-card'
    };
@endphp
<x-paneluser::layouts.master :title="$account->name">
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-primary/10 dark:bg-primary/15 border border-primary/20 dark:border-primary/30">
                    <x-icon name="building-columns" style="duotone" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                {{ $account->name }}
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 ml-14">Detalhes e histórico da conta</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-medium transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar
            </a>
            <a href="{{ route('core.accounts.edit', $account) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-semibold rounded-xl shadow-sm transition-colors">
                <x-icon name="pencil" style="solid" class="w-4 h-4" />
                Editar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Card visual PicPay-style --}}
        <div class="lg:col-span-1">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 shadow-xl p-6 h-56 flex flex-col justify-between text-white">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_50%_50%,white,transparent)]"></div>
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <x-icon name="{{ $cardIcon }}" style="solid" class="w-8 h-8 opacity-90" />
                    <span class="px-2.5 py-1 bg-white/20 rounded-lg text-xs font-bold uppercase backdrop-blur-sm">{{ $account->type === 'checking' ? 'Corrente' : ($account->type === 'savings' ? 'Poupança' : 'Dinheiro') }}</span>
                </div>
                <div class="relative z-10">
                    <p class="text-xs text-white/70 uppercase tracking-widest mb-1">Saldo</p>
                    <p class="text-3xl font-mono font-bold tabular-nums">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] text-white/60 uppercase tracking-[0.2em] mb-0.5">Nome no cartão</p>
                    <p class="font-semibold tracking-wider">{{ Str::upper($account->name) }}</p>
                </div>
            </div>
        </div>

        {{-- Transações + PRO extras - Excel-style table --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-icon name="receipt" style="duotone" class="w-5 h-5 text-primary-500" />
                        Últimas transações
                    </h3>
                    <a href="{{ route('core.transactions.create') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                        Nova
                    </a>
                </div>
                {{-- Excel-style table: header + zebra rows --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                        <thead class="text-xs uppercase bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold">Descrição</th>
                                <th scope="col" class="px-4 py-3 font-semibold">Data</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($account->transactions as $index => $transaction)
                                <tr class="border-b border-slate-200 dark:border-slate-700 {{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-slate-50/50 dark:bg-slate-800/30' }} hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="p-1.5 rounded-lg {{ $transaction->type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                                <x-icon name="{{ $transaction->type === 'income' ? 'arrow-down' : 'arrow-up' }}" style="solid" class="w-3.5 h-3.5 {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}" />
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900 dark:text-white">{{ $transaction->description }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $transaction->category->name ?? 'Geral' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400 font-mono text-xs">{{ $transaction->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-right font-mono font-semibold tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2 text-slate-500 dark:text-slate-400">
                                            <x-icon name="receipt" style="duotone" class="w-12 h-12 opacity-50" />
                                            <p class="text-sm">Nenhuma transação nesta conta</p>
                                            <a href="{{ route('core.transactions.create') }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium text-sm">Adicionar transação</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PRO: Quick stats (oculto para FREE) - Excel-style --}}
            @if($isPro && $account->transactions->count() > 0)
                @php
                    $income = $account->transactions->where('type', 'income')->sum('amount');
                    $expense = $account->transactions->where('type', 'expense')->sum('amount');
                @endphp
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 border-b border-emerald-100 dark:border-emerald-800/30">
                            <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase">Entradas</p>
                        </div>
                        <p class="p-3 text-lg font-bold text-emerald-600 dark:text-emerald-400 font-mono tabular-nums">R$ {{ number_format($income, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-3 py-2 bg-red-50 dark:bg-red-900/20 border-b border-red-100 dark:border-red-800/30">
                            <p class="text-xs font-semibold text-red-700 dark:text-red-400 uppercase">Saídas</p>
                        </div>
                        <p class="p-3 text-lg font-bold text-red-600 dark:text-red-400 font-mono tabular-nums">R$ {{ number_format($expense, 2, ',', '.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-paneluser::layouts.master>
