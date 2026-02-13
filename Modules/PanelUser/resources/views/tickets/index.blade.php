@section('title', 'Central de Ajuda')

<x-paneluser::layouts.master>
    <div class="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-500">

        <!-- Hero Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Central de Ajuda</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Gerencie suas solicitações e tire suas dúvidas.</p>
            </div>
            <a href="{{ route('user.tickets.create') }}" class="group relative px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl shadow-lg shadow-primary/25 transition-all hover:scale-105 active:scale-95 flex items-center gap-3 overflow-hidden">
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                <x-icon name="plus" class="w-4 h-4 relative z-10" />
                <span class="relative z-10">Novo Chamado</span>
            </a>
        </div>

        <!-- Stats / Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center justify-center text-center">
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total</span>
                <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $tickets->count() }}</span>
            </div>
             <div class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-500/20 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">Resolvidos</span>
                <span class="text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ $tickets->where('status', 'resolved')->count() + $tickets->where('status', 'closed')->count() }}</span>
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/10 p-4 rounded-2xl border border-amber-100 dark:border-amber-500/20 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-1">Pendente</span>
                <span class="text-2xl font-black text-amber-700 dark:text-amber-300">{{ $tickets->where('status', 'pending')->count() }}</span>
            </div>
             <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-2xl border border-blue-100 dark:border-blue-500/20 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Em Aberto</span>
                <span class="text-2xl font-black text-blue-700 dark:text-blue-300">{{ $tickets->where('status', 'open')->count() }}</span>
            </div>
        </div>

        <!-- Ticket List Timeline -->
        <div class="space-y-4">
            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Histórico de Atendimentos</h3>

            <div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                @forelse($tickets as $ticket)
                    <a href="{{ route('user.tickets.show', $ticket) }}" class="block group relative border-b border-gray-50 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors p-6">
                        <div class="flex items-start justify-between gap-4">
                            <!-- Icon & Content -->
                            <div class="flex items-start gap-4">
                                @php
                                    $lastMessage = $ticket->messages->last();
                                    $lastUser = $lastMessage ? $lastMessage->user : $ticket->user;
                                    $isSupport = $lastMessage ? $lastMessage->is_admin_reply : false;
                                @endphp

                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm relative
                                    {{ (!$lastUser || !$lastUser->photo) && !$isSupport ?
                                        ($ticket->status === 'closed' ? 'bg-gray-100 dark:bg-slate-700 text-gray-400' :
                                        ($ticket->status === 'open' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-500' :
                                        ($ticket->status === 'pending' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-500' :
                                        'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500')))
                                        : 'bg-white dark:bg-slate-800' /* Reset bg if photo exists */
                                    }}
                                ">
                                    @if($lastUser && $lastUser->photo)
                                        <img src="{{ asset('storage/' . $lastUser->photo) }}" class="w-full h-full rounded-2xl object-cover ring-1 ring-gray-100 dark:ring-slate-700" title="{{ $lastUser->name }}" />
                                        @if($isSupport)
                                             <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-primary border-2 border-white dark:border-slate-800 flex items-center justify-center shadow-sm">
                                                <x-icon name="headset" style="solid" class="text-[10px] text-white" />
                                            </div>
                                        @endif
                                    @else
                                        @if($isSupport)
                                             <div class="w-full h-full rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
                                                <x-icon name="headset" style="duotone" class="text-xl" />
                                             </div>
                                        @else
                                            <x-icon name="{{ $ticket->status === 'closed' ? 'lock' : 'ticket' }}" style="{{ $ticket->status === 'closed' ? 'solid' : 'duotone' }}" class="text-xl" />
                                        @endif
                                    @endif
                                </div>

                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">#{{ $ticket->id }}</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                        <span class="text-[10px] font-bold text-gray-400">{{ $ticket->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors mb-1">{{ $ticket->subject }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                                        {{ $ticket->messages->first()->message ?? 'Sem descrição...' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status & Arrow -->
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                @php
                                    $statusConfig = [
                                        'open' => ['bg' => 'bg-blue-100 text-blue-700', 'text' => 'Aberto'],
                                        'pending' => ['bg' => 'bg-amber-100 text-amber-700', 'text' => 'Pendente'],
                                        'answered' => ['bg' => 'bg-emerald-100 text-emerald-700', 'text' => 'Respondido'],
                                        'closed' => ['bg' => 'bg-gray-100 text-gray-600', 'text' => 'Fechado'],
                                    ];
                                    $config = $statusConfig[$ticket->status] ?? $statusConfig['closed'];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $config['bg'] }}">
                                    {{ $config['text'] }}
                                </span>
                                <x-icon name="chevron-right" class="text-gray-300 group-hover:text-primary transition-colors text-sm" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-20 h-20 bg-gray-50 dark:bg-slate-700/50 rounded-full flex items-center justify-center mb-4">
                            <x-icon name="inbox" style="duotone" class="text-4xl text-gray-300 dark:text-slate-600" />
                        </div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-1">Nenhum chamado encontrado</h3>
                        <p class="text-gray-500 text-sm max-w-xs mx-auto">Você ainda não abriu nenhuma solicitação de suporte. Precisa de ajuda?</p>
                        <a href="{{ route('user.tickets.create') }}" class="mt-6 text-sm font-bold text-primary hover:text-primary-dark uppercase tracking-widest">Abrir primeiro chamado</a>
                    </div>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
