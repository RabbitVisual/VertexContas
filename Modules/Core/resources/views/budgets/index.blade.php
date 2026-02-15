@php
    $isPro = auth()->user()?->isPro() ?? false;
    $totalBudgeted = $budgets->sum('limit_amount');
    $totalSpent = $budgets->sum('spent_amount');
    $avgConsumption = $budgets->count() > 0 ? ($totalSpent / max($totalBudgeted, 1)) * 100 : 0;
    $criticalBudgets = $isPro
        ? $budgets->filter(fn ($b) => $b->usage_percentage > (($b->alert_threshold ?? 80)))
            ->sortByDesc('usage_percentage')
            ->take(3)
        : collect();
@endphp

<x-paneluser::layouts.master :title="'Meus Orçamentos'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
    {{-- Hero CBAV --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <span>Gestão de gastos</span>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Orçamentos</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Meus <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Orçamentos</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Defina limites por categoria e acompanhe o consumo. Edite quando quiser sem avisos de limite.</p>
            </div>
            @can('create', \Modules\Core\Models\Budget::class)
                @if(!($inspectionReadOnly ?? false))
                    <a href="{{ route('core.budgets.create') }}" class="shrink-0 inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                        <x-icon name="plus" style="solid" class="w-5 h-5" />
                        Novo orçamento
                    </a>
                @endif
            @endcan
        </div>

        {{-- Stats: só Pro --}}
        @if($isPro)
            <div class="relative z-10 mt-8 pt-8 border-t border-gray-200 dark:border-white/5 grid grid-cols-2 md:grid-cols-3 gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="chart-pie" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total orçado</p>
                        <p class="sensitive-value text-xl font-black text-gray-900 dark:text-white tabular-nums"><x-core::financial-value :value="$totalBudgeted" /></p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0">
                        <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total utilizado</p>
                        <p class="sensitive-value text-xl font-black text-rose-600 dark:text-rose-400 tabular-nums"><x-core::financial-value :value="$totalSpent" /></p>
                    </div>
                </div>
                <div class="col-span-2 md:col-span-1 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                        <x-icon name="chart-line" style="duotone" class="w-6 h-6" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Consumo médio</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black text-gray-900 dark:text-white tabular-nums">{{ number_format($avgConsumption, 1) }}%</span>
                            <div class="flex-1 h-2 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden min-w-[60px]">
                                <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ min($avgConsumption, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="relative z-10 mt-8 pt-8 border-t border-gray-200 dark:border-white/5 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400 dark:text-gray-500">
                        <x-icon name="lock" style="solid" class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Resumo de orçamentos disponível no Vertex Pro</span>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider hover:bg-emerald-600/20 transition-colors">
                    <x-icon name="sparkles" style="duotone" class="w-4 h-4" />
                    Vertex Pro
                </a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/10 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <x-icon name="circle-check" style="solid" class="w-5 h-5" />
                </div>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ session('success') }}</span>
            </div>
            <button type="button" @click="show = false" class="p-2 rounded-lg hover:bg-emerald-500/20 text-gray-500 hover:text-gray-700 transition-colors">
                <x-icon name="xmark" style="solid" class="w-5 h-5" />
            </button>
        </div>
    @endif

    {{-- Limit: só mostra quando ainda está abaixo do limite; ao editar o único orçamento não exibe CTA de assinatura --}}
    <x-core::limit-status entity="budget" label="Orçamentos Ativos" :showOnlyWhenUnderLimit="true" />

    {{-- Análise de desvio: só Pro --}}
    @if($isPro && $criticalBudgets->count() > 0)
        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex items-center gap-3 bg-rose-500/5 dark:bg-rose-500/10">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Categorias que exigem atenção</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Vertex Pro</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($criticalBudgets as $crit)
                    <div class="rounded-2xl bg-gray-50 dark:bg-gray-950/50 p-4 border border-gray-200 dark:border-white/5">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ $crit->category->name }}</span>
                            <span class="text-xs font-bold text-rose-600 dark:text-rose-400">{{ number_format($crit->usage_percentage, 0) }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 dark:bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-rose-500 rounded-full" style="width: {{ min($crit->usage_percentage, 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Grid de orçamentos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($budgets as $budget)
            @php
                $percentage = min($budget->usage_percentage, 100);
                $isExceeded = $budget->spent_amount > $budget->limit_amount;
                $remaining = max(0, $budget->limit_amount - $budget->spent_amount);
                $color = $isExceeded ? 'rose' : ($percentage > 80 ? 'amber' : 'emerald');
                $bgClass = match($color) {
                    'rose' => 'bg-rose-50 dark:bg-rose-900/10',
                    'amber' => 'bg-amber-50 dark:bg-amber-900/10',
                    default => 'bg-emerald-50 dark:bg-emerald-900/10'
                };
                $textClass = match($color) {
                    'rose' => 'text-rose-600 dark:text-rose-400',
                    'amber' => 'text-amber-600 dark:text-amber-400',
                    default => 'text-emerald-600 dark:text-emerald-400'
                };
                $progressClass = match($color) {
                    'rose' => 'bg-rose-500',
                    'amber' => 'bg-amber-500',
                    default => 'bg-emerald-500'
                };
                $categoryColor = $budget->category->color ?? '#10b981';
                $categoryIcon = $budget->category->icon ?? 'wallet';
            @endphp

            <div class="group relative overflow-hidden rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="absolute -right-4 -top-4 w-24 h-24 {{ $bgClass }} rounded-full opacity-50 blur-2xl"></div>

                <div class="relative z-10 p-6">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background-color: {{ $categoryColor }}20; color: {{ $categoryColor }}">
                                <x-icon name="{{ $categoryIcon }}" style="duotone" class="w-6 h-6" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $budget->category->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $budget->period === 'monthly' ? 'Mensal' : 'Anual' }}</span>
                                    @if($isExceeded)
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-500">Excedido</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(!($inspectionReadOnly ?? false))
                            <div class="flex items-center gap-1">
                                <a href="{{ route('core.budgets.edit', $budget) }}" class="p-2 rounded-xl text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" title="Editar">
                                    <x-icon name="pen" style="solid" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('core.budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Excluir este orçamento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:text-rose-600 transition-colors" title="Excluir">
                                        <x-icon name="trash" style="solid" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gasto atual</p>
                                <p class="sensitive-value text-xl font-black text-gray-900 dark:text-white tabular-nums"><x-core::financial-value :value="$budget->spent_amount" /></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Limite</p>
                                <p class="text-sm font-bold text-gray-600 dark:text-gray-300 tabular-nums"><x-core::financial-value :value="$budget->limit_amount" /></p>
                            </div>
                        </div>
                        <div class="h-2.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full {{ $progressClass }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider">
                            <span class="{{ $textClass }}">{{ number_format($budget->usage_percentage, 1) }}% utilizado</span>
                            @if(!$isExceeded)
                                <span class="text-gray-500 dark:text-gray-400"><x-core::financial-value :value="$remaining" /> disponíveis</span>
                            @else
                                <span class="text-rose-600 dark:text-rose-400"><x-core::financial-value :value="abs($remaining)" /> acima</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center rounded-3xl bg-gray-50 dark:bg-gray-950/50 border-2 border-dashed border-gray-200 dark:border-white/5">
                <div class="w-20 h-20 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-5 border border-gray-100 dark:border-white/5">
                    <x-icon name="chart-pie" style="duotone" class="w-10 h-10" />
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Sem orçamentos</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Defina limites por categoria para controlar seus gastos.</p>
                @can('create', \Modules\Core\Models\Budget::class)
                    @if(!($inspectionReadOnly ?? false))
                        <a href="{{ route('core.budgets.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                            <x-icon name="plus" style="solid" class="w-5 h-5" />
                            Criar meu primeiro orçamento
                        </a>
                    @endif
                @endcan
            </div>
        @endforelse

        @if($budgets->count() > 0 && auth()->user()?->can('create', \Modules\Core\Models\Budget::class) && !($inspectionReadOnly ?? false))
            <a href="{{ route('core.budgets.create') }}" class="group flex flex-col items-center justify-center min-h-[280px] rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 hover:border-emerald-500/50 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-white/5 group-hover:bg-emerald-500/10 flex items-center justify-center mb-3 transition-colors">
                    <x-icon name="plus" style="solid" class="w-7 h-7 text-gray-400 dark:text-gray-500 group-hover:text-emerald-600 dark:group-hover:text-emerald-400" />
                </div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400">Novo orçamento</span>
            </a>
        @endif
    </div>
</div>
</x-paneluser::layouts.master>
