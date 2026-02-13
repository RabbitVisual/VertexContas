<x-panelsuporte::layouts.master>
    <div class="space-y-8 animate-in fade-in duration-500">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Relatórios de Atendimento</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">Análise de métricas e performance do suporte técnico.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold text-xs rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                    <x-icon name="download" style="solid" />
                    Exportar PDF
                </button>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <x-icon name="ticket" class="text-7xl text-primary" />
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total de Chamados</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ $totalTickets }}</h3>
                <div class="mt-4 flex items-center gap-2">
                    <div class="h-1.5 flex-1 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-primary w-full opacity-30"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                 <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <x-icon name="check-double" class="text-7xl text-emerald-500" />
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Taxa de Resolução</p>
                <h3 class="text-3xl font-black text-emerald-500 mt-2">{{ number_format($resolutionRate, 1) }}%</h3>
                <div class="mt-4 flex items-center gap-2">
                    <div class="h-1.5 flex-1 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" style="width: {{ $resolutionRate }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                 <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <x-icon name="clock" class="text-7xl text-amber-500" />
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Tempo Médio Resposta</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2">
                    {{ $averageResponseTime->avg_time ? number_format($averageResponseTime->avg_time, 0) : '0' }} <span class="text-sm font-bold text-gray-400">min</span>
                </h3>
                <div class="mt-4 flex items-center gap-2">
                    <div class="h-1.5 flex-1 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 w-1/2 opacity-30"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                 <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <x-icon name="bolt" class="text-7xl text-primary" />
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Em Aberto</p>
                <h3 class="text-3xl font-black text-primary mt-2">
                    {{ $ticketsByStatus->where('status', 'open')->first()->count ?? 0 }}
                </h3>
                <div class="mt-4 flex items-center gap-2">
                    <div class="h-1.5 flex-1 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-primary" style="width: {{ $totalTickets > 0 ? (($ticketsByStatus->where('status', 'open')->first()->count ?? 0) / $totalTickets) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Chart -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-wider text-[11px] mb-8 flex items-center gap-2">
                    <x-icon name="chart-line" style="duotone" class="text-primary" />
                    Volume de Chamados (Últimos 7 dias)
                </h4>
                <div class="h-72 flex items-end justify-between gap-4 px-4 overflow-hidden">
                    @forelse($recentTicketsByDay as $day)
                        @php $height = ($totalTickets > 0) ? ($day->count / $totalTickets) * 200 : 0; @endphp
                        <div class="flex-1 flex flex-col items-center gap-4 group">
                            <div class="w-full bg-primary/10 rounded-2xl transition-all group-hover:bg-primary group-hover:shadow-lg group-hover:shadow-primary/20 relative cursor-pointer" style="height: {{ max(10, $height) }}px">
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 text-[10px] font-black text-primary bg-white dark:bg-slate-800 px-2 py-1 rounded-lg border border-gray-100 dark:border-gray-700 opacity-0 group-hover:opacity-100 transition-all pointer-events-none">
                                    {{ $day->count }}
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">{{ Carbon\Carbon::parse($day->date)->translatedFormat('d M') }}</span>
                        </div>
                    @empty
                         <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs font-bold italic">Nenhum dado registrado</div>
                    @endforelse
                </div>
            </div>

            <!-- Priority Distribution -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-wider text-[11px] mb-8 flex items-center gap-2">
                    <x-icon name="bars-staggered" style="duotone" class="text-primary" />
                    Distribuição por Prioridade
                </h4>

                <div class="space-y-8">
                    @php
                        $priorityLabels = ['high' => 'Alta Prioridade', 'medium' => 'Média Prioridade', 'low' => 'Baixa Prioridade'];
                        $priorityColors = ['high' => 'bg-red-500', 'medium' => 'bg-amber-500', 'low' => 'bg-emerald-500'];
                        $priorityIcons = ['high' => 'fire', 'medium' => 'bolt', 'low' => 'leaf'];
                    @endphp

                    @foreach(['high', 'medium', 'low'] as $p)
                        @php
                            $count = $ticketsByPriority->where('priority', $p)->first()->count ?? 0;
                            $percent = $totalTickets > 0 ? ($count / $totalTickets) * 100 : 0;
                        @endphp
                        <div class="space-y-3 group">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg {{ str_replace('bg-', 'bg-opacity-10 ', $priorityColors[$p]) }} {{ str_replace('bg-', 'text-', $priorityColors[$p]) }} flex items-center justify-center">
                                        <x-icon name="{{ $priorityIcons[$p] }}" class="text-[10px]" />
                                    </div>
                                    <span class="text-[10px] font-black text-slate-600 dark:text-gray-400 uppercase tracking-widest">{{ $priorityLabels[$p] }}</span>
                                </div>
                                <span class="text-[11px] font-black text-slate-900 dark:text-white">{{ $count }}</span>
                            </div>
                            <div class="h-2 w-full bg-gray-50 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full {{ $priorityColors[$p] }} rounded-full group-hover:opacity-80 transition-opacity" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12 p-5 bg-primary/5 rounded-[2rem] border border-primary/10">
                    <p class="text-[9px] font-bold text-primary uppercase tracking-[0.2em] mb-1">Status Geral</p>
                    <p class="text-[11px] text-slate-800 dark:text-gray-300 leading-snug font-medium">
                        O sistema está operando com <span class="font-black text-primary">{{ number_format($resolutionRate, 0) }}%</span> de eficiência na resolução.
                    </p>
                </div>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm">
             <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-wider text-[11px] mb-8">Resumo por Status</h4>
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                 @foreach($ticketsByStatus as $status)
                    @php
                        $statusMeta = [
                            'open' => ['label' => 'Abertos', 'color' => 'emerald', 'icon' => 'folder-open'],
                            'pending' => ['label' => 'Pendentes', 'color' => 'amber', 'icon' => 'clock'],
                            'answered' => ['label' => 'Respondidos', 'color' => 'blue', 'icon' => 'check-circle'],
                            'closed' => ['label' => 'Fechados', 'color' => 'gray', 'icon' => 'circle-xmark'],
                        ];
                        $meta = $statusMeta[$status->status] ?? ['label' => $status->status, 'color' => 'gray', 'icon' => 'circle'];
                    @endphp
                    <div class="p-5 rounded-3xl bg-{{ $meta['color'] }}-50/50 dark:bg-{{ $meta['color'] }}-500/5 border border-{{ $meta['color'] }}-100 dark:border-{{ $meta['color'] }}-500/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-{{ $meta['color'] }}-500 text-white flex items-center justify-center shadow-lg shadow-{{ $meta['color'] }}-500/20">
                                <x-icon name="{{ $meta['icon'] }}" class="text-xs" />
                            </div>
                            <span class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">{{ $meta['label'] }}</span>
                        </div>
                        <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $status->count }}</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-1">Chamados Registrados</p>
                    </div>
                 @endforeach
             </div>
        </div>
    </div>
</x-panelsuporte::layouts.master>
