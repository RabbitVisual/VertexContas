@php
    $isPro = auth()->user()?->isPro() ?? false;
    $dashboardRoute = ($isPro && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index');
@endphp
<x-paneluser::layouts.master :title="'Minhas Contas'">
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
                        <span class="text-gray-400 dark:text-gray-500">Minhas Contas</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Minhas <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Contas</span></h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Cada conta tem seu saldo. As transações do <a href="{{ route('core.transactions.index') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">Extrato</a> movimentam o saldo da conta escolhida.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    @can('create', \Modules\Core\Models\Account::class)
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                                <x-icon name="plus" style="solid" class="w-5 h-5" />
                                Nova conta
                            </a>
                        @endif
                    @endcan
                    <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                        <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                        Voltar
                    </a>
                </div>
            </div>

            {{-- Stats no hero (saldo + qtd) --}}
            <div class="relative z-10 mt-8 pt-8 border-t border-gray-200 dark:border-white/5 flex flex-wrap items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Saldo consolidado</p>
                        <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white tabular-nums"><x-core::financial-value :value="$totalBalance" /></p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                        <x-icon name="building-columns" style="duotone" class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $accounts->count() }} conta(s)</span>
                    </div>
                    <x-core::limit-status entity="account" label="Contas ativas" :compact="true" />
                </div>
            </div>
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
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="rounded-2xl border border-red-200 dark:border-red-800/50 bg-red-50 dark:bg-red-900/10 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-red-600 dark:text-red-400">
                        <x-icon name="triangle-exclamation" style="solid" class="w-5 h-5" />
                    </div>
                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ session('error') }}</span>
                </div>
                <button type="button" @click="show = false" class="p-2 rounded-lg hover:bg-red-500/20 text-gray-500 hover:text-gray-700 transition-colors">
                    <x-icon name="xmark" style="solid" class="w-5 h-5" />
                </button>
            </div>
        @endif

        {{-- Dica: Como funciona --}}
        <div class="rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm">
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1">Como funcionam as contas no Vertex Contas</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Cada <strong>conta</strong> (corrente, poupança ou dinheiro em espécie) tem um saldo. Ao registrar uma <strong>transação</strong> no Extrato, você escolhe a conta de origem ou destino; o saldo é atualizado automaticamente. Use contas para separar dinheiro do dia a dia, reservas e gastos por finalidade.</p>
                </div>
            </div>
        </div>

        {{-- Pro: Resumo por tipo --}}
        @if($isPro && $accounts->count() > 0)
            <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex items-center gap-3 bg-amber-500/5 dark:bg-amber-500/10">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Resumo por tipo</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Vertex Pro</p>
                    </div>
                    <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-200 dark:divide-white/5">
                    @php
                        $byType = $accounts->groupBy('type');
                        $typeLabels = ['checking' => 'Corrente', 'savings' => 'Poupança', 'cash' => 'Dinheiro'];
                    @endphp
                    @foreach($typeLabels as $key => $label)
                        @php $items = $byType->get($key, collect()); $sum = $items->sum('balance'); @endphp
                        @if($items->count() > 0)
                            <div class="p-6">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $label }}</p>
                                <p class="sensitive-value text-xl font-black text-gray-900 dark:text-white mt-1 font-mono tabular-nums"><x-core::financial-value :value="$sum" /></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $items->count() }} conta(s)</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Cards: Pro = layout cartão virtual (flip); Free = cartão físico simples --}}
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
                @endphp
                @if($isPro)
                    <div class="flex justify-center md:justify-start">
                        <x-core::account-card-pro :account="$account" :gradient="$bgGradient" :show-actions="true" />
                    </div>
                @else
                    @php
                        $cardIcon = match($account->type) {
                            'cash' => 'money-bill-wave',
                            'savings' => 'piggy-bank',
                            default => 'credit-card'
                        };
                    @endphp
                    <div class="group relative">
                        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br {{ $bgGradient }} shadow-lg h-60 flex flex-col justify-between p-6 text-white transition-all hover:shadow-xl hover:scale-[1.02]">
                            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_50%_50%,white,transparent)]"></div>
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>

                            <div class="relative z-10 flex justify-between items-start">
                                <x-icon name="{{ $cardIcon }}" style="solid" class="w-9 h-9 opacity-90" />
                                <span class="px-3 py-1.5 bg-white/20 rounded-xl text-xs font-bold uppercase tracking-wider backdrop-blur-sm">{{ $account->type === 'checking' ? 'Corrente' : ($account->type === 'savings' ? 'Poupança' : 'Dinheiro') }}</span>
                            </div>

                            <div class="relative z-10">
                                <p class="text-xs text-white/70 uppercase tracking-widest mb-1">Saldo</p>
                                <p class="sensitive-value text-2xl font-mono font-black tracking-tight tabular-nums"><x-core::financial-value :value="$account->balance" /></p>
                            </div>

                            <div class="relative z-10 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] text-white/60 uppercase tracking-[0.2em] mb-0.5">Nome no cartão</p>
                                    <p class="font-semibold tracking-wider text-sm">{{ Str::upper($account->name) }}</p>
                                </div>
                                <x-icon name="cc-visa" style="brands" class="w-10 h-10 opacity-80" />
                            </div>

                            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 z-20 rounded-3xl">
                                <a href="{{ route('core.accounts.show', $account) }}" class="p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors" title="Ver">
                                    <x-icon name="eye" style="solid" class="w-5 h-5" />
                                </a>
                                @if(!($inspectionReadOnly ?? false))
                                    <a href="{{ route('core.accounts.edit', $account) }}" class="p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors" title="Editar">
                                        <x-icon name="pencil" style="solid" class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('core.accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta conta? Ela não pode ter transações.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-3 bg-red-500/40 hover:bg-red-500/60 rounded-xl text-white transition-colors" title="Excluir">
                                            <x-icon name="trash-can" style="solid" class="w-5 h-5" />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-24 text-center rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-gray-950/50">
                    <div class="w-24 h-24 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-700 mb-6 shadow-sm border border-gray-100 dark:border-white/5">
                        <x-icon name="wallet" style="duotone" class="w-12 h-12 opacity-40 dark:opacity-20" />
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 leading-tight">Nenhuma conta cadastrada</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Adicione contas (corrente, poupança ou dinheiro) para começar a controlar saldos e lançar transações no Extrato.</p>
                    @can('create', \Modules\Core\Models\Account::class)
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                                <x-icon name="plus" style="solid" class="w-5 h-5" />
                                Adicionar primeira conta
                            </a>
                        @endif
                    @endcan
                </div>
            @endforelse

            @if($accounts->count() > 0 && $accounts->count() < 20 && !($inspectionReadOnly ?? false))
                @can('create', \Modules\Core\Models\Account::class)
                    <a href="{{ route('core.accounts.create') }}" class="flex flex-col items-center justify-center min-h-[240px] border-2 border-dashed border-gray-300 dark:border-white/10 rounded-3xl hover:border-emerald-500/50 hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-all group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 group-hover:bg-emerald-500/10 flex items-center justify-center mb-4 transition-colors">
                            <x-icon name="plus" style="solid" class="w-8 h-8 text-gray-500 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" />
                        </div>
                        <span class="font-semibold text-gray-500 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Nova conta</span>
                    </a>
                @endcan
            @endif
        </div>
    </div>
</x-paneluser::layouts.master>
