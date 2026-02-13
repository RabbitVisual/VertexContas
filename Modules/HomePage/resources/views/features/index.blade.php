<x-homepage::layouts.master title="Funcionalidades - Vertex Contas">
    <x-homepage::layouts.navbar />
    <!-- Hero Section -->
    <div class="relative bg-white dark:bg-slate-900 pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-500/10 via-background to-background"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-500 text-sm font-medium mb-6 animate-fade-in-down">
                    <x-icon name="layer-group" style="duotone" />
                    <span>Gestão Completa</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-6 tracking-tight animate-fade-in-up">
                    O controle total <br>das suas <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Finanças Diárias</span>
                </h1>

                <p class="text-lg text-slate-600 dark:text-slate-300 mb-10 leading-relaxed max-w-2xl mx-auto animate-fade-in-up delay-75">
                    Centralize receitas, despesas e contas em um único lugar. O Vertex Contas oferece ferramentas poderosas para você nunca mais perder o controle do seu dinheiro.
                </p>
            </div>
        </div>
    </div>

    <!-- Feature: Receitas -->
    <div class="py-20 bg-slate-50 dark:bg-slate-950/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-1/2">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-green-500/20 rounded-3xl blur-2xl transform rotate-3 group-hover:rotate-0 transition-all duration-500"></div>
                        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-xl">
                            <!-- Visual representation (Mockup) -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-green-600 dark:text-green-500">
                                        <x-icon name="plus" size="text-xl" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 dark:text-white">Salário Mensal</div>
                                        <div class="text-xs text-slate-400">Receita Recorrente</div>
                                    </div>
                                </div>
                                <span class="font-bold text-green-600 dark:text-green-500">+ R$ 5.500,00</span>
                            </div>
                            <div class="space-y-3">
                                <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full w-full"></div>
                                <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full w-3/4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 space-y-6">
                    <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center text-green-600 dark:text-green-500">
                        <x-icon name="money-bill-trend-up" size="text-2xl" />
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Gestão de Receitas</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Registre todas as suas fontes de renda, sejam elas fixas (como salários) ou variáveis (freelances, vendas).
                        Crie receitas recorrentes para automatizar o lançamento mensal e tenha previsibilidade do seu fluxo de caixa.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-green-500" /> Categorização inteligente
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-green-500" /> Repetição automática (mensal, semanal)
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-green-500" /> Anexos de comprovantes
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature: Despesas -->
    <div class="py-20 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
                <div class="w-full lg:w-1/2">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-red-500/20 rounded-3xl blur-2xl transform -rotate-3 group-hover:rotate-0 transition-all duration-500"></div>
                        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-xl">
                            <!-- Visual representation -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-red-600 dark:text-red-500">
                                        <x-icon name="cart-shopping" size="text-xl" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 dark:text-white">Supermercado</div>
                                        <div class="text-xs text-slate-400">Cartão de Crédito</div>
                                    </div>
                                </div>
                                <span class="font-bold text-red-600 dark:text-red-500">- R$ 450,20</span>
                            </div>
                            <div class="space-y-3">
                                <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full w-full"></div>
                                <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full w-2/3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 space-y-6">
                    <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center text-red-600 dark:text-red-500">
                        <x-icon name="receipt" size="text-2xl" />
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Controle de Despesas</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Saiba exatamente para onde vai cada centavo. Registre gastos à vista, parcelados ou recorrentes.
                        O sistema calcula automaticamente o impacto de compras parceladas nos meses futuros.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-red-500" /> Gestão de parcelamentos
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-red-500" /> Alertas de vencimento
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-red-500" /> Tags personalizadas
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature: Contas Bancarias -->
    <div class="py-20 bg-slate-50 dark:bg-slate-950/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                 <div class="w-full lg:w-1/2">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-blue-500/20 rounded-3xl blur-2xl transform rotate-2 group-hover:rotate-0 transition-all duration-500"></div>
                        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-xl">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                                    <div class="flex items-center gap-3">
                                        <x-icon name="building-columns" class="text-blue-500" />
                                        <span class="font-medium dark:text-white">Nubank</span>
                                    </div>
                                    <span class="font-bold dark:text-white">R$ 1.250,00</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                                    <div class="flex items-center gap-3">
                                        <x-icon name="building-columns" class="text-orange-500" />
                                        <span class="font-medium dark:text-white">Inter</span>
                                    </div>
                                    <span class="font-bold dark:text-white">R$ 5.420,00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 space-y-6">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-500">
                        <x-icon name="wallet" size="text-2xl" />
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Contas e Saldos</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Mantenha o saldo de todas as suas contas atualizado. O Vertex Contas suporta contas correntes, poupança, investimentos e dinheiro em espécie.
                        Faça transferências entre contas e acompanhe a evolução do seu patrimônio.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-blue-500" /> Múltiplas carteiras
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-blue-500" /> Transferências internas
                        </li>
                        <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <x-icon name="check" class="text-blue-500" /> Conciliação de saldo
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
