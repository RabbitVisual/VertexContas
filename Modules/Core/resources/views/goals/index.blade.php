<x-paneluser::layouts.master :title="'Minhas Metas'">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-black text-3xl text-slate-800 dark:text-white flex items-center">
                <div class="bg-primary/10 dark:bg-primary/20 p-2 rounded-xl mr-3">
                    <x-icon name="bullseye" style="duotone" class="text-primary" />
                </div>
                Minhas Metas
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 ml-14">Defina, acompanhe e realize seus objetivos.</p>
        </div>
        @can('create', \Modules\Core\Models\Goal::class)
            <a href="{{ route('core.goals.create') }}"
               class="group relative inline-flex items-center justify-center px-6 py-3 text-base font-bold text-white transition-all duration-200 bg-primary font-pj rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary hover:bg-primary-dark shadow-lg shadow-primary/30">
                <x-icon name="plus" style="solid" class="mr-2" /> Nova Meta
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center"><x-icon name="circle-check" style="solid" class="mr-2" /> {{ session('success') }}</div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-lg p-1"><x-icon name="xmark" style="solid" /></button>
        </div>
    @endif

    <!-- Limit Status Bar -->
    <x-core::limit-status entity="goal" label="Metas Ativas" />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($goals as $goal)
            @php
                $percentage = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                $percentage = min($percentage, 100);
                $remaining = max(0, $goal->target_amount - $goal->current_amount);

                $statusColor = $percentage >= 100 ? 'emerald' : ($percentage >= 50 ? 'blue' : 'amber');
                $iconBgClass = match($statusColor) { 'emerald' => 'bg-emerald-50 dark:bg-emerald-900/10', 'blue' => 'bg-blue-50 dark:bg-blue-900/10', default => 'bg-amber-50 dark:bg-amber-900/10' };
                $iconTextClass = match($statusColor) { 'emerald' => 'text-emerald-600 dark:text-emerald-400', 'blue' => 'text-blue-600 dark:text-blue-400', default => 'text-amber-600 dark:text-amber-400' };
                $barGradientClass = match($statusColor) { 'emerald' => 'from-emerald-500 to-emerald-400', 'blue' => 'from-blue-500 to-blue-400', default => 'from-amber-500 to-amber-400' };
                $badgeClass = match($statusColor) { 'emerald' => 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20', 'blue' => 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20', default => 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20' };
            @endphp

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl {{ $iconBgClass }} flex items-center justify-center {{ $iconTextClass }}">
                             <x-icon name="{{ $percentage >= 100 ? 'trophy' : 'bullseye' }}" style="duotone" class="text-xl" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white leading-tight mb-1">{{ $goal->name }}</h3>
                            @if($goal->deadline)
                                <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                                    <x-icon name="clock" style="solid" class="mr-1 text-[10px]" />
                                    <span>
                                        @if($goal->deadline->isPast())
                                            <span class="text-red-500 font-medium">Venceu em {{ $goal->deadline->format('d/m/Y') }}</span>
                                        @else
                                            Vence em {{ $goal->deadline->diffForHumans() }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Atual</span>
                            <div class="text-2xl font-black text-slate-800 dark:text-white">
                                R$ {{ number_format($goal->current_amount, 2, ',', '.') }}
                            </div>
                        </div>
                         <div class="text-right">
                             <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Alvo</span>
                             <div class="text-sm font-bold text-slate-500 dark:text-slate-400">
                                R$ {{ number_format($goal->target_amount, 2, ',', '.') }}
                             </div>
                        </div>
                    </div>

                    <div class="relative w-full bg-slate-100 dark:bg-slate-700 rounded-full h-4 overflow-hidden">
                        <div class="absolute top-0 left-0 h-full bg-gradient-to-r {{ $barGradientClass }} rounded-full transition-all duration-1000 ease-out"
                             style="width: {{ $percentage }}%">

                             <!-- Shimmer Effect -->
                            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-2 text-xs">
                        <span class="font-bold {{ $badgeClass }} px-2 py-0.5 rounded-md">
                            {{ number_format($percentage, 0) }}% Concluído
                        </span>
                        @if($remaining > 0)
                            <span class="text-slate-400">Falta R$ {{ number_format($remaining, 2, ',', '.') }}</span>
                        @else
                            <span class="text-emerald-500 font-bold flex items-center"><x-icon name="check" style="solid" class="mr-1" /> Conquista Desbloqueada!</span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                     <a href="{{ route('core.goals.edit', $goal) }}" class="flex-1 py-2.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary transition-colors text-center flex items-center justify-center gap-2">
                        <x-icon name="pen" style="solid" /> Editar
                    </a>
                    <div class="w-px h-6 bg-slate-200 dark:bg-slate-700"></div>
                     <form action="{{ route('core.goals.destroy', $goal) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Excluir esta meta?')" class="w-full py-2.5 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:text-rose-600 transition-colors flex items-center justify-center gap-2">
                            <x-icon name="trash" style="solid" /> Excluir
                        </button>
                    </form>
                </div>
            </div>
        @empty
             <div class="col-span-full py-16 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-800 mb-6 group-hover:scale-110 transition-transform">
                    <x-icon name="bullseye" style="duotone" class="text-4xl text-slate-300 dark:text-slate-600" />
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Sem metas definidas</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8">Comece a planejar seu futuro financeiro agora mesmo. Crie uma meta para uma viagem, carro novo ou reserva de emergência.</p>
                @can('create', \Modules\Core\Models\Goal::class)
                    <a href="{{ route('core.goals.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-primary/25">
                        <x-icon name="plus" style="solid" class="mr-2" />
                        Criar Nova Meta
                    </a>
                @endcan
            </div>
        @endforelse

        @if($goals->count() > 0)
            <a href="{{ route('core.goals.create') }}" class="group h-full min-h-[300px] border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl flex flex-col items-center justify-center hover:border-primary dark:hover:border-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all cursor-pointer">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 group-hover:bg-primary/10 flex items-center justify-center mb-4 transition-colors">
                    <x-icon name="plus" style="solid" class="text-2xl text-slate-400 group-hover:text-primary transition-colors" />
                </div>
                <span class="font-bold text-slate-500 group-hover:text-primary transition-colors">Nova Meta</span>
            </a>
        @endif
    </div>
</x-paneluser::layouts.master>
