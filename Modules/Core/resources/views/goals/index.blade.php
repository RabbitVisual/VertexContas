@php
    $isPro = auth()->user()?->isPro() ?? false;

    $totalTarget = $goals->sum('target_amount');
    $totalCurrent = $goals->sum('current_amount');
    $avgProgress = $goals->count() > 0 ? ($totalCurrent / max($totalTarget, 1)) * 100 : 0;
@endphp

<x-paneluser::layouts.master :title="'Minhas Metas'">
    <div class="space-y-8 pb-8">
        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-600/20 to-transparent"></div>

            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/20 border border-indigo-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="bullseye" style="duotone" class="w-4 h-4 text-indigo-300" />
                        <span class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em]">Planejamento</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Minhas Metas</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base md:text-lg leading-relaxed">Transforme seus sonhos em objetivos alcançáveis</p>
                </div>
                @can('create', \Modules\Core\Models\Goal::class)
                    <a href="{{ route('core.goals.create') }}" class="shrink-0 inline-flex items-center gap-2.5 px-6 py-4 rounded-2xl bg-white text-slate-900 font-bold hover:bg-slate-100 transition-all shadow-lg shadow-white/10 hover:-translate-y-1">
                        <x-icon name="plus" style="duotone" class="w-5 h-5 text-indigo-600" />
                        Nova Meta
                    </a>
                @endcan
            </div>

            {{-- Stats Bar inside Hero - Restricted to PRO --}}
            @if($isPro)
                <div class="relative border-t border-white/5 bg-black/10 backdrop-blur-sm px-6 md:px-10 py-6 grid grid-cols-2 md:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300/70 mb-1">Total Objetivado</p>
                        <p class="sensitive-value text-xl md:text-2xl font-black font-mono tracking-tight">R$ {{ number_format($totalTarget, 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-300/70 mb-1">Total Acumulado</p>
                        <p class="sensitive-value text-xl md:text-2xl font-black font-mono tracking-tight text-emerald-400">R$ {{ number_format($totalCurrent, 2, ',', '.') }}</p>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-300/70 mb-1">Progresso Geral</p>
                        <div class="flex items-center gap-3">
                            <p class="text-xl md:text-2xl font-black font-mono tracking-tight text-amber-400">{{ number_format($avgProgress, 1) }}%</p>
                            <div class="flex-1 max-w-[100px] h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ $avgProgress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="relative border-t border-white/5 bg-black/5 px-6 md:px-10 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-icon name="lock" style="solid" class="text-indigo-400/50 text-xs" />
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40">Estatísticas Consolidadas Bloqueadas</span>
                    </div>
                    <a href="{{ route('user.subscription.index') }}" class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 bg-indigo-500/20 border border-indigo-400/30 rounded-lg text-indigo-200 hover:bg-indigo-500/40 transition-colors">
                        Liberar com PRO
                    </a>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-200 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-icon name="circle-check" style="solid" class="w-5 h-5" />
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="p-1 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-800/30 transition-colors">
                    <x-icon name="xmark" style="solid" class="w-5 h-5" />
                </button>
            </div>
        @endif

        {{-- Limit Status Bar --}}
        <x-core::limit-status entity="goal" label="Metas Ativas" />

        {{-- PRO Section: Simulador de Realização --}}
        @if($isPro && $goals->where('deadline', '!=', null)->count() > 0)
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden group">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/30">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <x-icon name="wand-magic-sparkles" style="duotone" class="w-4 h-4" />
                        </div>
                        Simulador de Realização Pro
                        <span class="px-2 py-0.5 text-[10px] font-black bg-amber-500 text-white rounded uppercase tracking-widest">Exclusivo</span>
                    </h3>
                    <x-icon name="chevron-right" size="xs" class="text-slate-400 transition-transform group-hover:translate-x-1" />
                </div>
                <div class="p-6">
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
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-1">
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed italic">
                                "Para atingir todas as suas metas com prazos definidos, você deve reservar mensalmente aproximadamente:"
                            </p>
                            <div class="mt-4 flex items-baseline gap-2 text-indigo-600 dark:text-indigo-400">
                                <span class="text-lg font-bold">R$</span>
                                <span class="text-5xl font-black font-mono tracking-tighter">
                                    {{ number_format($totalMonthlyNeeded, 2, ',', '.') }}
                                </span>
                                <span class="text-sm font-bold uppercase tracking-wider">/ mês</span>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-full border-8 border-indigo-100 dark:border-indigo-900/30">
                             <x-icon name="calendar-check" style="duotone" class="text-3xl text-indigo-500" />
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Goals Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($goals as $goal)
                @php
                    $percentage = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                    $percentage = min($percentage, 100);
                    $remaining = max(0, $goal->target_amount - $goal->current_amount);

                    $colorClass = $percentage >= 100 ? 'emerald' : ($percentage >= 50 ? 'indigo' : 'amber');
                    $bgClass = match($colorClass) {
                        'emerald' => 'bg-emerald-50 dark:bg-emerald-900/10',
                        'indigo' => 'bg-indigo-50 dark:bg-indigo-900/10',
                        default => 'bg-amber-50 dark:bg-amber-900/10'
                    };
                    $textClass = match($colorClass) {
                        'emerald' => 'text-emerald-600 dark:text-emerald-400',
                        'indigo' => 'text-indigo-600 dark:text-indigo-400',
                        default => 'text-amber-600 dark:text-amber-400'
                    };
                    $progressClass = match($colorClass) {
                        'emerald' => 'bg-emerald-500 shadow-emerald-500/20',
                        'indigo' => 'bg-indigo-600 shadow-indigo-600/20',
                        default => 'bg-amber-500 shadow-amber-500/20'
                    };
                @endphp

                <div class="group relative bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 transition-all hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                    {{-- Glass Decorative Accent --}}
                    <div class="absolute -right-4 -top-4 w-24 h-24 {{ $bgClass }} rounded-full opacity-50 blur-2xl group-hover:scale-150 transition-transform"></div>

                    <div class="relative z-10 flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl {{ $bgClass }} {{ $textClass }} flex items-center justify-center transition-transform group-hover:scale-110">
                                <x-icon name="{{ $percentage >= 100 ? 'trophy' : 'bullseye' }}" style="duotone" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 dark:text-white leading-tight mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $goal->name }}</h3>
                                @if($goal->deadline)
                                    <div class="flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        <x-icon name="clock" class="mr-1 text-[9px]" />
                                        @if($goal->deadline->isPast())
                                            <span class="text-rose-500">Expirada</span>
                                        @else
                                            {{ $goal->deadline->diffForHumans() }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Dropdown Toggle (Visual only for Vertex look) --}}
                        <button class="p-2 text-slate-300 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            <x-icon name="ellipsis-vertical" />
                        </button>
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-end mb-3">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Acumulado</p>
                                <p class="sensitive-value text-xl font-black text-slate-900 dark:text-white font-mono tracking-tighter tabular-nums">R$ {{ number_format($goal->current_amount, 2, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Meta</p>
                                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 font-mono">R$ {{ number_format($goal->target_amount, 2, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Premium Progress Bar --}}
                        <div class="relative w-full h-3 bg-slate-100 dark:bg-slate-700/50 rounded-full overflow-hidden mb-3">
                            <div class="absolute top-0 left-0 h-full {{ $progressClass }} rounded-full transition-all duration-1000 ease-out shadow-lg"
                                 style="width: {{ $percentage }}%">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                            <span class="{{ $textClass }}">{{ number_format($percentage, 0) }}% concluído</span>
                            @if($remaining > 0)
                                <span class="text-slate-400 italic">Restam R$ {{ number_format($remaining, 2, ',', '.') }}</span>
                            @else
                                <span class="text-emerald-500 flex items-center gap-1">
                                    <x-icon name="circle-check" /> Concluída
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions Bar --}}
                    <div class="flex items-center gap-2 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="{{ route('core.goals.edit', $goal) }}" class="flex-1 py-3 rounded-xl text-xs font-black uppercase tracking-wider text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-indigo-600 transition-all flex items-center justify-center gap-2">
                            <x-icon name="pen" style="solid" /> Editar
                        </a>
                        <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
                        <form action="{{ route('core.goals.destroy', $goal) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Deseja realmente remover esta meta?')" class="w-full py-3 rounded-xl text-xs font-black uppercase tracking-wider text-slate-500 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:text-rose-600 transition-all flex items-center justify-center gap-2">
                                <x-icon name="trash" style="solid" /> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-slate-800 rounded-[40px] border-2 border-dashed border-slate-100 dark:border-slate-800">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-slate-50 dark:bg-slate-900 mb-6 text-slate-200 dark:text-slate-800">
                        <x-icon name="bullseye" size="2xl" style="duotone" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Sem metas ativas</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-8">Planeje seu futuro hoje. Adicione sua primeira meta financeira para começar a poupar.</p>
                    <a href="{{ route('core.goals.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-indigo-600 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-indigo-900/40 hover:bg-indigo-500 transition-all transform hover:-translate-y-1">
                        <x-icon name="plus" style="solid" />
                        Criar minha primeira meta
                    </a>
                </div>
            @endforelse

            @if($goals->count() > 0)
                <a href="{{ route('core.goals.create') }}" class="group relative flex flex-col items-center justify-center min-h-[300px] border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl hover:border-indigo-500 dark:hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-900/30 transition-all overflow-hidden">
                    <div class="relative z-10 w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-800 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 flex items-center justify-center mb-4 transition-all group-hover:scale-110">
                        <x-icon name="plus" style="solid" class="text-2xl text-slate-300 group-hover:text-indigo-600 transition-colors" />
                    </div>
                    <span class="relative z-10 font-black uppercase tracking-[0.2em] text-xs text-slate-400 group-hover:text-indigo-600 transition-colors">Nova Meta</span>
                </a>
            @endif
        </div>
    </div>
</x-paneluser::layouts.master>
