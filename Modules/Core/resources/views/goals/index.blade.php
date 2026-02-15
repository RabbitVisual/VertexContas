@php
    $isPro = auth()->user()?->isPro() ?? false;
    $totalTarget = $goals->sum('target_amount');
    $totalCurrent = $goals->sum('current_amount');
    $avgProgress = $goals->count() > 0 ? ($totalCurrent / max($totalTarget, 1)) * 100 : 0;
@endphp

<x-paneluser::layouts.master :title="'Minhas Metas'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
    {{-- Hero CBAV --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <span>Planejamento</span>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Metas</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Minhas <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Metas</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Transforme seus sonhos em objetivos alcançáveis. Acompanhe o progresso de cada meta.</p>
            </div>
            @can('create', \Modules\Core\Models\Goal::class)
                @if(!($inspectionReadOnly ?? false))
                    <a href="{{ route('core.goals.create') }}" class="shrink-0 inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                        <x-icon name="plus" style="solid" class="w-5 h-5" />
                        Nova Meta
                    </a>
                @endif
            @endcan
        </div>

        {{-- Stats: só para Pro --}}
        @if($isPro)
            <div class="relative z-10 mt-8 pt-8 border-t border-gray-200 dark:border-white/5 grid grid-cols-2 md:grid-cols-3 gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="bullseye" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total objetivado</p>
                        <p class="sensitive-value text-xl font-black text-gray-900 dark:text-white tabular-nums"><x-core::financial-value :value="$totalTarget" /></p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600/10 dark:bg-teal-500/20 flex items-center justify-center text-teal-600 dark:text-teal-400 shrink-0">
                        <x-icon name="sack-dollar" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total acumulado</p>
                        <p class="sensitive-value text-xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums"><x-core::financial-value :value="$totalCurrent" /></p>
                    </div>
                </div>
                <div class="col-span-2 md:col-span-1 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                        <x-icon name="chart-line" style="duotone" class="w-6 h-6" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progresso geral</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black text-gray-900 dark:text-white tabular-nums">{{ number_format($avgProgress, 1) }}%</span>
                            <div class="flex-1 h-2 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden min-w-[60px]">
                                <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ min($avgProgress, 100) }}%"></div>
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
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estatísticas consolidadas disponíveis no Vertex Pro</span>
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

    <x-core::limit-status entity="goal" label="Metas Ativas" />

    {{-- Simulador de Realização: só Pro --}}
    @if($isPro && $goals->where('deadline', '!=', null)->count() > 0)
        @php
            $totalMonthlyNeeded = 0;
            foreach($goals->where('deadline', '!=', null) as $g) {
                $monthsLeft = now()->diffInMonths($g->deadline);
                if ($monthsLeft > 0) {
                    $remaining = max(0, $g->target_amount - $g->current_amount);
                    $totalMonthlyNeeded += $remaining / $monthsLeft;
                }
            }
        @endphp
        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex items-center gap-3 bg-gray-50/50 dark:bg-gray-950/30">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <x-icon name="wand-magic-sparkles" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Simulador de Realização</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Vertex Pro</p>
                </div>
                <span class="ml-auto px-2.5 py-1 text-[10px] font-black bg-amber-500 text-white rounded-lg uppercase tracking-wider">Pro</span>
            </div>
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-4">Para atingir todas as metas com prazo, reserve mensalmente aproximadamente:</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">R$</span>
                        <span class="sensitive-value text-3xl sm:text-4xl font-black text-gray-900 dark:text-white font-mono tracking-tight"><x-core::financial-value :value="$totalMonthlyNeeded" prefix="" /></span>
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">/ mês</span>
                    </div>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <x-icon name="calendar-check" style="duotone" class="w-8 h-8" />
                </div>
            </div>
        </div>
    @endif

    {{-- Grid de metas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($goals as $goal)
            @php
                $percentage = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                $percentage = min($percentage, 100);
                $remaining = max(0, $goal->target_amount - $goal->current_amount);
                $colorClass = $percentage >= 100 ? 'emerald' : ($percentage >= 50 ? 'teal' : 'amber');
                $bgClass = match($colorClass) {
                    'emerald' => 'bg-emerald-50 dark:bg-emerald-900/10',
                    'teal' => 'bg-teal-50 dark:bg-teal-900/10',
                    default => 'bg-amber-50 dark:bg-amber-900/10'
                };
                $textClass = match($colorClass) {
                    'emerald' => 'text-emerald-600 dark:text-emerald-400',
                    'teal' => 'text-teal-600 dark:text-teal-400',
                    default => 'text-amber-600 dark:text-amber-400'
                };
                $progressClass = match($colorClass) {
                    'emerald' => 'bg-emerald-500',
                    'teal' => 'bg-teal-500',
                    default => 'bg-amber-500'
                };
            @endphp

            <div class="group relative overflow-hidden rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="absolute -right-4 -top-4 w-24 h-24 {{ $bgClass }} rounded-full opacity-50 blur-2xl"></div>

                <div class="relative z-10 p-6">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-2xl {{ $bgClass }} flex items-center justify-center {{ $textClass }} shrink-0">
                                <x-icon name="{{ $percentage >= 100 ? 'trophy' : 'bullseye' }}" style="duotone" class="w-6 h-6" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $goal->name }}</h3>
                                @if($goal->deadline)
                                    <div class="flex items-center gap-1.5 mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        <x-icon name="clock" style="duotone" class="w-3.5 h-3.5" />
                                        @if($goal->deadline->isPast())
                                            <span class="text-rose-500">Expirada</span>
                                        @else
                                            {{ $goal->deadline->diffForHumans() }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acumulado</p>
                                <p class="sensitive-value text-xl font-black text-gray-900 dark:text-white tabular-nums"><x-core::financial-value :value="$goal->current_amount" /></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Meta</p>
                                <p class="text-sm font-bold text-gray-600 dark:text-gray-300 tabular-nums"><x-core::financial-value :value="$goal->target_amount" /></p>
                            </div>
                        </div>
                        <div class="h-2.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full {{ $progressClass }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider">
                            <span class="{{ $textClass }}">{{ number_format($percentage, 0) }}%</span>
                            @if($remaining > 0)
                                <span class="text-gray-500 dark:text-gray-400">Restam <x-core::financial-value :value="$remaining" /></span>
                            @else
                                <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                    <x-icon name="circle-check" style="solid" class="w-3.5 h-3.5" /> Concluída
                                </span>
                            @endif
                        </div>
                    </div>

                    @if(!($inspectionReadOnly ?? false))
                        <div class="flex items-center gap-2 pt-4 mt-4 border-t border-gray-100 dark:border-white/5">
                            <a href="{{ route('core.goals.edit', $goal) }}" class="flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center justify-center gap-2">
                                <x-icon name="pen" style="solid" class="w-4 h-4" /> Editar
                            </a>
                            <div class="w-px h-4 bg-gray-200 dark:bg-white/10"></div>
                            <form action="{{ route('core.goals.destroy', $goal) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Deseja realmente remover esta meta?')" class="w-full py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:text-rose-600 transition-colors flex items-center justify-center gap-2">
                                    <x-icon name="trash" style="solid" class="w-4 h-4" /> Excluir
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center rounded-3xl bg-gray-50 dark:bg-gray-950/50 border-2 border-dashed border-gray-200 dark:border-white/5">
                <div class="w-20 h-20 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-5 border border-gray-100 dark:border-white/5">
                    <x-icon name="bullseye" style="duotone" class="w-10 h-10" />
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Sem metas ativas</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Planeje seu futuro. Adicione sua primeira meta financeira para começar a poupar.</p>
                @can('create', \Modules\Core\Models\Goal::class)
                    @if(!($inspectionReadOnly ?? false))
                        <a href="{{ route('core.goals.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                            <x-icon name="plus" style="solid" class="w-5 h-5" />
                            Criar minha primeira meta
                        </a>
                    @endif
                @endcan
            </div>
        @endforelse

        @if($goals->count() > 0 && auth()->user()?->can('create', \Modules\Core\Models\Goal::class) && !($inspectionReadOnly ?? false))
            <a href="{{ route('core.goals.create') }}" class="group flex flex-col items-center justify-center min-h-[280px] rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 hover:border-emerald-500/50 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-white/5 group-hover:bg-emerald-500/10 flex items-center justify-center mb-3 transition-colors">
                    <x-icon name="plus" style="solid" class="w-7 h-7 text-gray-400 dark:text-gray-500 group-hover:text-emerald-600 dark:group-hover:text-emerald-400" />
                </div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400">Nova Meta</span>
            </a>
        @endif
    </div>
</div>
</x-paneluser::layouts.master>
