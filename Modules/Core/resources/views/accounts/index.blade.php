<x-paneluser::layouts.master>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-black text-3xl text-slate-800 dark:text-white flex items-center">
                <div class="bg-primary/10 dark:bg-primary/20 p-2 rounded-xl mr-3">
                    <x-icon name="wallet" class="text-primary" />
                </div>
                Minhas Contas
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 ml-14">Gerencie seus saldos e cartões virtuais.</p>
        </div>
        @can('create', \Modules\Core\Models\Account::class)
            <a href="{{ route('core.accounts.create') }}"
               class="group relative inline-flex items-center justify-center px-6 py-3 text-base font-bold text-white transition-all duration-200 bg-primary font-pj rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary hover:bg-primary-dark shadow-lg shadow-primary/30">
                <x-icon name="plus" class="mr-2" /> Nova Conta
            </a>
        @endcan
    </div>

    <!-- Total Balance Highlight -->
    <div class="mb-10">
        <div class="relative overflow-hidden bg-slate-900 rounded-3xl p-8 shadow-2xl">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-primary/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-blue-600/20 blur-3xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span class="text-slate-400 font-medium uppercase tracking-widest text-xs">Saldo Consolidado</span>
                    <h3 class="text-4xl md:text-5xl font-black text-white mt-1">R$ {{ number_format($totalBalance, 2, ',', '.') }}</h3>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full backdrop-blur-sm border border-white/10">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-slate-300 text-sm font-medium">{{ $accounts->count() }} conta(s) ativa(s)</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <x-icon name="circle-check" class="mr-2" /> {{ session('success') }}
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><x-icon name="xmark" /></button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <x-icon name="triangle-exclamation" class="mr-2" /> {{ session('error') }}
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700"><x-icon name="xmark" /></button>
        </div>
    @endif

    <!-- Limit Status Bar -->
    <x-core::limit-status entity="account" label="Contas Ativas" />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($accounts as $index => $account)
            @php
                // Generate a consistent gradient based on ID or index
                $gradients = [
                    'from-slate-800 to-slate-900', // Classic Black
                    'from-blue-600 to-blue-800',   // Corporate Blue
                    'from-emerald-600 to-teal-800', // Emerald Green
                    'from-purple-600 to-indigo-800', // Royal Purple
                    'from-rose-600 to-pink-800',     // Vibrant Rose
                    'from-amber-500 to-orange-700',  // Warm Amber
                ];
                $bgGradient = $gradients[$index % count($gradients)];

                $cardIcon = match($account->type) {
                    'cash' => 'money-bill-wave',
                    'savings' => 'piggy-bank',
                    'investment' => 'chart-line',
                    default => 'credit-card'
                };
            @endphp

            <!-- Virtual Card Component -->
            <div class="group relative perspective-1000 h-64">
                <div class="absolute inset-0 bg-gradient-to-br {{ $bgGradient }} rounded-2xl shadow-xl transform transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-2xl overflow-hidden">
                    <!-- Global Patterns -->
                    <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 p-6 h-full flex flex-col justify-between text-white">
                        <!-- Card Header -->
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-2">
                                <x-icon name="{{ $cardIcon }}" class="text-2xl opacity-80" />
                                <span class="font-bold tracking-wider text-sm opacity-90 uppercase">{{ $account->name }}</span>
                            </div>
                            <x-icon name="wifi" class="rotate-90 text-white/50" />
                        </div>

                        <!-- Chip & Details -->
                        <div class="flex items-center gap-4 my-2">
                             <div class="w-10 h-8 bg-gradient-to-tr from-yellow-200 to-yellow-500 rounded-md border border-yellow-600 opacity-90"></div>
                             <div class="flex flex-col">
                                 <span class="text-xs text-white/60 tracking-widest">Saldo Atual</span>
                                 <span class="text-2xl font-mono font-bold tracking-tight">R$ {{ number_format($account->balance, 2, ',', '.') }}</span>
                             </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-white/60 uppercase tracking-widest mb-0.5">Titular</span>
                                <span class="font-medium tracking-wide text-sm">{{ Str::upper(auth()->user()->name) }}</span>
                            </div>
                            <!-- Actions Overlay Trigger -->
                            <div class="absolute bottom-6 right-6">
                                 <x-icon name="cc-visa" class="text-3xl opacity-80" />
                            </div>
                        </div>
                    </div>

                    <!-- Hover Actions Overlay -->
                     <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4 z-20">
                        <a href="{{ route('core.accounts.show', $account) }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-full text-white backdrop-blur-sm transition-transform hover:scale-110" title="Ver Detalhes">
                            <x-icon name="eye" />
                        </a>
                        <a href="{{ route('core.accounts.edit', $account) }}" class="p-3 bg-blue-500/20 hover:bg-blue-500/40 text-blue-300 rounded-full backdrop-blur-sm transition-transform hover:scale-110 border border-blue-500/30" title="Editar">
                            <x-icon name="pencil" />
                        </a>
                         <form action="{{ route('core.accounts.destroy', $account) }}" method="POST" class="inline">
                             @csrf
                             @method('DELETE')
                             <button type="submit" onclick="return confirm('Excluir esta conta?')" class="p-3 bg-red-500/20 hover:bg-red-500/40 text-red-300 rounded-full backdrop-blur-sm transition-transform hover:scale-110 border border-red-500/30" title="Excluir">
                                <x-icon name="trash" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
             <div class="col-span-full py-12">
                 <!-- Empty State -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-800 mb-6">
                        <x-icon name="wallet" class="text-4xl text-slate-300 dark:text-slate-600" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Sua carteira está vazia</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8">Adicione suas contas bancárias, poupança ou dinheiro em espécie para começar a controlar seu patrimônio.</p>
                    <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-primary/25">
                        <x-icon name="plus" class="mr-2" />
                        Adicionar Primeira Conta
                    </a>
                </div>
             </div>
        @endforelse

        <!-- Add New Card Placeholder -->
        @if($accounts->count() > 0)
            <a href="{{ route('core.accounts.create') }}" class="group relative h-64 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl flex flex-col items-center justify-center hover:border-primary dark:hover:border-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all cursor-pointer">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 group-hover:bg-primary/10 flex items-center justify-center mb-4 transition-colors">
                    <x-icon name="plus" class="text-2xl text-slate-400 group-hover:text-primary transition-colors" />
                </div>
                <span class="font-bold text-slate-500 group-hover:text-primary transition-colors">Adicionar Nova Conta</span>
            </a>
        @endif
    </div>
</x-paneluser::layouts.master>
