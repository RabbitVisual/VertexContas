<x-homepage::layouts.master title="Relatórios - Vertex Contas">
    <x-homepage::layouts.navbar />
    <!-- Hero -->
    <div class="relative bg-white dark:bg-slate-900 pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-purple-500/10 via-background to-background"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 text-purple-500 text-sm font-medium mb-6 animate-fade-in-down">
                <x-icon name="chart-simple" style="duotone" />
                <span>Inteligência de Dados</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-6 tracking-tight animate-fade-in-up">
                Visualize seus dados com <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">Clareza</span>
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-300 mb-10 leading-relaxed max-w-2xl mx-auto animate-fade-in-up delay-75">
                Gráficos interativos, relatórios detalhados e exportação de dados para você analisar sua saúde financeira de todos os ângulos.
            </p>
        </div>
    </div>

    <!-- Analytics -->
    <div class="py-20 bg-slate-50 dark:bg-slate-950/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Chart Type 1 -->
                <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6">
                        <x-icon name="chart-pie" />
                    </div>
                    <h3 class="text-xl font-bold dark:text-white mb-3">Distribuição de Gastos</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Entenda quais categorias consomem mais do seu orçamento através de gráficos de pizza intuitivos e interativos.
                    </p>
                </div>

                <!-- Chart Type 2 -->
                 <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6">
                        <x-icon name="chart-column" />
                    </div>
                    <h3 class="text-xl font-bold dark:text-white mb-3">Evolução Mensal</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Compare seus ganhos e gastos mês a mês para identificar tendências sazonais e ajustar seu comportamento.
                    </p>
                </div>

                <!-- Chart Type 3 -->
                 <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center text-pink-600 dark:text-pink-400 mb-6">
                        <x-icon name="file-arrow-down" />
                    </div>
                    <h3 class="text-xl font-bold dark:text-white mb-3">Exportação de Dados</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Precisa dos dados brutos? Exporte todos os seus lançamentos e relatórios para PDF e Excel (CSV) a qualquer momento.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Note -->
    <div class="py-20 bg-white dark:bg-slate-900">
        <div class="max-w-4xl mx-auto px-4 text-center">
             <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-sm font-medium mb-6">
                <x-icon name="lock" style="duotone" />
                <span>Privacidade Garantida</span>
            </div>
            <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-6">Seus relatórios são apenas seus</h2>
            <p class="text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto">
                Toda a geração de inteligência e processamento de dados acontece localmente no seu dispositivo.
                Nossos algoritmos analisam suas finanças sem nunca enviar informações para servidores externos.
            </p>
        </div>
    </div>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
