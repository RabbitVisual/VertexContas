@php
    $isPro = auth()->user()?->isPro() ?? false;
    $cardIcon = match($account->type) {
        'cash' => 'money-bill-wave',
        'savings' => 'piggy-bank',
        default => 'credit-card'
    };
    $dashboardRoute = ($isPro && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index');
@endphp
<x-paneluser::layouts.master :title="$account->name">
    <div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                        <span>Financeiro</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <a href="{{ route('core.accounts.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Contas</a>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">{{ $account->name }}</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">{{ $account->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Detalhes e últimas transações desta conta.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                        <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                        Voltar
                    </a>
                    @if(!($inspectionReadOnly ?? false))
                        <a href="{{ route('core.accounts.edit', $account) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                            <x-icon name="pencil" style="solid" class="w-4 h-4" />
                            Editar
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Card: Pro = layout cartão virtual (flip); Free = cartão físico --}}
            <div class="lg:col-span-1 flex justify-center lg:justify-start">
                @if($isPro)
                    <x-core::account-card-pro :account="$account" gradient="from-emerald-600 to-teal-700" :show-actions="false" />
                @else
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-700 shadow-xl p-6 h-64 w-full max-w-[340px] flex flex-col justify-between text-white">
                        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_50%_50%,white,transparent)]"></div>
                        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <x-icon name="{{ $cardIcon }}" style="solid" class="w-9 h-9 opacity-90" />
                            <span class="px-3 py-1.5 bg-white/20 rounded-xl text-xs font-bold uppercase backdrop-blur-sm">{{ $account->type === 'checking' ? 'Corrente' : ($account->type === 'savings' ? 'Poupança' : 'Dinheiro') }}</span>
                        </div>
                        <div class="relative z-10">
                            <p class="text-xs text-white/70 uppercase tracking-widest mb-1">Saldo</p>
                            <p class="sensitive-value text-3xl font-mono font-black tabular-nums"><x-core::financial-value :value="$account->balance" /></p>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[10px] text-white/60 uppercase tracking-[0.2em] mb-0.5">Nome no cartão</p>
                            <p class="font-semibold tracking-wider">{{ Str::upper($account->name) }}</p>
                        </div>
                        <div class="relative z-10 flex justify-end">
                            <x-icon name="cc-visa" style="brands" class="w-10 h-10 opacity-80" />
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex flex-wrap items-center justify-between gap-4 bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <x-icon name="receipt" style="duotone" class="w-5 h-5" />
                            </div>
                            Últimas transações
                        </h3>
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                <x-icon name="plus" style="solid" class="w-4 h-4" />
                                Nova transação
                            </a>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                            <thead class="text-xs font-bold uppercase bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Descrição</th>
                                    <th scope="col" class="px-6 py-4">Data</th>
                                    <th scope="col" class="px-6 py-4 text-right">Valor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                @forelse($account->transactions as $index => $transaction)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-900/30' : 'bg-gray-50/50 dark:bg-gray-800/30' }} hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $transaction->type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' }}">
                                                    <x-icon name="{{ $transaction->type === 'income' ? 'arrow-trend-up' : 'arrow-trend-down' }}" style="solid" class="w-5 h-5" />
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900 dark:text-white">{{ $transaction->description }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->category->name ?? 'Geral' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400 font-mono text-xs">{{ $transaction->date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="sensitive-value font-mono font-semibold tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }} <x-core::financial-value :value="$transaction->amount" />
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center gap-4 text-gray-500 dark:text-gray-400">
                                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                                                    <x-icon name="receipt" style="duotone" class="w-8 h-8 opacity-50" />
                                                </div>
                                                <p class="font-medium">Nenhuma transação nesta conta</p>
                                                @if(!($inspectionReadOnly ?? false))
                                                    <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                                        <x-icon name="plus" style="solid" class="w-4 h-4" />
                                                        Adicionar transação
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($isPro)
                    <div class="grid grid-cols-2 gap-6">
                        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 overflow-hidden">
                            <div class="px-6 py-4 border-b border-emerald-200 dark:border-emerald-800/30 bg-emerald-50/50 dark:bg-emerald-900/10 flex items-center gap-2">
                                <x-icon name="arrow-trend-up" style="duotone" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Entradas</span>
                                <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                            </div>
                            <p class="sensitive-value p-6 text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tabular-nums"><x-core::financial-value :value="$incomeTotal ?? 0" /></p>
                        </div>
                        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 overflow-hidden">
                            <div class="px-6 py-4 border-b border-rose-200 dark:border-rose-800/30 bg-rose-50/50 dark:bg-rose-900/10 flex items-center gap-2">
                                <x-icon name="arrow-trend-down" style="duotone" class="w-5 h-5 text-rose-600 dark:text-rose-400" />
                                <span class="text-xs font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider">Saídas</span>
                                <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                            </div>
                            <p class="sensitive-value p-6 text-2xl font-black text-rose-600 dark:text-rose-400 font-mono tabular-nums"><x-core::financial-value :value="$expenseTotal ?? 0" /></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
