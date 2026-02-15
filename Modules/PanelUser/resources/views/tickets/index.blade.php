@php
    $isPro = $isPro ?? auth()->user()?->isPro() ?? false;
    $stats = $stats ?? ['total' => 0, 'abertos' => 0, 'pendentes' => 0, 'resolvidos' => 0];
@endphp
<x-paneluser::layouts.master :title="'Central de Ajuda'">
    <div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 px-4 pb-12">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary-500/5 dark:bg-primary-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-primary-600 dark:text-primary-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                        <span>Suporte</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">Central de Ajuda</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Seus <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-emerald-600 dark:from-primary-400 dark:to-emerald-400">Chamados</span></h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">
                        @if($isPro)
                            Seus chamados têm prioridade no atendimento. Exporte o histórico quando quiser.
                        @else
                            Gerencie seus chamados e obtenha suporte da nossa equipe.
                        @endif
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 shrink-0">
                    @if($isPro)
                        <a href="{{ route('user.tickets.export') }}"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                            <x-icon name="file-arrow-down" style="duotone" class="w-5 h-5" />
                            Exportar CSV
                        </a>
                    @endif
                    <a href="{{ route('user.tickets.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-primary-500/20">
                        <x-icon name="plus" style="solid" class="w-5 h-5" />
                        Novo Chamado
                    </a>
                </div>
            </div>

            @if($isPro)
                <div class="relative z-10 mt-8 pt-8 border-t border-gray-200 dark:border-white/5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0">
                            <x-icon name="ticket" style="duotone" class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total</p>
                            <p class="text-lg font-black text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                            <x-icon name="folder-open" style="duotone" class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Abertos</p>
                            <p class="text-lg font-black text-blue-600 dark:text-blue-400">{{ $stats['abertos'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                            <x-icon name="clock" style="duotone" class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pendentes</p>
                            <p class="text-lg font-black text-amber-600 dark:text-amber-400">{{ $stats['pendentes'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                            <x-icon name="circle-check" style="duotone" class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Resolvidos</p>
                            <p class="text-lg font-black text-emerald-600 dark:text-emerald-400">{{ $stats['resolvidos'] }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if(!$isPro)
            <div class="rounded-3xl border border-amber-200 dark:border-amber-500/20 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-transparent p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                        <x-icon name="crown" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Suporte Prioritário com Vertex PRO</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Exporte histórico, tenha atendimento VIP e respostas mais rápidas.</p>
                    </div>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm transition-all">
                    <x-icon name="sparkles" style="duotone" class="w-4 h-4" />
                    Fazer Upgrade
                </a>
            </div>
        @endif

        {{-- Lista de Chamados --}}
        <div class="grid grid-cols-1 gap-6">
            @forelse($tickets as $ticket)
                @php
                    $iconClass = match($ticket->status) {
                        'closed' => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400',
                        'open' => 'bg-blue-500/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400',
                        'pending' => 'bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400',
                        default => 'bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400'
                    };
                    $statusConfig = [
                        'open' => ['bg' => 'bg-blue-100 dark:bg-blue-500/20', 'text' => 'Aberto'],
                        'pending' => ['bg' => 'bg-amber-100 dark:bg-amber-500/20', 'text' => 'Pendente'],
                        'answered' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/20', 'text' => 'Respondido'],
                        'resolved' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/20', 'text' => 'Resolvido'],
                        'closed' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'Fechado'],
                    ];
                    $config = $statusConfig[$ticket->status] ?? $statusConfig['closed'];
                @endphp
                <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-primary-500/30 shadow-sm hover:shadow-xl">
                    <div class="flex flex-col lg:flex-row items-stretch">
                        {{-- Data / ID --}}
                        <div class="lg:w-36 bg-gray-50 dark:bg-gray-900 p-6 flex flex-row lg:flex-col items-center justify-center gap-3 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                            <span class="text-xs font-black text-primary-600 dark:text-primary-500 uppercase tracking-[0.2em]">#{{ $ticket->id }}</span>
                            <span class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">{{ $ticket->created_at->format('d') }}</span>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">{{ $ticket->created_at->translatedFormat('M Y') }}</span>
                        </div>

                        {{-- Conteúdo --}}
                        <div class="flex-1 p-6 lg:p-8">
                            <div class="flex flex-col h-full justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $config['bg'] }} {{ $ticket->status === 'closed' ? 'text-gray-600 dark:text-gray-400' : 'text-gray-700 dark:text-gray-300' }}">{{ $config['text'] }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $ticket->priority === 'high' ? 'Alta' : ($ticket->priority === 'medium' ? 'Média' : 'Baixa') }} prioridade</span>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors mb-2 leading-tight">{{ $ticket->subject }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">{{ Str::limit($ticket->messages->first()?->message ?? 'Sem descrição', 80) }}</p>
                                </div>

                                @if($isPro && $ticket->assignedAgent)
                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        <x-icon name="headset" style="solid" class="w-3.5 h-3.5" />
                                        <span>Atendente: {{ $ticket->assignedAgent->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Ação --}}
                        <div class="lg:w-40 p-6 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                            <a href="{{ route('user.tickets.show', $ticket) }}" class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-primary-500/20">
                                Ver chamado
                                <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-24 text-center rounded-[2rem] border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50">
                    <div class="w-24 h-24 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-700 mb-6 shadow-sm border border-gray-100 dark:border-none">
                        <x-icon name="ticket" style="duotone" class="w-12 h-12 opacity-40 dark:opacity-20" />
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 leading-tight">Nenhum chamado</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Você ainda não abriu nenhum chamado. Precisa de ajuda com algo?</p>
                    <a href="{{ route('user.tickets.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition-all shadow-lg shadow-primary-500/20">
                        <x-icon name="plus" style="solid" class="w-5 h-5" />
                        Abrir primeiro chamado
                    </a>
                </div>
            @endforelse
        </div>

        @if($tickets->hasPages())
            <div class="flex justify-center pt-8">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</x-paneluser::layouts.master>
