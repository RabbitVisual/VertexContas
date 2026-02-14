@php
    $isPro = auth()->user()?->isPro() ?? false;

    $totalBudgeted = $budgets->sum('limit_amount');
    $totalSpent = $budgets->sum('spent_amount');
    $avgConsumption = $budgets->count() > 0 ? ($totalSpent / max($totalBudgeted, 1)) * 100 : 0;

    // Pro Feature: Análise de Desvio (Top 3 categorias críticas)
    $criticalBudgets = $isPro ? $budgets->where('usage_percentage', '>', 80)->sortByDesc('usage_percentage')->take(3) : collect();
@endphp

<x-paneluser::layouts.master :title="'Meus Orçamentos'">
    <div class="space-y-8 pb-8">
        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-600/20 to-transparent"></div>

            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/20 border border-indigo-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="chart-pie" style="duotone" class="w-4 h-4 text-indigo-300" />
                        <span class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em]">Gestão de Gastos</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Orçamentos</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base md:text-lg leading-relaxed">Assuma o controle total das suas categorias de gastos</p>
                </div>
                <div class="flex gap-4">
                    @can('create', \Modules\Core\Models\Budget::class)
                        <a href="{{ route('core.budgets.create') }}" class="shrink-0 inline-flex items-center gap-2.5 px-6 py-4 rounded-2xl bg-white text-slate-900 font-bold hover:bg-slate-100 transition-all shadow-lg shadow-white/10 hover:-translate-y-1">
                            <x-icon name="plus" style="duotone" class="w-5 h-5 text-indigo-600" />
                            Novo Orçamento
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Stats Bar - Pro Exclusive (Oculto para free sem CTA conforme pedido) --}}
            @if($isPro)
                <div class="relative border-t border-white/5 bg-black/10 backdrop-blur-sm px-6 md:px-10 py-6 grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300/70 mb-1">Total Orçado</p>
                        <p class="sensitive-value text-xl md:text-2xl font-black font-mono tracking-tight">R$ {{ number_format($totalBudgeted, 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-300/70 mb-1">Total Utilizado</p>
                        <p class="sensitive-value text-xl md:text-2xl font-black font-mono tracking-tight text-rose-400">R$ {{ number_format($totalSpent, 2, ',', '.') }}</p>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-300/70 mb-1">Consumo Médio</p>
                        <div class="flex items-center gap-3">
                            <p class="text-xl md:text-2xl font-black font-mono tracking-tight text-amber-400">{{ number_format($avgConsumption, 1) }}%</p>
                            <div class="flex-1 max-w-[100px] h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ min($avgConsumption, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Limit Status Bar --}}
        <x-core::limit-status entity="budget" label="Orçamentos Ativos" />

        {{-- PRO Section: Análise de Desvio - Invisible for Free --}}
        @if($isPro && $criticalBudgets->count() > 0)
            <div class="bg-rose-50 dark:bg-rose-900/10 rounded-3xl p-6 border border-rose-100 dark:border-rose-900/30">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/20">
                        <x-icon name="triangle-exclamation" style="duotone" />
                    </div>
                    <div>
                        <h3 class="font-black text-rose-900 dark:text-rose-400">Análise de Desvio Pro</h3>
                        <p class="text-xs text-rose-700/60 dark:text-rose-500/60 font-medium">Categorias que exigem sua atenção imediata</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($criticalBudgets as $crit)
                        <div class="bg-white/60 dark:bg-slate-900/40 backdrop-blur-md rounded-2xl p-4 border border-white dark:border-slate-800">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ $crit->category->name }}</span>
                                <span class="text-xs font-black text-rose-600">{{ number_format($crit->usage_percentage, 0) }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-500" style="width: {{ min($crit->usage_percentage, 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Budgets Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($budgets as $budget)
                @php
                    $percentage = min($budget->usage_percentage, 100);
                    $isExceeded = $budget->spent_amount > $budget->limit_amount;
                    $remaining = max(0, $budget->limit_amount - $budget->spent_amount);

                    $color = $isExceeded ? 'rose' : ($percentage > 80 ? 'amber' : 'indigo');
                    $bgClass = match($color) {
                        'rose' => 'bg-rose-50 dark:bg-rose-900/10',
                        'amber' => 'bg-amber-50 dark:bg-amber-900/10',
                        default => 'bg-indigo-50 dark:bg-indigo-900/10'
                    };
                    $textClass = match($color) {
                        'rose' => 'text-rose-600 dark:text-rose-400',
                        'amber' => 'text-amber-600 dark:text-amber-400',
                        default => 'text-indigo-600 dark:text-indigo-400'
                    };
                    $progressClass = match($color) {
                        'rose' => 'bg-rose-500 shadow-rose-500/20',
                        'amber' => 'bg-amber-500 shadow-amber-500/20',
                        default => 'bg-indigo-600 shadow-indigo-600/20'
                    };
                @endphp

                <div class="group relative bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 transition-all hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                    {{-- Decorative Icon Accent --}}
                    <div class="absolute -right-4 -top-4 w-24 h-24 {{ $bgClass }} rounded-full opacity-50 blur-2xl group-hover:scale-150 transition-transform"></div>

                    <div class="relative z-10 flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner relative overflow-hidden transition-transform group-hover:scale-110"
                                 style="background-color: {{ $budget->category->color ?? '#6366f1' }}20">
                                <div class="absolute inset-0 opacity-20" style="background-color: {{ $budget->category->color ?? '#6366f1' }}"></div>
                                <x-icon name="{{ $budget->category->icon ?? 'wallet' }}" style="duotone" class="text-2xl" style="color: {{ $budget->category->color ?? '#6366f1' }}" />
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 dark:text-white leading-tight mb-0.5 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $budget->category->name }}</h3>
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 text-[9px] font-black uppercase tracking-widest text-slate-500 border border-slate-200 dark:border-slate-800">
                                        {{ $budget->period === 'monthly' ? 'Mensal' : 'Anual' }}
                                    </span>
                                    @if($isExceeded)
                                        <span class="text-[9px] font-black uppercase tracking-widest text-rose-500 animate-pulse">Excedido</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('core.budgets.edit', $budget) }}" class="p-2 bg-slate-50 dark:bg-slate-900 rounded-xl text-slate-400 hover:text-indigo-600 transition-colors">
                                <x-icon name="pen" size="sm" />
                            </a>
                            <form action="{{ route('core.budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Excluir este orçamento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-slate-50 dark:bg-slate-900 rounded-xl text-slate-400 hover:text-rose-600 transition-colors">
                                    <x-icon name="trash" size="sm" />
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Gasto Atual</p>
                                <p class="sensitive-value text-xl font-black text-slate-900 dark:text-white font-mono tracking-tighter tabular-nums">R$ {{ number_format($budget->spent_amount, 2, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Limite</p>
                                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 font-mono">R$ {{ number_format($budget->limit_amount, 2, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Premium Progress Bar --}}
                        <div class="relative w-full h-3 bg-slate-100 dark:bg-slate-700/50 rounded-full overflow-hidden">
                            <div class="absolute top-0 left-0 h-full {{ $progressClass }} rounded-full transition-all duration-1000 ease-out"
                                 style="width: {{ $percentage }}%">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent"></div>
                                @if($percentage > 90)
                                    <div class="absolute inset-0 bg-[linear-gradient(45deg,rgba(255,255,255,0.1)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.1)_50%,rgba(255,255,255,0.1)_75%,transparent_75%,transparent)] bg-[length:1rem_1rem] animate-[stripe_1s_linear_infinite]"></div>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                            <span class="{{ $textClass }}">{{ number_format($budget->usage_percentage, 1) }}% utilizado</span>
                            @if(!$isExceeded)
                                <span class="text-slate-400">R$ {{ number_format($remaining, 2, ',', '.') }} disponíveis</span>
                            @else
                                <span class="text-rose-500 font-black">R$ {{ number_format(abs($remaining), 2, ',', '.') }} acima</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white dark:bg-slate-800 rounded-[40px] border-2 border-dashed border-slate-100 dark:border-slate-800">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-slate-50 dark:bg-slate-900 mb-6 text-slate-200 dark:text-slate-800">
                        <x-icon name="calculator" size="2xl" style="duotone" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Sem orçamentos ativos</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-8">Defina limites de gastos por categoria para manter sua saúde financeira em dia.</p>
                    <a href="{{ route('core.budgets.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-indigo-600 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-indigo-900/40 hover:bg-indigo-500 transition-all transform hover:-translate-y-1">
                        <x-icon name="plus" style="solid" />
                        Definir meu primeiro orçamento
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        @keyframes stripe {
            from { background-position: 0 0; }
            to { background-position: 1rem 0; }
        }
    </style>
</x-paneluser::layouts.master>
