<x-paneluser::layouts.master :title="__('Help Center')">
    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-7xl mx-auto space-y-8 px-6 pt-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <li>Painel</li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="text-primary">Central de Ajuda</li>
                        </ol>
                    </nav>
                    <div class="flex flex-wrap items-center gap-3 mb-1">
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Central de Ajuda</h1>
                        @if($isPro ?? false)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                                <x-icon name="crown" style="solid" class="w-3.5 h-3.5" /> Suporte VIP
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">
                        @if($isPro ?? false)
                            Seus chamados têm prioridade no atendimento. Exporte o histórico quando quiser.
                        @else
                            Gerencie seus chamados e obtenha suporte da nossa equipe.
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if($isPro ?? false)
                        <a href="{{ route('user.tickets.export') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm">
                            <x-icon name="file-arrow-down" style="solid" class="w-4 h-4" />
                            Exportar CSV
                        </a>
                    @endif
                    <a href="{{ route('user.tickets.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95 group">
                    <x-icon name="plus" style="solid" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                    Novo Chamado
                </a>
                </div>
            </div>

            @if(!($isPro ?? false))
                <div class="p-6 bg-gradient-to-br from-slate-900 to-slate-800 dark:from-slate-800 dark:to-slate-900 rounded-3xl border border-slate-700/50 relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-40 h-40 bg-amber-500/20 rounded-full blur-3xl"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="p-3 bg-amber-500/20 rounded-xl shrink-0">
                            <x-icon name="crown" style="solid" class="text-amber-400 w-8 h-8" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-white text-sm">Suporte Prioritário com Vertex PRO</h3>
                            <p class="text-slate-400 text-xs mt-0.5">Exporte seu histórico de chamados, tenha atendimento VIP e respostas mais rápidas.</p>
                        </div>
                        <a href="{{ route('user.subscription.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold rounded-xl text-sm transition-all">
                            <x-icon name="rocket" style="solid" class="w-4 h-4" />
                            Fazer Upgrade
                        </a>
                    </div>
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-primary/10 rounded-xl">
                        <x-icon name="ticket" style="solid" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Total</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $tickets->count() }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                        <x-icon name="check-circle" style="solid" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Resolvidos</p>
                        <p class="text-xl font-bold text-emerald-700 dark:text-emerald-400">{{ $tickets->where('status', 'resolved')->count() + $tickets->where('status', 'closed')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                        <x-icon name="clock" style="solid" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Pendentes</p>
                        <p class="text-xl font-bold text-amber-700 dark:text-amber-400">{{ $tickets->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                        <x-icon name="folder-open" style="solid" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Abertos</p>
                        <p class="text-xl font-bold text-blue-700 dark:text-blue-400">{{ $tickets->where('status', 'open')->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Ticket List Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2.5 bg-primary/10 rounded-xl">
                        <x-icon name="headset" style="solid" class="text-primary w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Histórico de Chamados</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Seus tickets e respostas da equipe</p>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($tickets as $ticket)
                        <a href="{{ route('user.tickets.show', $ticket) }}" class="block group p-6 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4 min-w-0 flex-1">
                                    @php
                                        $lastMessage = $ticket->messages->last();
                                        $lastUser = $lastMessage ? $lastMessage->user : $ticket->user;
                                        $isSupport = $lastMessage ? $lastMessage->is_admin_reply : false;
                                        $iconClass = match($ticket->status) {
                                            'closed' => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400',
                                            'open' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                                            'pending' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
                                            default => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
                                        };
                                    @endphp

                                    <div class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl {{ $iconClass }}">
                                        @if($lastUser && $lastUser->photo)
                                            <img src="{{ asset('storage/' . $lastUser->photo) }}" class="w-full h-full rounded-xl object-cover" title="{{ $lastUser->name }}" alt="" />
                                        @elseif($isSupport)
                                            <x-icon name="headset" style="solid" class="w-5 h-5" />
                                        @else
                                            <x-icon name="{{ $ticket->status === 'closed' ? 'lock' : 'ticket' }}" style="solid" class="w-5 h-5" />
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">#{{ $ticket->id }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                            <span class="text-[10px] font-bold text-gray-400">{{ $ticket->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors mb-1">{{ $ticket->subject }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-slate-400 line-clamp-1">
                                            {{ $ticket->messages->first()->message ?? __('Sem descrição...') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    @php
                                        $statusConfig = [
                                            'open' => ['bg' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300', 'text' => 'Aberto'],
                                            'pending' => ['bg' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300', 'text' => 'Pendente'],
                                            'answered' => ['bg' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300', 'text' => 'Respondido'],
                                            'resolved' => ['bg' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300', 'text' => 'Resolvido'],
                                            'closed' => ['bg' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400', 'text' => 'Fechado'],
                                        ];
                                        $config = $statusConfig[$ticket->status] ?? $statusConfig['closed'];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $config['bg'] }}">
                                        {{ $config['text'] }}
                                    </span>
                                    <x-icon name="chevron-right" style="solid" class="text-gray-300 group-hover:text-primary transition-colors w-5 h-5" />
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-16 text-center">
                            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                                <x-icon name="inbox" style="solid" class="w-10 h-10 text-slate-400 dark:text-slate-500" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Nenhum chamado encontrado</h3>
                            <p class="text-gray-500 dark:text-slate-400 text-sm max-w-sm mx-auto mb-6">Você ainda não abriu nenhum chamado. Precisa de ajuda?</p>
                            <a href="{{ route('user.tickets.create') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
                                <x-icon name="plus" style="solid" class="w-5 h-5" />
                                Abrir primeiro chamado
                            </a>
                        </div>
                    @endforelse
                </div>

                @if($tickets->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
