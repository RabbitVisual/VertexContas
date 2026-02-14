<x-paneluser::layouts.master :title="'Limite Atingido'">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="p-8 sm:p-10 text-center">

                <!-- Icon -->
                <div class="mx-auto h-24 w-24 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full flex items-center justify-center mb-6 ring-8 ring-purple-50 dark:ring-purple-900/10">
                    <x-icon name="right-left" style="duotone" class="h-10 w-10 text-purple-600 dark:text-purple-500" />
                </div>

                <!-- Headline -->
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                    @if($type === 'income')
                        Maximize seus Ganhos
                    @else
                        Controle Total de Gastos
                    @endif
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mb-8">
                    @if($type === 'income')
                        Você atingiu o limite de <strong>5 receitas</strong> mensais do plano Grátis. Para registrar todos os seus ganhos, você precisa do <strong>Vertex Pro</strong>.
                    @else
                        Você atingiu o limite de <strong>5 despesas</strong> mensais do plano Grátis. Para um controle financeiro completo, você precisa do <strong>Vertex Pro</strong>.
                    @endif
                </p>

                <!-- Comparison -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto mb-10">
                    <!-- Free Plan Card -->
                    <div class="bg-gray-50 dark:bg-slate-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-600 opacity-75">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Plano Atual</h3>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                             @if($type === 'income')
                                5 Receitas/mês
                            @else
                                5 Despesas/mês
                            @endif
                        </div>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2 mt-4 text-left">
                            <li class="flex items-center text-red-500">
                                <x-icon name="xmark" style="solid" class="w-4 h-4 mr-2" /> Registro limitado
                            </li>
                            <li class="flex items-center text-red-500">
                                <x-icon name="xmark" style="solid" class="w-4 h-4 mr-2" /> Histórico curto
                            </li>
                        </ul>
                    </div>

                    <!-- Pro Plan Card -->
                    <div class="bg-gradient-to-b from-primary/5 to-transparent rounded-xl p-6 border-2 border-primary relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-primary text-white text-xs font-bold px-3 py-1 rounded-bl-lg">RECOMENDADO</div>
                        <h3 class="text-sm font-semibold text-primary uppercase tracking-wider mb-2">Vertex Pro</h3>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ilimitado</div>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2 mt-4 text-left">
                            <li class="flex items-center text-emerald-600 dark:text-emerald-400">
                                <x-icon name="check" style="solid" class="w-4 h-4 mr-2" /> <strong>Transações infinitas</strong>
                            </li>
                            <li class="flex items-center text-emerald-600 dark:text-emerald-400">
                                <x-icon name="check" style="solid" class="w-4 h-4 mr-2" /> Categorias personalizadas
                            </li>
                            <li class="flex items-center text-emerald-600 dark:text-emerald-400">
                                <x-icon name="check" style="solid" class="w-4 h-4 mr-2" /> Anexos e comprovantes
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('user.subscription.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-lg text-white bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 shadow-lg shadow-primary/20 transition-all transform hover:scale-105">
                        <x-icon name="rocket" style="solid" class="w-5 h-5 mr-2" />
                        Desbloquear Transações
                    </a>

                    <a href="{{ route('core.transactions.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Voltar
                    </a>
                </div>

                <p class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                    Garantia de 7 dias ou seu dinheiro de volta. Cancelamento a qualquer momento.
                </p>

            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
