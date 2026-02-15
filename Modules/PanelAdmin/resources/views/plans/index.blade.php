<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Planos e Limites</x-slot>

    <x-paneladmin::page title="Planos e Limites" subtitle="Limites do plano gratuito e informações do PRO.">
    <form action="{{ route('admin.plans.update') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Plans Setup -->
        <div class="col-span-2">
            <x-paneladmin::card title="Nomes dos planos" subtitle="Exibidos em mensagens de limite e assinatura.">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome do plano gratuito</label>
                            <input type="text" name="plan_free_name" value="{{ old('plan_free_name', $planFreeName) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="Plano Gratuito">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome do plano PRO</label>
                            <input type="text" name="plan_pro_name" value="{{ old('plan_pro_name', $planProName) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="Vertex PRO">
                        </div>
                    </div>
                </div>
            </x-paneladmin::card>

            <x-paneladmin::card title="Limites do Plano Gratuito" subtitle="Defina os recursos disponíveis para usuários gratuitos." class="mt-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Transactions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Receitas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="arrow-up" style="duotone" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_income" value="{{ $limits['income'] }}" class="pl-10 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Despesas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="arrow-down" style="duotone" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_expense" value="{{ $limits['expense'] }}" class="pl-10 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            </div>
                        </div>

                        <!-- Core Entities -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Contas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="wallet" style="duotone" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_account" value="{{ $limits['account'] }}" class="pl-10 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Metas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="bullseye" style="duotone" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_goal" value="{{ $limits['goal'] }}" class="pl-10 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Orçamentos</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="chart-pie" style="duotone" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_budget" value="{{ $limits['budget'] }}" class="pl-10 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Categorias (personalizadas)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    <x-icon name="tags" style="duotone" class="w-4 h-4" />
                                </span>
                                <input type="number" name="limit_free_category" value="{{ $limits['category'] }}" min="0" class="pl-10 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            </div>
                        </div>
                    </div>
                </div>
            </x-paneladmin::card>

            <x-paneladmin::card :title="$planProName . ' — Limites opcionais'" subtitle="Ative para definir limites por recurso no plano PRO (-1 = Ilimitado)." class="mt-6" x-data="{ proHasLimits: {{ $proHasLimits ? 'true' : 'false' }} }">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <input type="hidden" name="pro_has_limits" :value="proHasLimits ? '1' : '0'">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" x-model="proHasLimits">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-slate-600 peer-focus:ring-2 peer-focus:ring-[#11C76F]/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#11C76F]"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">PRO com limites</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="proHasLimits" x-transition x-cloak style="display: none;">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Receitas (-1 = Ilimitado)</label>
                            <input type="number" name="limit_pro_income" value="{{ $limitsPro['income'] }}" min="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Despesas (-1 = Ilimitado)</label>
                            <input type="number" name="limit_pro_expense" value="{{ $limitsPro['expense'] }}" min="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contas (-1 = Ilimitado)</label>
                            <input type="number" name="limit_pro_account" value="{{ $limitsPro['account'] }}" min="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metas (-1 = Ilimitado)</label>
                            <input type="number" name="limit_pro_goal" value="{{ $limitsPro['goal'] }}" min="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Orçamentos (-1 = Ilimitado)</label>
                            <input type="number" name="limit_pro_budget" value="{{ $limitsPro['budget'] }}" min="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categorias (-1 = Ilimitado)</label>
                            <input type="number" name="limit_pro_category" value="{{ $limitsPro['category'] }}" min="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-2.5 bg-[#11C76F] text-white rounded-xl hover:bg-[#0EA85A] transition-colors font-bold flex items-center">
                            <x-icon name="save" style="duotone" class="w-4 h-4 mr-2" />
                            Salvar Alterações
                        </button>
                    </div>
                </div>
            </x-paneladmin::card>
        </div>

        <!-- Info Card -->
        <div class="col-span-1">
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-sm border border-slate-700 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-primary/20 rounded-full blur-2xl"></div>

                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <x-icon name="crown" style="solid" class="w-5 h-5 mr-2 text-amber-500" />
                    {{ $planProName }}
                </h3>

                <p class="text-slate-300 text-sm mb-6">
                    @if($proHasLimits)
                        Limites configurados por recurso abaixo. Caso contrário, o plano é <strong>Ilimitado</strong> em todos os recursos.
                    @else
                        O plano PRO está <strong>Ilimitado</strong> em todos os recursos. Ative "PRO com limites" para definir tetos por recurso.
                    @endif
                </p>

                <div class="space-y-3">
                    @if($proHasLimits)
                        @php
                            $labels = ['account' => 'Contas', 'income' => 'Receitas', 'expense' => 'Despesas', 'goal' => 'Metas', 'budget' => 'Orçamentos', 'category' => 'Categorias'];
                        @endphp
                        @foreach($labels as $key => $label)
                            <div class="flex items-center text-sm">
                                @if($limitsPro[$key] < 0)
                                    <x-icon name="check-circle" style="solid" class="w-4 h-4 mr-2 text-emerald-400" />
                                    <span>{{ $label }}: Ilimitado</span>
                                @else
                                    <x-icon name="chart-simple" style="solid" class="w-4 h-4 mr-2 text-amber-400" />
                                    <span>{{ $label }}: até {{ $limitsPro[$key] }}</span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center text-sm">
                            <x-icon name="check-circle" style="solid" class="w-4 h-4 mr-2 text-emerald-400" />
                            <span>Contas Ilimitadas</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <x-icon name="check-circle" style="solid" class="w-4 h-4 mr-2 text-emerald-400" />
                            <span>Transações Ilimitadas</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <x-icon name="check-circle" style="solid" class="w-4 h-4 mr-2 text-emerald-400" />
                            <span>Metas & Orçamentos Ilimitados</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <x-icon name="check-circle" style="solid" class="w-4 h-4 mr-2 text-emerald-400" />
                            <span>Categorias Ilimitadas</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </form>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
