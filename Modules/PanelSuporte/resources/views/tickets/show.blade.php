<x-panelsuporte::layouts.master>




<div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
    <!-- Breadcrumbs & Brief Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('support.tickets.index') }}" class="group flex items-center justify-center p-3 bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-gray-400 hover:text-primary transition-all shadow-sm">
                <x-icon name="arrow-left-long" style="solid" class="group-hover:-translate-x-1 transition-transform" />
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight italic-none">Ticket #{{ $ticket->id }}</h1>
                    @if($ticket->user->isPro())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                            <x-icon name="crown" style="solid" class="w-3.5 h-3.5" /> Cliente PRO
                        </span>
                    @endif
                    @php
                        $statusThemes = [
                            'open' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 ring-emerald-100 dark:ring-emerald-500/20',
                            'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 ring-amber-100 dark:ring-amber-500/20',
                            'answered' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 ring-blue-100 dark:ring-blue-500/20',
                            'closed' => 'bg-gray-50 text-gray-600 dark:bg-slate-800 dark:text-gray-400 ring-gray-100 dark:ring-slate-700',
                        ];
                        $statusTxt = ['open' => 'Aberto', 'pending' => 'Pendente', 'answered' => 'Respondido', 'closed' => 'Fechado'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest ring-1 {{ $statusThemes[$ticket->status] ?? $statusThemes['closed'] }}">
                        {{ $statusTxt[$ticket->status] ?? $ticket->status }}
                    </span>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-[11px] font-black uppercase tracking-widest mt-1">Assunto: {{ $ticket->subject }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
             <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Prioridade:</span>
             @php
                $priorityThemes = [
                    'high' => 'text-red-600 bg-red-50 dark:bg-red-500/10 ring-red-100 dark:ring-red-500/20',
                    'medium' => 'text-amber-600 bg-amber-50 dark:bg-amber-500/10 ring-amber-100 dark:ring-amber-500/20',
                    'low' => 'text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 ring-emerald-100 dark:ring-emerald-500/20',
                ];
            @endphp
            <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] ring-1 inline-flex items-center gap-1.5 {{ $priorityThemes[$ticket->priority] ?? 'bg-gray-50 text-gray-600' }}">
                <x-icon name="signal-bars" class="text-[10px]" />
                {{ $ticket->priority === 'high' ? 'Alta' : ($ticket->priority === 'medium' ? 'Média' : 'Baixa') }}
            </span>

            @if($ticket->status !== 'closed')
                <form action="{{ route('support.tickets.close', $ticket) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja encerrar este atendimento?');">
                    @csrf
                    <button type="submit" class="px-4 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest ring-1 ring-red-100 transition-all flex items-center gap-2">
                        <x-icon name="lock" style="solid" class="text-xs" />
                        Encerrar
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <!-- Main Conversation Area -->
        <div class="xl:col-span-3 space-y-8">
            <div class="bg-white/50 dark:bg-slate-900/50 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-sm backdrop-blur-sm overflow-hidden">
                <div
                    id="ticket-chat-support"
                    class="flex-1"
                    data-ticket-id="{{ $ticket->id }}"
                    data-post-url="{{ route('support.tickets.reply', $ticket) }}"
                    data-messages-url="{{ route('support.tickets.messages', $ticket) }}"
                    data-initial-messages="{{ json_encode($initialMessagesForVue ?? []) }}"
                    data-current-user-id="{{ auth()->id() }}"
                    data-is-closed="{{ $ticket->status === 'closed' ? '1' : '0' }}"
                    data-can-reply="{{ $ticket->status !== 'closed' ? '1' : '0' }}"
                    data-context="support"
                    data-placeholder="Escreva aqui sua resposta técnica..."
                    data-is-pro="{{ $ticket->user->isPro() ? '1' : '0' }}"
                    data-status-options="{{ json_encode([
                        ['value' => 'answered', 'label' => 'Resolvido / Respondido'],
                        ['value' => 'pending', 'label' => 'Aguardar Cliente'],
                        ['value' => 'open', 'label' => 'Reabrir / Manter Aberto'],
                    ]) }}"
                    data-csrf="{{ csrf_token() }}"
                ></div>
            </div>

            @if($ticket->status === 'closed')
                <div class="bg-gray-50 dark:bg-slate-900 rounded-[2.5rem] p-10 border border-gray-100 dark:border-gray-800 shadow-inner text-center">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <x-icon name="lock" style="solid" class="text-2xl" />
                    </div>
                    <h3 class="text-lg font-black text-slate-700 dark:text-gray-300 uppercase tracking-widest mb-2">Atendimento Encerrado</h3>
                    <p class="text-sm text-gray-500 font-medium">Este chamado foi finalizado em {{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y \à\s H:i') : 'Data desconhecida' }} pelo agente {{ $ticket->closedBy ? $ticket->closedBy->name : 'Sistema' }}.</p>

                    @if($ticket->rating)
                        <div class="mt-6 inline-flex flex-col items-center bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Avaliação do Cliente</span>
                            <div class="flex items-center gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <x-icon name="star" style="solid" class="{{ $i <= $ticket->rating ? 'text-amber-400' : 'text-gray-200 dark:text-gray-700' }} w-5 h-5" />
                                @endfor
                            </div>
                            @if($ticket->rating_comment)
                                <p class="text-xs text-slate-600 dark:text-gray-400 italic">"{{ $ticket->rating_comment }}"</p>
                            @endif
                        </div>
                    @else
                        <div class="mt-6 inline-block px-4 py-2 bg-amber-50 text-amber-600 text-xs font-bold rounded-lg border border-amber-100">
                            Aguardando avaliação do cliente
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Detail Sidebar -->
        <div class="space-y-8">
            <!-- User Intelligence Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden relative group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>

                <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-[11px] mb-8 flex items-center gap-2">
                    <x-icon name="user-tie" style="duotone" class="text-primary" />
                    Perfil do Solicitante
                </h3>

                <div class="flex flex-col items-center text-center space-y-4 mb-8">
                    <div class="relative">
                        @if($ticket->user->photo)
                            <img src="{{ asset('storage/' . $ticket->user->photo) }}" class="w-24 h-24 rounded-[2rem] object-cover ring-8 ring-gray-100 dark:ring-slate-800 shadow-lg" />
                        @else
                            <div class="w-24 h-24 rounded-[2rem] bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 flex items-center justify-center font-black text-2xl ring-8 ring-gray-100 dark:ring-slate-800 shadow-lg">
                                {{ substr($ticket->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="absolute -bottom-2 -right-2 px-3 py-1 bg-primary text-white text-[9px] font-black uppercase rounded-xl border-4 border-white dark:border-slate-900 shadow-sm">
                            Ativo
                        </div>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-900 dark:text-white text-lg leading-tight">{{ $ticket->user->name }}</h4>
                        <p class="text-xs text-gray-500 font-bold truncate max-w-[180px]">{{ $ticket->user->email }}</p>
                    </div>
                </div>

                @if($ticket->user->isPro())
                <div class="mb-8 p-5 bg-amber-50 dark:bg-amber-500/10 rounded-2xl border border-amber-200 dark:border-amber-500/20">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 flex items-center justify-center bg-amber-100 dark:bg-amber-500/20 rounded-xl">
                            <x-icon name="crown" style="solid" class="text-amber-600 dark:text-amber-400 w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-widest">Atendimento Prioritário</p>
                            <p class="text-[11px] text-amber-600/80 dark:text-amber-400/80">Cliente Vertex PRO • Suporte VIP</p>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400">Priorize a resolução deste chamado. Cliente com suporte prioritário.</p>
                </div>
                @endif

                <div class="space-y-4 pt-6 border-t border-gray-50 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Plano Atual</span>
                        @if($ticket->user->isPro())
                            <span class="px-2 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[9px] font-black uppercase rounded-lg inline-flex items-center gap-1">
                                <x-icon name="crown" style="solid" class="w-3 h-3" /> PRO
                            </span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 dark:bg-slate-800 text-gray-500 text-[9px] font-black uppercase rounded-lg">Gratuito</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                         <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Membro Desde</span>
                         <span class="text-[11px] font-bold text-slate-700 dark:text-gray-300 tracking-tight">{{ $ticket->user->created_at->format('M Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                         <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">CPF</span>
                         <span class="text-[11px] font-bold text-slate-700 dark:text-gray-300 tracking-tight">{{ $ticket->user->cpf ?? 'Não inf.' }}</span>
                    </div>
                </div>

                <div class="mt-8 pt-4 space-y-3">
                    @php
                        $activeInspection = \Modules\Core\Models\Inspection::where('ticket_id', $ticket->id)
                            ->whereIn('status', ['pending', 'active'])
                            ->first();
                    @endphp

                    @if($activeInspection)
                        @if($activeInspection->status === 'active')
                            <form action="{{ route('support.inspection.enter', $activeInspection) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-emerald-500/20 transition-all flex items-center justify-center gap-3 animate-pulse group">
                                    <x-icon name="magnifying-glass-chart" style="solid" class="group-hover:rotate-12 transition-transform" />
                                    Acessar Painel Agora
                                </button>
                            </form>
                        @elseif($activeInspection->status === 'pending')
                             <div class="w-full py-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 font-black text-[10px] uppercase tracking-widest rounded-2xl flex items-center justify-center gap-2 animate-pulse">
                                <x-icon name="clock" style="solid" /> Aguardando Autorização...
                            </div>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1 text-center italic">O usuário deve aceitar o pedido no painel dele.</p>
                        @endif
                    @else
                        <form action="{{ route('support.tickets.inspection.request', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-lg hover:scale-[1.02] transition-all flex items-center justify-center gap-2 group">
                                <x-icon name="magnifying-glass-chart" style="solid" class="group-hover:rotate-12 transition-transform" /> Solicitar Inspeção Remota
                            </button>
                        </form>
                    @endif

                    @if($ticket->user->support_access_expires_at && $ticket->user->support_access_expires_at->isFuture())
                         <a href="{{ route('support.users.show', $ticket->user) }}" class="w-full py-3 bg-primary text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 hover:bg-primary-dark">
                            <x-icon name="id-card-clip" style="duotone" /> Perfil Completo
                        </a>
                    @else
                        <button disabled class="w-full py-3 bg-gray-50 dark:bg-slate-800 text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-2xl cursor-not-allowed flex items-center justify-center gap-2 opacity-60">
                            <x-icon name="lock" style="solid" /> Perfil Bloqueado
                        </button>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1 text-center italic">Peça ao usuário que autorize o acesso nas configurações dele.</p>
                    @endif
                </div>
            </div>

            <!-- Ticket Metadata -->
            <div class="bg-gradient-to-br from-slate-900 to-black rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden">
                 <div class="relative z-10 flex flex-col gap-6">
                    <div>
                        <p class="text-[10px] font-black text-primary/70 uppercase tracking-widest mb-1.5 leading-none">Data de Abertura</p>
                        <p class="text-white font-bold text-sm tracking-tight">{{ $ticket->created_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-primary/70 uppercase tracking-widest mb-1.5 leading-none">Última Atualização</p>
                        <p class="text-white font-bold text-sm tracking-tight">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>
                 </div>
                 <x-icon name="timer" style="duotone" class="absolute -right-4 -bottom-4 w-24 h-24 text-white/5 -rotate-12" />
            </div>
        </div>
    </div>
</div>

@push('scripts')
@vite('resources/js/ticket-chat.js')
@endpush
</x-panelsuporte::layouts.master>
