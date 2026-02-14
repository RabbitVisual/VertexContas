@php
    $isPro = auth()->user()?->isPro() ?? false;
    $cardIcon = match($account->type) {
        'cash' => 'money-bill-wave',
        'savings' => 'piggy-bank',
        default => 'credit-card'
    };
@endphp
<x-paneluser::layouts.master :title="$account->name">
    <div class="space-y-8 pb-8">
        {{-- Hero Header - CBAV style --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-primary-600/20 to-transparent"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500/20 border border-primary-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="building-columns" style="duotone" class="w-4 h-4 text-primary-300" />
                        <span class="text-primary-200 text-xs font-black uppercase tracking-[0.2em]">Detalhes da conta</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                        {{ $account->name }}
                    </h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base md:text-lg leading-relaxed">
                        Detalhes e histórico da conta
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold hover:bg-white/20 transition-colors">
                        <x-icon name="arrow-left" style="duotone" class="w-5 h-5 text-slate-200" />
                        Voltar
                    </a>
                    <a href="{{ route('core.accounts.edit', $account) }}" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white text-slate-900 font-bold hover:bg-slate-100 transition-colors shadow-lg shadow-white/10">
                        <x-icon name="pencil" style="duotone" class="w-5 h-5 text-primary-600" />
                        Editar
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Card visual CBAV style --}}
            <div class="lg:col-span-1">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-700 shadow-xl p-6 h-64 flex flex-col justify-between text-white">
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_50%_50%,white,transparent)]"></div>
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <x-icon name="{{ $cardIcon }}" style="solid" class="w-9 h-9 opacity-90" />
                        <span class="px-3 py-1.5 bg-white/20 rounded-xl text-xs font-bold uppercase backdrop-blur-sm">{{ $account->type === 'checking' ? 'Corrente' : ($account->type === 'savings' ? 'Poupança' : 'Dinheiro') }}</span>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs text-white/70 uppercase tracking-widest mb-1">Saldo</p>
                        <p class="sensitive-value text-3xl font-mono font-black tabular-nums">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] text-white/60 uppercase tracking-[0.2em] mb-0.5">Nome no cartão</p>
                        <p class="font-semibold tracking-wider">{{ Str::upper($account->name) }}</p>
                    </div>
                </div>
            </div>

            {{-- Transações + PRO extras --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                <x-icon name="receipt" style="duotone" class="w-4 h-4" />
                            </div>
                            Últimas transações
                        </h3>
                        <a href="{{ route('core.transactions.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">
                            Nova
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                            <thead class="text-xs font-semibold uppercase bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Descrição</th>
                                    <th scope="col" class="px-6 py-4">Data</th>
                                    <th scope="col" class="px-6 py-4 text-right">Valor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($account->transactions as $index => $transaction)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-slate-800' : 'bg-slate-50/50 dark:bg-slate-700/30' }} hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 rounded-xl {{ $transaction->type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-rose-100 dark:bg-rose-900/30' }}">
                                                    <x-icon name="{{ $transaction->type === 'income' ? 'arrow-down' : 'arrow-up' }}" style="solid" class="w-4 h-4 {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}" />
                                                </div>
                                                <div>
                                                    <p class="font-medium text-slate-900 dark:text-white">{{ $transaction->description }}</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $transaction->category->name ?? 'Geral' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono text-xs">{{ $transaction->date->format('d/m/Y') }}</td>
<td class="px-6 py-4 text-right"><span class="sensitive-value font-mono font-semibold tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                                                <x-icon name="receipt" style="duotone" class="w-14 h-14 opacity-40" />
                                                <p class="font-medium">Nenhuma transação nesta conta</p>
                                                <a href="{{ route('core.transactions.create') }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 font-semibold text-sm hover:underline">Adicionar transação</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- PRO: Quick stats (somente PRO) --}}
                @if($isPro)
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 overflow-hidden relative">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-bl-full -mr-8 -mt-8"></div>
                            <div class="relative px-6 py-4 border-b border-emerald-100 dark:border-emerald-800/30 bg-emerald-50/50 dark:bg-emerald-900/10">
                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                    <x-icon name="arrow-trend-up" style="solid" class="w-4 h-4" />
                                    Entradas
                                    <span class="px-2 py-0.5 text-[10px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                                </p>
                            </div>
                            <p class="sensitive-value p-6 text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tabular-nums">R$ {{ number_format($incomeTotal ?? 0, 2, ',', '.') }}</p>
                        </div>
                        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 overflow-hidden relative">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-rose-50 dark:bg-rose-900/20 rounded-bl-full -mr-8 -mt-8"></div>
                            <div class="relative px-6 py-4 border-b border-rose-100 dark:border-rose-800/30 bg-rose-50/50 dark:bg-rose-900/10">
                                <p class="text-xs font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider flex items-center gap-2">
                                    <x-icon name="arrow-trend-down" style="solid" class="w-4 h-4" />
                                    Saídas
                                    <span class="px-2 py-0.5 text-[10px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                                </p>
                            </div>
                            <p class="sensitive-value p-6 text-2xl font-black text-rose-600 dark:text-rose-400 font-mono tabular-nums">R$ {{ number_format($expenseTotal ?? 0, 2, ',', '.') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
