<x-homepage::layouts.master title="Metas e Objetivos - Vertex Contas">
    <x-homepage::layouts.navbar />
    <!-- Hero -->
    <div class="relative bg-white dark:bg-slate-900 pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-emerald-500/10 via-background to-background"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-sm font-medium mb-6 animate-fade-in-down">
                <x-icon name="bullseye-arrow" style="duotone" />
                <span>Planejamento Futuro</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-6 tracking-tight animate-fade-in-up">
                Transforme sonhos em <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400">Realidade</span>
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-300 mb-10 leading-relaxed max-w-2xl mx-auto animate-fade-in-up delay-75">
                Defina objetivos claros, acompanhe seu progresso e estabeleça orçamentos para garantir que você chegue onde deseja.
            </p>
        </div>
    </div>

    <!-- Metas -->
    <div class="py-20 bg-slate-50 dark:bg-slate-950/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-1/2">
                    <!-- Progress Card Mockup -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <div class="text-sm text-slate-400 uppercase font-bold tracking-wider mb-1">Viagem Europa</div>
                                <div class="text-3xl font-black text-slate-800 dark:text-white">R$ 8.500 <span class="text-lg text-slate-400 font-normal">/ 15.000</span></div>
                            </div>
                            <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-500">
                                <x-icon name="plane" />
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 mb-2">
                            <div class="bg-emerald-500 h-3 rounded-full" style="width: 56%"></div>
                        </div>
                        <div class="text-right text-sm font-bold text-emerald-500">56% Concluído</div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 space-y-6">
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Metas Financeiras</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Quer comprar um carro? Fazer uma viagem? Criar sua reserva de emergência?
                        O Módulo de Metas permite criar objetivos com valor alvo e data limite. O sistema calcula quanto você precisa economizar por mês para atingir o objetivo no prazo.
                    </p>
                    <ul class="space-y-3">
                         <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-emerald-500" /> Cálculo automático de aporte mensal
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-emerald-500" /> Projeção de conclusão
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Orçamentos -->
    <div class="py-20 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
                 <div class="w-full lg:w-1/2">
                    <!-- Budget Mockup -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-xl">
                        <div class="mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">
                            <h3 class="font-bold text-lg dark:text-white flex items-center gap-2">
                                <x-icon name="utensils" class="text-orange-500"/> Alimentação
                            </h3>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-500">Gasto: R$ 850,00</span>
                            <span class="text-slate-500">Limite: R$ 1.000,00</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 relative">
                            <div class="bg-orange-500 h-3 rounded-full" style="width: 85%"></div>
                        </div>
                        <div class="mt-4 p-3 bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 text-xs rounded-lg flex items-center gap-2">
                            <x-icon name="triangle-exclamation" /> Atenção: 85% do limite atingido.
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 space-y-6">
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Orçamentos (Budgets)</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                         Defina limites de gastos para cada categoria. O sistema monitora seus lançamentos em tempo real e avisa quando você está perto de estourar o orçamento definido.
                    </p>
                    <ul class="space-y-3">
                         <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-emerald-500" /> Teto de gastos por categoria
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-emerald-500" /> Alertas visuais de consumo
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
