<x-paneluser::layouts.master :title="'Relatórios'">
    <div class="mb-8">
        <h2 class="font-black text-3xl text-slate-800 dark:text-white flex items-center">
            <x-icon name="chart-simple" style="solid" class="text-primary mr-3" /> Relatórios
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-2">Analise suas finanças com relatórios detalhados.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-['Poppins']">
        <!-- CashFlow Report -->
        <a href="{{ route('core.reports.cashflow') }}" class="group block bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-6">
                <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <x-icon name="money-bill-trend-up" style="duotone" class="text-3xl" />
                </div>
                <div class="bg-slate-100 dark:bg-slate-700 rounded-full p-2 group-hover:bg-primary group-hover:text-white transition-colors">
                    <x-icon name="arrow-right" style="solid" class="w-5 h-5" />
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2 group-hover:text-primary transition-colors">Fluxo de Caixa</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Visualize suas receitas e despesas ao longo do tempo para entender melhor seu saldo mensal.</p>
        </a>

        <!-- Category Ranking Report -->
        <a href="{{ route('core.reports.categories') }}" class="group block bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-6">
                <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                    <x-icon name="chart-pie" style="duotone" class="text-3xl" />
                </div>
                <div class="bg-slate-100 dark:bg-slate-700 rounded-full p-2 group-hover:bg-primary group-hover:text-white transition-colors">
                    <x-icon name="arrow-right" style="solid" class="w-5 h-5" />
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2 group-hover:text-primary transition-colors">Ranking de Categorias</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Descubra para onde seu dinheiro está indo com um ranking detalhado de despesas por categoria.</p>
        </a>
    </div>

    @if(!auth()->user()->hasRole('pro_user') && !auth()->user()->hasRole('admin'))
        <!-- Pro Banner -->
        <div class="mt-12 relative overflow-hidden bg-slate-900 rounded-3xl p-8 shadow-2xl">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-purple-600/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-indigo-600/20 blur-3xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <span class="bg-gradient-to-r from-purple-400 to-pink-400 text-transparent bg-clip-text font-black text-sm uppercase tracking-widest mb-2 block">Vertex Pro</span>
                    <h3 class="text-2xl font-black text-white mb-2">Relatórios Avançados e Exportação</h3>
                    <p class="text-slate-400 max-w-xl">
                        Desbloqueie exportação para PDF e CSV, filtros avançados e relatórios exclusivos assinando o plano Vertex Pro.
                    </p>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="whitespace-nowrap inline-flex items-center px-8 py-4 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-50 transition-all transform hover:scale-105 shadow-lg">
                    <x-icon name="crown" style="solid" class="text-purple-600 mr-2" />
                    Fazer Upgrade
                </a>
            </div>
        </div>
    @endif
</x-paneluser::layouts.master>
