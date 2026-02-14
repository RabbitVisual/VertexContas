<x-paneluser::layouts.master :title="'Meus Orçamentos'">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="chart-pie" style="solid" class="text-primary" /> Meus Orçamentos
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('core.budgets.create') }}"
                   class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                    <x-icon name="plus" style="solid" /> Novo Orçamento
                </a>
                <a href="{{ route('core.dashboard') }}"
                   class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                    <x-icon name="arrow-left" style="solid" /> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="py-12 font-['Poppins']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Limit Status Bar -->
            <x-core::limit-status entity="budget" label="Orçamentos Ativos" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($budgets as $budget)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white" style="background-color: {{ $budget->category->color ?? '#6366f1' }}">
                                    <x-icon name="{{ $budget->category->icon ?? 'wallet' }}" style="solid" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-white">{{ $budget->category->name }}</h4>
                                    <p class="text-xs text-slate-500 capitalize">{{ $budget->period }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('core.budgets.edit', $budget) }}" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <x-icon name="pen" style="solid" size="sm" />
                                </a>
                                <form action="{{ route('core.budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                        <x-icon name="trash" style="solid" size="sm" />
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-500">Gasto: R$ {{ number_format($budget->spent_amount, 2, ',', '.') }}</span>
                                <span class="font-bold {{ $budget->is_exceeded ? 'text-red-500' : 'text-slate-700 dark:text-slate-300' }}">
                                    Limite: R$ {{ number_format($budget->limit_amount, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $budget->is_exceeded ? 'bg-red-500' : 'bg-primary' }}"
                                     style="width: {{ $budget->usage_percentage }}%"></div>
                            </div>
                        </div>

                        <p class="text-xs text-center {{ $budget->is_exceeded ? 'text-red-500 font-bold' : 'text-slate-400' }}">
                            {{ $budget->is_exceeded ? 'Orçamento excedido!' : number_format(100 - $budget->usage_percentage, 1) . '% restante' }}
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-slate-800 rounded-2xl">
                        <x-icon name="calculator" style="solid" size="6xl" class="text-slate-300 dark:text-slate-600 mb-4" />
                        <p class="text-slate-500 dark:text-slate-400 font-bold">Nenhum orçamento cadastrado</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
