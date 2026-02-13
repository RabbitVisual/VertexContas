@section('title', 'Planos & Limites')

<x-paneladmin::layouts.master>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Plans Setup -->
        <div class="col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Limites do Plano Gratuito</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Defina os recursos disponíveis para usuários gratuitos.</p>
                    </div>
                    <div class="h-10 w-10 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <x-icon name="sliders" class="w-5 h-5" />
                    </div>
                </div>

                <form action="{{ route('admin.plans.update') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Transactions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Receitas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="arrow-up" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_income" value="{{ $limits['income'] }}" class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary transition-colors">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Despesas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="arrow-down" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_expense" value="{{ $limits['expense'] }}" class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary transition-colors">
                            </div>
                        </div>

                        <!-- Core Entities -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Contas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="wallet" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_account" value="{{ $limits['account'] }}" class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary transition-colors">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Metas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="bullseye" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_goal" value="{{ $limits['goal'] }}" class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary transition-colors">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Orçamentos</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="chart-pie" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_budget" value="{{ $limits['budget'] }}" class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary transition-colors">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium flex items-center">
                            <x-icon name="save" class="w-4 h-4 mr-2" />
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-span-1">
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-primary/20 rounded-full blur-2xl"></div>

                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <x-icon name="crown" style="solid" class="w-5 h-5 mr-2 text-amber-500" />
                    Plano Vertex PRO
                </h3>

                <p class="text-slate-300 text-sm mb-6">
                    O plano PRO é hardcoded para ser <strong>Ilimitado</strong> em todos os recursos. As configurações aqui aplicam-se apenas aos usuários com a role <code>free_user</code>.
                </p>

                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <x-icon name="check-circle" class="w-4 h-4 mr-2 text-emerald-400" />
                        <span>Contas Ilimitadas</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <x-icon name="check-circle" class="w-4 h-4 mr-2 text-emerald-400" />
                        <span>Transações Ilimitadas</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <x-icon name="check-circle" class="w-4 h-4 mr-2 text-emerald-400" />
                        <span>Metas & Orçamentos Ilimitados</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneladmin::layouts.master>
