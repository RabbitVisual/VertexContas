<x-panelsuporte::layouts.master>




<div class="space-y-8 animate-in fade-in duration-500">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <div class="p-2.5 bg-primary/10 rounded-2xl text-primary">
                    <x-icon name="list-tree" style="duotone" class="text-2xl" />
                </div>
                Gestão de Chamados
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 font-medium">Visualize e responda a todos os tickets de suporte.</p>
        </div>

        <!-- Advanced Filters -->
        <div class="bg-white dark:bg-slate-900 p-2 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-2">
            <form action="{{ route('support.tickets.index') }}" method="GET" class="flex flex-wrap items-center gap-2" id="filterForm">
                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="pl-10 pr-8 py-2 bg-gray-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold text-slate-700 dark:text-gray-300 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                        <option value="all">Status: Todos</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Abertos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                        <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Respondidos</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Fechados</option>
                    </select>
                    <x-icon name="filter-list" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs" />
                </div>

                <div class="relative">
                    <select name="priority" onchange="this.form.submit()" class="pl-10 pr-8 py-2 bg-gray-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold text-slate-700 dark:text-gray-300 focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                        <option value="all">Prioridade: Todas</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Média</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Baixa</option>
                    </select>
                    <x-icon name="sort" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs" />
                </div>

                @if(request()->anyFilled(['status', 'priority']))
                    <a href="{{ route('support.tickets.index') }}" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-colors" title="Limpar Filtros">
                        <x-icon name="xmark" style="solid" />
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Ticket List -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden min-h-[500px] flex flex-col">
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-slate-800/20">
                        <th class="px-8 py-5">Identificação</th>
                        <th class="px-8 py-5">Usuário Solicitante</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Nível</th>
                        <th class="px-8 py-5">Última Atividade</th>
                        <th class="px-8 py-5 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800 text-sm italic-none">
                    @forelse($tickets as $ticket)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-all cursor-pointer" onclick="window.location='{{ route('support.tickets.show', $ticket) }}'">
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[11px] font-black text-primary px-2 py-0.5 bg-primary/5 rounded-lg w-fit">#{{ $ticket->id }}</span>
                                    <span class="font-bold text-slate-800 dark:text-white leading-tight break-words max-w-[250px]">{{ $ticket->subject }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        @if($ticket->user->photo)
                                            <img src="{{ asset('storage/' . $ticket->user->photo) }}" class="w-11 h-11 rounded-2xl object-cover ring-4 ring-gray-100 dark:ring-slate-800 shadow-sm" />
                                        @else
                                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-800 dark:to-slate-700 text-gray-500 dark:text-gray-400 flex items-center justify-center font-black text-xs shadow-sm">
                                                {{ substr($ticket->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-1 -right-1 w-4.5 h-4.5 rounded-full bg-emerald-500 border-4 border-white dark:border-slate-900"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $ticket->user->name }}</span>
                                        <span class="text-[11px] text-gray-400 font-medium">{{ $ticket->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $statusThemes = [
                                        'open' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 ring-emerald-100 dark:ring-emerald-500/20',
                                        'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 ring-amber-100 dark:ring-amber-500/20',
                                        'answered' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 ring-blue-100 dark:ring-blue-500/20',
                                        'closed' => 'bg-gray-50 text-gray-600 dark:bg-slate-800 dark:text-gray-400 ring-gray-100 dark:ring-slate-700',
                                    ];
                                    $statusTxt = ['open' => 'Aberto', 'pending' => 'Pendente', 'answered' => 'Respondido', 'closed' => 'Fechado'];
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.1em] ring-1 inline-flex items-center gap-1.5 {{ $statusThemes[$ticket->status] ?? $statusThemes['closed'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $statusTxt[$ticket->status] ?? $ticket->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $priorityThemes = [
                                        'high' => 'text-red-600 bg-red-50 dark:bg-red-500/10 ring-red-100 dark:ring-red-500/20',
                                        'medium' => 'text-amber-600 bg-amber-50 dark:bg-amber-500/10 ring-amber-100 dark:ring-amber-500/20',
                                        'low' => 'text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 ring-emerald-100 dark:ring-emerald-500/20',
                                    ];
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.1em] ring-1 inline-flex items-center gap-1.5 {{ $priorityThemes[$ticket->priority] ?? 'bg-gray-50 text-gray-600' }}">
                                    <x-icon name="signal" class="text-[10px]" />
                                    {{ $ticket->priority === 'high' ? 'Alta' : ($ticket->priority === 'medium' ? 'Média' : 'Baixa') }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-slate-600 dark:text-gray-400 flex items-center gap-2">
                                    <x-icon name="calendar-days" style="duotone" class="text-primary/50" />
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('support.tickets.show', $ticket) }}" class="inline-flex items-center justify-center p-3 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-2xl transition-all shadow-sm hover:shadow-primary/20">
                                    <x-icon name="arrow-right" style="solid" class="text-lg" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center max-w-sm mx-auto">
                                    <div class="w-24 h-24 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                                        <x-icon name="inbox-full" style="duotone" class="text-5xl text-gray-200 dark:text-slate-700" />
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-tight">Nenhum Chamado Encontrado</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Tente ajustar seus filtros ou verifique mais tarde se novos chamados foram abertos.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-slate-800/10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                        Exibindo {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} de {{ $tickets->total() }} resultados
                    </p>
                    {{ $tickets->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
</x-panelsuporte::layouts.master>
