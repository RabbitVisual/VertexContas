@php
    $isPro = $isPro ?? auth()->user()?->isPro() ?? false;
    $statusConfig = [
        'open' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300', 'text' => 'Aberto'],
        'pending' => ['bg' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300', 'text' => 'Pendente'],
        'answered' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300', 'text' => 'Respondido'],
        'resolved' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300', 'text' => 'Resolvido'],
        'closed' => ['bg' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400', 'text' => 'Fechado'],
    ];
    $config = $statusConfig[$ticket->status] ?? $statusConfig['closed'];
@endphp
<x-paneluser::layouts.master :title="'Chamado #' . $ticket->id">
    <div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 px-4 pb-12">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary-500/5 dark:bg-primary-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-primary-600 dark:text-primary-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                        <a href="{{ route('user.tickets.index') }}" class="hover:underline">Central de Ajuda</a>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">Chamado #{{ $ticket->id }}</span>
                    </nav>
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3 line-clamp-2">
                        {{ $ticket->subject }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        Aberto em {{ $ticket->created_at->format('d/m/Y \à\s H:i') }}
                    </p>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $config['bg'] }}">
                            {{ $config['text'] }}
                        </span>
                        @if($isPro)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                                <x-icon name="crown" style="solid" class="w-3.5 h-3.5" /> Suporte VIP
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                    <a href="{{ route('user.tickets.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                        <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                        Voltar
                    </a>
                    @if($isPro)
                        <a href="{{ route('user.tickets.export-single', $ticket) }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                            <x-icon name="file-arrow-down" style="duotone" class="w-5 h-5" />
                            Exportar Histórico
                        </a>
                    @endif
                </div>
            </div>

            @if($isPro && ($ticket->assignedAgent || $firstResponseHours !== null))
                <div class="relative z-10 mt-8 pt-8 border-t border-gray-200 dark:border-white/5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($ticket->assignedAgent)
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5">
                            <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0">
                                <x-icon name="headset" style="duotone" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Agente Responsável</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $ticket->assignedAgent->name }}</p>
                            </div>
                        </div>
                    @endif
                    @if($firstResponseHours !== null)
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                <x-icon name="clock" style="duotone" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">1ª Resposta</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $firstResponseHours < 1 ? round($firstResponseHours * 60) . ' min' : $firstResponseHours . ' h' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if($inspectionActive ?? false)
            <div class="rounded-3xl border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50/50 dark:bg-emerald-950/20 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="magnifying-glass-chart" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-emerald-800 dark:text-emerald-300">O suporte está visualizando seu painel</p>
                        <p class="text-xs text-emerald-700/80 dark:text-emerald-400/80">Abra seu painel para acompanhar em tempo real a mesma tela que o agente está vendo.</p>
                    </div>
                </div>
                <a href="{{ route('paneluser.index') }}" target="_blank"
                    class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all">
                    <x-icon name="up-right-from-square" style="solid" class="w-4 h-4" />
                    Abrir painel em tempo real
                </a>
            </div>
        @endif

        @if(!$isPro)
            <div class="rounded-3xl border border-amber-200 dark:border-amber-500/20 bg-amber-50/50 dark:bg-amber-950/20 p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                    <x-icon name="crown" style="duotone" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Suporte Prioritário com Vertex PRO</p>
                    <p class="text-xs text-amber-700/80 dark:text-amber-400/80">Respostas mais rápidas, agente dedicado e exportação do histórico.</p>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="shrink-0 text-sm font-bold text-amber-600 dark:text-amber-400 hover:underline">
                    Conhecer PRO →
                </a>
            </div>
        @endif

        {{-- Chat Card --}}
        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex items-center gap-3 bg-gray-50/50 dark:bg-gray-950/50">
                <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <x-icon name="comments" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Conversa</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Mensagens com a equipe de suporte</p>
                </div>
            </div>

            <div
                id="ticket-chat-user"
                class="flex-1"
                data-ticket-id="{{ $ticket->id }}"
                data-post-url="{{ route('user.tickets.reply', $ticket) }}"
                data-messages-url="{{ route('user.tickets.messages', $ticket) }}"
                data-initial-messages="{{ json_encode($initialMessagesForVue ?? []) }}"
                data-current-user-id="{{ auth()->id() }}"
                data-is-closed="{{ $ticket->status === 'closed' ? '1' : '0' }}"
                data-can-reply="{{ $ticket->status !== 'closed' ? '1' : '0' }}"
                data-context="user"
                data-placeholder="Digite sua resposta aqui..."
                data-is-pro="{{ $isPro ? '1' : '0' }}"
                data-csrf="{{ csrf_token() }}"
            ></div>

            @if($ticket->status === 'closed' && !$ticket->rating)
                <div class="p-6 md:p-8 border-t border-gray-200 dark:border-white/5 bg-white dark:bg-slate-900">
                    <div class="bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-6 text-center border border-gray-200 dark:border-white/5 max-w-xl mx-auto">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-1">Chamado Fechado</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mb-6">
                            Fechado em <span class="font-bold">{{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : '' }}</span>
                        </p>
                        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-white/5">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Como foi sua experiência?</h4>
                            <form action="{{ route('user.tickets.rate', $ticket) }}" method="POST" x-data="{ rating: 0, hoveredStar: 0 }">
                                @csrf
                                <div class="flex justify-center gap-2 mb-4">
                                    <template x-for="i in [1,2,3,4,5]" :key="i">
                                        <button type="button"
                                            @click="rating = i"
                                            @mouseenter="hoveredStar = i"
                                            @mouseleave="hoveredStar = 0"
                                            class="transition-transform hover:scale-110 focus:outline-none p-1">
                                            <span class="inline-block" :class="(hoveredStar >= i || rating >= i) ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600'">
                                                <x-icon name="star" style="solid" class="w-8 h-8 transition-colors duration-200" />
                                            </span>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" :value="rating" required>
                                <textarea name="rating_comment" rows="2"
                                    class="w-full rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:ring-4 focus:ring-primary/10 focus:border-primary mb-4 p-3"
                                    placeholder="Deixe um comentário (opcional)..."></textarea>
                                <button type="submit" :disabled="rating === 0"
                                    class="w-full py-3 px-4 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed text-sm transition-all shadow-lg shadow-primary-500/20">
                                    Enviar Avaliação
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($ticket->status === 'closed' && $ticket->rating)
                <div class="p-6 border-t border-gray-200 dark:border-white/5 bg-white dark:bg-slate-900 text-center">
                    <div class="inline-block px-6 py-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-200 dark:border-emerald-500/20">
                        <div class="flex items-center gap-2 mb-1 justify-center">
                            <span class="text-emerald-700 dark:text-emerald-400 font-bold uppercase tracking-widest text-[10px]">Avaliado</span>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <x-icon name="star" style="solid" class="text-sm {{ $i <= $ticket->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" />
                                @endfor
                            </div>
                        </div>
                        @if($ticket->rating_comment)
                            <p class="text-emerald-600 dark:text-emerald-300 italic text-xs">"{{ $ticket->rating_comment }}"</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    @vite('resources/js/ticket-chat.js')
    @endpush
</x-paneluser::layouts.master>
