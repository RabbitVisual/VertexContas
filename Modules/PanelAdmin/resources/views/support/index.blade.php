<x-paneladmin::layouts.master>
    <div class="space-y-8 animate-in fade-in duration-500">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center">
                        <x-icon name="headset" style="duotone" />
                    </div>
                    Central de Suporte
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1 ml-1">Gestão inteligente e global de todos os chamados.</p>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white dark:bg-[#111111] rounded-[3rem] p-8 border border-gray-100 dark:border-white/5 shadow-2xl">
            <form action="{{ route('admin.support.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Status do Chamado</label>
                    <div class="relative group">
                        <select name="status" class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-[1.5rem] focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm appearance-none transition-all cursor-pointer">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Todos os Status</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Aberto</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Respondido</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Fechado</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-[#11C76F] transition-colors">
                            <x-icon name="chevron-down" class="text-[10px]" />
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nível de Prioridade</label>
                    <div class="relative group">
                        <select name="priority" class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-[1.5rem] focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm appearance-none transition-all cursor-pointer">
                            <option value="all" {{ request('priority') == 'all' ? 'selected' : '' }}>Todas as Prioridades</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Baixa</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Média</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-[#11C76F] transition-colors">
                            <x-icon name="chevron-down" class="text-[10px]" />
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Responsável</label>
                    <div class="relative group">
                        <select name="agent" class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-[1.5rem] focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm appearance-none transition-all cursor-pointer">
                            <option value="all" {{ request('agent') == 'all' ? 'selected' : '' }}>Qualquer Agente</option>
                            <option value="unassigned" {{ request('agent') == 'unassigned' ? 'selected' : '' }}>Sem Atribuição</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request('agent') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-[#11C76F] transition-colors">
                            <x-icon name="chevron-down" class="text-[10px]" />
                        </div>
                    </div>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full py-4 bg-[#11C76F] text-white font-black rounded-[1.5rem] shadow-xl shadow-[#11C76F]/25 hover:bg-[#0EA85A] hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                        <x-icon name="magnifying-glass" class="text-sm" /> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Tickets Table Area -->
        <div class="bg-white dark:bg-[#111111] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/[0.02]">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informações do Chamado</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Prioridade</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Responsável</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-white/[0.05]">
                        @forelse($tickets as $ticket)
                            <tr class="group hover:bg-[#11C76F]/[0.02] dark:hover:bg-[#11C76F]/[0.05] transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="relative shrink-0">
                                            @if($ticket->user->photo)
                                                <img src="{{ $ticket->user->photo_url }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                                            @else
                                                <div class="w-12 h-12 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-black text-sm border-2 border-[#11C76F]/20">
                                                    {{ substr($ticket->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white dark:border-[#111111] shadow-sm"></div>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-900 dark:text-white text-base group-hover:text-[#11C76F] transition-colors line-clamp-1">{{ $ticket->subject }}</span>
                                            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $ticket->user->name }}</span>
                                                @if($ticket->user->isPro())
                                                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded text-[9px] font-black uppercase bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">
                                                        <x-icon name="crown" style="solid" class="w-2.5 h-2.5" /> PRO
                                                    </span>
                                                @endif
                                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                <span class="text-[10px] text-slate-400 font-bold tracking-tight">#{{ $ticket->id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $priorityTheme = [
                                            'low' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'medium' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                            'high' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400',
                                        ];
                                        $priorityLabel = [
                                            'low' => 'Baixa',
                                            'medium' => 'Média',
                                            'high' => 'Alta',
                                        ];
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $priorityTheme[$ticket->priority] }}">
                                        {{ $priorityLabel[$ticket->priority] }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $statusTheme = [
                                            'open' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                            'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                            'answered' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                                            'closed' => 'bg-slate-100 text-slate-600 dark:bg-white/5 dark:text-slate-400',
                                        ];
                                         $statusLabel = [
                                            'open' => 'Aberto',
                                            'pending' => 'Pendente',
                                            'answered' => 'Respondido',
                                            'closed' => 'Fechado',
                                        ];
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $statusTheme[$ticket->status] }}">
                                        {{ $statusLabel[$ticket->status] }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($ticket->assignedAgent)
                                        <div class="flex items-center justify-center gap-3">
                                            @if($ticket->assignedAgent->photo)
                                                <img src="{{ $ticket->assignedAgent->photo_url }}" class="w-8 h-8 rounded-xl object-cover border-2 border-indigo-500/20">
                                            @else
                                                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-[10px] font-black border border-indigo-500/20">
                                                    {{ substr($ticket->assignedAgent->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $ticket->assignedAgent->name }}</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center">
                                            <span class="text-[9px] font-black text-slate-300 uppercase italic tracking-widest">Aguardando</span>
                                            <div class="h-1 w-8 bg-slate-100 dark:bg-white/5 rounded-full mt-1"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                        <a href="{{ route('admin.support.show', $ticket) }}" class="w-10 h-10 rounded-[1rem] bg-gray-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-[#11C76F] hover:text-white transition-all flex items-center justify-center shadow-sm">
                                            <x-icon name="comment-dots" class="text-xs" />
                                        </a>
                                        @if($ticket->assigned_agent_id !== Auth::id())
                                            <form action="{{ route('admin.support.takeover', $ticket) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-10 h-10 rounded-[1rem] bg-[#11C76F]/10 text-[#11C76F] hover:bg-[#11C76F] hover:text-white transition-all flex items-center justify-center shadow-sm" title="Assumir este ticket">
                                                    <x-icon name="handshake" class="text-xs" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center max-w-xs mx-auto">
                                        <div class="w-24 h-24 rounded-[2rem] bg-gray-50 dark:bg-white/[0.02] flex items-center justify-center mb-6">
                                            <x-icon name="mailbox-empty" class="text-5xl text-slate-200 dark:text-white/10" />
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Nenhum chamado pendente</h3>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 font-medium leading-relaxed">Não encontramos nenhum chamado que corresponda aos filtros selecionados no momento.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-white/5 border-t border-gray-100 dark:border-white/5">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-paneladmin::layouts.master>
