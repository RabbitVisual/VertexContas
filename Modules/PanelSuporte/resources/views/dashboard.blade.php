<x-panelsuporte::layouts.master>


<div class="space-y-8 animate-in fade-in duration-500">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Dashboard de Suporte</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">Bem-vindo de volta, {{ explode(' ', Auth::user()->name)[0] }}. Aqui está o resumo dos atendimentos.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('support.tickets.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold text-sm rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                <x-icon name="list-tree" style="duotone" class="text-primary" />
                Todos os Chamados
            </a>
            <a href="{{ route('support.manual.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                <x-icon name="book-user" style="solid" />
                Manual
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest">Abertos</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ $openTickets }}</h3>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl text-emerald-500 group-hover:scale-110 transition-transform duration-300">
                    <x-icon name="folder-open" style="duotone" class="w-6 h-6" />
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-[10px] font-bold py-0.5 px-2 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full">Ativo</span>
                <span class="text-[11px] text-gray-400 font-medium">Aguardando resposta</span>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest">Pendentes</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ $pendingTickets }}</h3>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-500/10 rounded-2xl text-amber-500 group-hover:scale-110 transition-transform duration-300">
                    <x-icon name="clock" style="duotone" class="w-6 h-6" />
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-[10px] font-bold py-0.5 px-2 bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-full">Urgente</span>
                <span class="text-[11px] text-gray-400 font-medium">Requer atenção</span>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest">Alta Prioridade</p>
                    <h3 class="text-3xl font-black text-red-600 dark:text-red-500 mt-2">{{ $highPriority }}</h3>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-500/10 rounded-2xl text-red-500 group-hover:scale-110 transition-transform duration-300">
                    <x-icon name="fire" style="duotone" class="w-6 h-6" />
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-[10px] font-bold py-0.5 px-2 bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-full">Crítico</span>
                <span class="text-[11px] text-gray-400 font-medium">Check imediato</span>
            </div>
        </div>

        <!-- Help Card (Call to action) -->
        <div class="bg-gradient-to-br from-primary to-primary-dark p-6 rounded-3xl shadow-lg shadow-primary/20 flex flex-col justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <h4 class="text-white font-black text-lg leading-tight mb-2 flex items-center gap-2">
                    Precisa de Ajuda?
                    <x-icon name="circle-question" style="solid" class="text-white/50 animate-pulse" />
                </h4>
                <p class="text-white/80 text-xs font-medium">Consulte a base de conhecimento técnica em caso de dúvidas.</p>
            </div>
            <a href="{{ route('support.wiki.index') }}" class="relative z-10 mt-4 w-full py-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold text-xs rounded-xl transition-all border border-white/10 flex items-center justify-center">
                Acessar Wiki
            </a>
            <!-- Decorative Icon -->
            <x-icon name="headset" style="duotone" class="absolute -right-4 -bottom-4 w-24 h-24 text-white/5 rotate-12 group-hover:scale-125 transition-transform duration-700" />
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Tickets Table -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="font-black text-slate-800 dark:text-white flex items-center gap-2 uppercase tracking-wider text-xs">
                    <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                    Chamados Recentes
                </h3>
                <a href="{{ route('support.tickets.index') }}" class="text-primary hover:text-primary-dark text-xs font-bold flex items-center gap-1 group">
                    Explorar todos
                    <x-icon name="arrow-right" class="group-hover:translate-x-1 transition-transform" />
                </a>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto min-h-[400px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-slate-800/20">
                                <th class="px-6 py-4">ID / Assunto</th>
                                <th class="px-6 py-4">Usuário</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800 text-sm">
                            @forelse($recentTickets as $ticket)
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-all cursor-pointer" onclick="window.location='{{ route('support.tickets.show', $ticket) }}'">
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-primary mb-1 inline-flex items-center gap-1">
                                                <x-icon name="hashtag" class="text-[8px]" /> {{ $ticket->id }}
                                            </span>
                                            <span class="font-bold text-slate-700 dark:text-gray-200 line-clamp-1 truncate w-[200px]">{{ $ticket->subject }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                @if($ticket->user->photo)
                                                    <img src="{{ asset('storage/' . $ticket->user->photo) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-gray-100 dark:ring-slate-800" />
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 flex items-center justify-center font-black text-xs">
                                                        {{ substr($ticket->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-900"></div>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-800 dark:text-gray-200 text-xs">{{ $ticket->user->name }}</span>
                                                <span class="text-[10px] text-gray-400 font-medium">{{ $ticket->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @php
                                            $statusThemes = [
                                                'open' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 ring-emerald-100 dark:ring-emerald-500/20',
                                                'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 ring-amber-100 dark:ring-amber-500/20',
                                                'answered' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 ring-blue-100 dark:ring-blue-500/20',
                                                'closed' => 'bg-gray-50 text-gray-600 dark:bg-slate-800 dark:text-gray-400 ring-gray-100 dark:ring-slate-700',
                                            ];
                                            $statusTxt = ['open' => 'Aberto', 'pending' => 'Esperando', 'answered' => 'Respondido', 'closed' => 'Fechado'];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ring-1 {{ $statusThemes[$ticket->status] ?? $statusThemes['closed'] }}">
                                            {{ $statusTxt[$ticket->status] ?? $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('support.tickets.show', $ticket) }}" class="p-2 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all scale-90 group-hover:scale-100">
                                                <x-icon name="arrow-right" style="solid" />
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center opacity-20">
                                            <x-icon name="inbox-full" style="duotone" class="w-16 h-16 mb-4" />
                                            <p class="font-black text-sm uppercase tracking-widest">Nenhum chamado pendente</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Activity / Stats -->
        <div class="space-y-6">
            <h3 class="font-black text-slate-800 dark:text-white flex items-center gap-2 uppercase tracking-wider text-xs px-2">
                Prioridades Atuais
            </h3>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
                <!-- Priority Item 1 -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-8 bg-red-500 rounded-full"></div>
                        <div>
                            <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">Alta Prioridade</p>
                            <p class="text-[10px] text-gray-400 font-medium">Chamados críticos</p>
                        </div>
                    </div>
                    <span class="text-lg font-black text-slate-800 dark:text-white">{{ $highPriority }}</span>
                </div>

                <!-- Progress Bar Mockup (Optional visualization) -->
                <div class="space-y-2">
                    <div class="flex justify-between text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest px-1">
                        <span>Carga de Trabalho</span>
                        <span>{{ min(100, (($openTickets + $pendingTickets) * 10)) }}%</span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ min(100, (($openTickets + $pendingTickets) * 10)) }}%"></div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-50 dark:border-gray-800 flex flex-col gap-3">
                    <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Ações Rápidas</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('support.wiki.index') }}" class="p-3 bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-2xl flex flex-col items-center gap-1 transition-all group">
                            <x-icon name="book-open-reader" style="duotone" class="text-primary group-hover:scale-110 transition-transform" />
                            <span class="text-[9px] font-bold text-slate-700 dark:text-gray-300">Wiki Técnica</span>
                        </a>
                        <a href="{{ route('support.reports.index') }}" class="p-3 bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-2xl flex flex-col items-center gap-1 transition-all group">
                            <x-icon name="file-chart-pie" style="duotone" class="text-primary group-hover:scale-110 transition-transform" />
                            <span class="text-[9px] font-bold text-slate-700 dark:text-gray-300">Relatórios</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-panelsuporte::layouts.master>
