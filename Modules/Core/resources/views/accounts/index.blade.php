@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp
<x-paneluser::layouts.master :title="'Minhas Contas'">
    {{-- Header PicPay-style --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-primary/10 dark:bg-primary/15 border border-primary/20 dark:border-primary/30">
                    <x-icon name="building-columns" style="duotone" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                Minhas Contas
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 ml-14">Gerencie seus saldos e cartões com segurança</p>
        </div>
        @can('create', \Modules\Core\Models\Account::class)
            <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <x-icon name="plus" style="solid" class="w-5 h-5" />
                Nova Conta
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-200 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5" />
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 p-1 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-800/30">
                <x-icon name="xmark" style="solid" class="w-5 h-5" />
            </button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-200 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-icon name="triangle-exclamation" style="solid" class="w-5 h-5" />
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 p-1 rounded-lg hover:bg-red-100 dark:hover:bg-red-800/30">
                <x-icon name="xmark" style="solid" class="w-5 h-5" />
            </button>
        </div>
    @endif

    <x-core::limit-status entity="account" label="Contas Ativas" />

    {{-- Saldo consolidado - PicPay card --}}
    <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Saldo consolidado</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1 font-mono tabular-nums">R$ {{ number_format($totalBalance, 2, ',', '.') }}</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl">
                <div class="w-2.5 h-2.5 rounded-full bg-primary-500"></div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $accounts->count() }} conta(s)</span>
            </div>
        </div>
    </div>

    {{-- PRO: Resumo por tipo (oculto para FREE) - Excel-style grid --}}
    @if($isPro && $accounts->count() > 0)
        <div class="mb-6 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Resumo por tipo</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-slate-200 dark:divide-slate-700">
                @php
                    $byType = $accounts->groupBy('type');
                    $typeLabels = ['checking' => 'Corrente', 'savings' => 'Poupança', 'cash' => 'Dinheiro'];
                @endphp
                @foreach($typeLabels as $key => $label)
                    @php $items = $byType->get($key, collect()); $sum = $items->sum('balance'); @endphp
                    @if($items->count() > 0)
                        <div class="p-4">
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $label }}</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white mt-1 font-mono tabular-nums">R$ {{ number_format($sum, 2, ',', '.') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $items->count() }} conta(s)</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Cards de conta - PicPay style --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($accounts as $index => $account)
            @php
                $gradients = [
                    'from-emerald-600 to-teal-700',
                    'from-primary-600 to-emerald-700',
                    'from-slate-600 to-slate-800',
                    'from-teal-600 to-cyan-700',
                    'from-emerald-700 to-green-800',
                    'from-slate-700 to-slate-900',
                ];
                $bgGradient = $gradients[$index % count($gradients)];
                $cardIcon = match($account->type) {
                    'cash' => 'money-bill-wave',
                    'savings' => 'piggy-bank',
                    default => 'credit-card'
                };
            @endphp

            <div class="group relative">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $bgGradient }} shadow-lg h-56 flex flex-col justify-between p-6 text-white transition-all hover:shadow-xl hover:scale-[1.02]">
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_50%_50%,white,transparent)]"></div>
                    <div class="absolute top-0 right-0 w-36 h-36 bg-white/5 rounded-full -mr-16 -mt-16"></div>

                    <div class="relative z-10 flex justify-between items-start">
                        <x-icon name="{{ $cardIcon }}" style="solid" class="w-8 h-8 opacity-90" />
                        <span class="px-2.5 py-1 bg-white/20 rounded-lg text-xs font-bold uppercase tracking-wider backdrop-blur-sm">{{ $account->type === 'checking' ? 'Corrente' : ($account->type === 'savings' ? 'Poupança' : 'Dinheiro') }}</span>
                    </div>

                    <div class="relative z-10">
                        <p class="text-xs text-white/70 uppercase tracking-widest mb-1">Saldo</p>
                        <p class="text-2xl font-mono font-bold tracking-tight tabular-nums">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                    </div>

                    <div class="relative z-10 flex justify-between items-end">
                        <div>
                            <p class="text-[10px] text-white/60 uppercase tracking-[0.2em] mb-0.5">Nome no cartão</p>
                            <p class="font-semibold tracking-wider text-sm">{{ Str::upper($account->name) }}</p>
                        </div>
                        <x-icon name="cc-visa" style="brands" class="w-10 h-10 opacity-80" />
                    </div>

                    {{-- Hover actions --}}
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 z-20 rounded-2xl">
                        <a href="{{ route('core.accounts.show', $account) }}" class="p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors" title="Ver">
                            <x-icon name="eye" style="solid" class="w-5 h-5" />
                        </a>
                        <a href="{{ route('core.accounts.edit', $account) }}" class="p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors" title="Editar">
                            <x-icon name="pencil" style="solid" class="w-5 h-5" />
                        </a>
                        <form action="{{ route('core.accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta conta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 bg-red-500/40 hover:bg-red-500/60 rounded-xl text-white transition-colors" title="Excluir">
                                <x-icon name="trash" style="solid" class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-primary/10 dark:bg-primary/20 mb-6">
                        <x-icon name="wallet" style="duotone" class="w-10 h-10 text-primary-500 dark:text-primary-400" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Nenhuma conta cadastrada</h3>
                    <p class="text-slate-600 dark:text-slate-400 max-w-md mx-auto mb-6">Adicione contas, poupança ou dinheiro em espécie para começar a controlar seu patrimônio.</p>
                    @can('create', \Modules\Core\Models\Account::class)
                        <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-semibold rounded-xl shadow-sm transition-colors">
                            <x-icon name="plus" style="solid" class="w-5 h-5" />
                            Adicionar primeira conta
                        </a>
                    @endcan
                </div>
            </div>
        @endforelse

        @if($accounts->count() > 0 && $accounts->count() < 20)
            <a href="{{ route('core.accounts.create') }}" class="flex flex-col items-center justify-center min-h-[224px] border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl hover:border-primary-500 dark:hover:border-primary-500 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all group">
                <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-700 group-hover:bg-primary/20 flex items-center justify-center mb-3 transition-colors">
                    <x-icon name="plus" style="solid" class="w-7 h-7 text-slate-500 group-hover:text-primary-500 dark:text-slate-400 dark:group-hover:text-primary-400 transition-colors" />
                </div>
                <span class="font-semibold text-slate-600 dark:text-slate-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Nova conta</span>
            </a>
        @endif
    </div>
</x-paneluser::layouts.master>
