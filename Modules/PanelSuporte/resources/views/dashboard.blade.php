<x-panelsuporte::layouts.master>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-8 text-white relative overflow-hidden shadow-lg group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-primary/30 transition-all duration-1000"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-black uppercase tracking-widest backdrop-blur-sm border border-white/10">
                            Painel do Agente
                        </span>
                        <span class="text-xs text-gray-400 font-medium">{{ now()->format('d M, Y') }}</span>
                    </div>

                    <h1 class="text-3xl font-black mb-2 tracking-tight">
                        Olá, {{ auth()->user()->first_name }} 👋
                    </h1>
                    <p class="text-gray-400 text-sm max-w-md leading-relaxed">
                        Você tem <strong class="text-white">{{ $openTickets }} chamados abertos</strong> e <strong class="text-white">{{ $pendingTickets }} pendentes</strong>. Mantenha o foco na excelência!
                    </p>
                </div>

                <div class="absolute bottom-6 right-8 opacity-50 group-hover:opacity-100 group-hover:translate-x-2 transition-all duration-500">
                    <x-icon name="headset" style="duotone" class="w-24 h-24 text-white/10" />
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="flex items-center justify-between px-2">
                <h2 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2">
                    <x-icon name="ticket" class="text-primary" />
                    Chamados Recentes
                </h2>
                <a href="{{ route('support.tickets.index') }}" class="group flex items-center gap-1 text-xs font-bold text-gray-400 hover:text-primary transition-colors uppercase tracking-wider">
                    Ver todos
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

            <!-- Blog Comments Widget -->
            <div class="flex items-center justify-between px-2 pt-4">
                <h2 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2">
                    <x-icon name="comments" class="text-indigo-500" />
                    Comentários Pendentes
                </h2>
                <a href="{{ route('suporte.blog.comments') }}" class="group flex items-center gap-1 text-xs font-bold text-gray-400 hover:text-indigo-500 transition-colors uppercase tracking-wider">
                    Ver todos
                    <x-icon name="arrow-right" class="group-hover:translate-x-1 transition-transform" />
                </a>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($pendingComments as $comment)
                        <div class="p-6 flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-gray-500">
                                    {{ substr($comment->user->name ?? 'U', 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $comment->user->name ?? 'Usuário Desconhecido' }}
                                    <span class="text-gray-400 font-normal">em</span>
                                    <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">{{ $comment->post->title }}</a>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $comment->content }}</p>
                                <div class="mt-2 flex space-x-3">
                                    <form action="{{ route('suporte.blog.comments.approve', $comment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Aprovar</button>
                                    </form>
                                    <form action="{{ route('suporte.blog.comments.reject', $comment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-700">Rejeitar</button>
                                    </form>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            Nenhum comentário pendente.
                        </div>
                    @endforelse
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
</x-panelsuporte::layouts.master>
