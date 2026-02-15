<x-paneluser::layouts.master :title="'Relatórios'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                    <a href="{{ ($isPro = auth()->user()->hasRole('pro_user') || auth()->user()->hasRole('admin')) && Route::has('core.dashboard') ? route('core.dashboard') : route('paneluser.index') }}" class="hover:underline">Dashboard</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
                    <span class="text-gray-400 dark:text-gray-500">Relatórios</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Seus <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Relatórios</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Analise suas finanças com relatórios detalhados e exporte quando precisar.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                        <x-icon name="chart-line" style="solid" class="w-3.5 h-3.5" /> Comparativos
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 text-xs font-semibold">
                        <x-icon name="lightbulb" style="solid" class="w-3.5 h-3.5" /> Dicas de uso
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs font-semibold">
                        <x-icon name="file-export" style="solid" class="w-3.5 h-3.5" /> Exportação
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0" role="region" aria-label="Resumo">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 rounded-2xl {{ $isPro ? 'bg-emerald-600/10 dark:bg-emerald-500/20' : 'bg-amber-500/10 dark:bg-amber-500/20' }} flex items-center justify-center {{ $isPro ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }} shrink-0">
                        <x-icon name="{{ $isPro ? 'chart-simple' : 'eye' }}" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">
                            {{ $isPro ? 'Vertex PRO' : 'Visualização básica' }}
                        </p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white leading-tight">
                            {{ $isPro ? ($transactionCount ?? 0) . ' transações' : 'Apenas visualização' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Cards --}}
    <div class="grid grid-cols-1 gap-6">
        {{-- Fluxo de Caixa --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 shadow-sm hover:shadow-xl">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-[0.2em]">Relatório</span>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="money-bill-trend-up" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors mb-2">Fluxo de Caixa</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Visualize receitas e despesas ao longo do tempo para entender seu saldo mensal.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('core.reports.cashflow') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                        Acessar
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        {{-- Ranking de Categorias --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-purple-500/30 shadow-sm hover:shadow-xl">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-purple-600 dark:text-purple-500 uppercase tracking-[0.2em]">Relatório</span>
                    <div class="w-14 h-14 rounded-2xl bg-purple-600/10 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0">
                        <x-icon name="chart-pie" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors mb-2">Ranking de Categorias</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Descubra para onde seu dinheiro está indo com despesas agrupadas por categoria.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('core.reports.categories') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-purple-500/20">
                        Acessar
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        {{-- Extrato Vertex (PRO only) --}}
        @if($isPro)
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-teal-500/30 shadow-sm hover:shadow-xl">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-teal-600 dark:text-teal-500 uppercase tracking-[0.2em]">Vertex PRO</span>
                    <div class="w-14 h-14 rounded-2xl bg-teal-600/10 dark:bg-teal-500/20 flex items-center justify-center text-teal-600 dark:text-teal-400 shrink-0">
                        <x-icon name="building-columns" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors mb-2">Extrato Vertex</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Lista de transações com data, descrição, débito, crédito e saldo acumulado. Estilo extrato de conta.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('core.reports.extrato') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-teal-500/20">
                        Acessar
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm opacity-90">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.2em]">Vertex PRO</span>
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                        <x-icon name="building-columns" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Extrato Vertex</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Relatório exclusivo para Vertex PRO. Assine para acessar extrato completo com filtros e exportação.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('user.subscription.index') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg">
                        <x-icon name="crown" style="solid" class="w-4 h-4" />
                        Fazer Upgrade
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Guia rápido: o que cada relatório faz --}}
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 p-6 sm:p-8 shadow-sm">
        <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <x-icon name="circle-question" style="duotone" class="w-5 h-5 text-emerald-600" />
            Guia rápido dos relatórios
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="p-4 rounded-xl bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30">
                <span class="font-bold text-emerald-700 dark:text-emerald-400 block mb-2">Fluxo de Caixa</span>
                <p class="text-slate-600 dark:text-slate-400">Mostra receitas e despesas ao longo do tempo. Use para ver tendências, taxa de poupança e identificar meses atípicos.</p>
            </div>
            <div class="p-4 rounded-xl bg-purple-50/50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-800/30">
                <span class="font-bold text-purple-700 dark:text-purple-400 block mb-2">Ranking de Categorias</span>
                <p class="text-slate-600 dark:text-slate-400">Para onde vai seu dinheiro? Despesas agrupadas por categoria para você priorizar onde cortar ou ajustar.</p>
            </div>
            <div class="p-4 rounded-xl bg-teal-50/50 dark:bg-teal-900/10 border border-teal-100 dark:border-teal-800/30">
                <span class="font-bold text-teal-700 dark:text-teal-400 block mb-2">Extrato Vertex</span>
                <p class="text-slate-600 dark:text-slate-400">Lista transação por transação com saldo acumulado. Ideal para conferir contra o banco ou exportar.</p>
            </div>
        </div>
    </div>

    @if(!$isPro)
    <div class="relative overflow-hidden bg-slate-900 rounded-3xl p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-purple-600/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-indigo-600/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <span class="bg-gradient-to-r from-purple-400 to-pink-400 text-transparent bg-clip-text font-black text-sm uppercase tracking-widest mb-2 block">Vertex Pro</span>
                <h3 class="text-2xl font-black text-white mb-2">Relatórios Avançados e Exportação</h3>
                <p class="text-slate-400 max-w-xl">Desbloqueie exportação PDF/CSV, extrato bancário, filtros avançados e relatórios exclusivos.</p>
            </div>
            <a href="{{ route('user.subscription.index') }}" class="whitespace-nowrap inline-flex items-center px-8 py-4 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-50 transition-all transform hover:scale-105 shadow-lg">
                <x-icon name="crown" style="solid" class="text-purple-600 mr-2" />
                Fazer Upgrade
            </a>
        </div>
    </div>
    @endif
</div>
</x-paneluser::layouts.master>
