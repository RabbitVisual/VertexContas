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

            <div class="p-6 md:p-8 space-y-8 bg-gray-50/30 dark:bg-slate-900/30">
                @forelse($ticket->messages as $message)
                    @php
                        $isSystem = $message->is_system ?? false;
                        $senderLabel = $isSystem ? 'Vertex Inspection' : ($message->is_admin_reply ? 'Equipe de Suporte' : 'Você');
                    @endphp
                    <div class="flex gap-4 {{ $isSystem ? 'justify-center' : ($message->user_id === Auth::id() ? 'flex-row-reverse' : '') }}">
                        @if(!$isSystem)
                            <div class="shrink-0">
                                @if($message->user && $message->user->photo)
                                    <img src="{{ asset('storage/' . $message->user->photo) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white dark:ring-slate-900 shadow-sm" title="{{ $message->user->name }}" alt="" />
                                @else
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold ring-2 ring-white dark:ring-slate-900 shadow-sm
                                        {{ $message->is_admin_reply ? 'bg-primary/20 text-primary' : 'bg-gray-200 dark:bg-slate-700 text-gray-600 dark:text-gray-400' }}">
                                        {{ substr($message->user->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="max-w-[80%] lg:max-w-[70%] {{ $isSystem ? 'max-w-2xl mx-auto' : '' }}">
                            <div class="flex items-center gap-2 mb-1 {{ $isSystem ? 'justify-center' : ($message->user_id === Auth::id() ? 'flex-row-reverse' : '') }}">
                                @if($isSystem)
                                    <div class="flex items-center gap-2 px-2 py-0.5 rounded-lg bg-amber-100 dark:bg-amber-500/20">
                                        <x-icon name="magnifying-glass-chart" style="solid" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" />
                                        <span class="text-xs font-bold text-amber-700 dark:text-amber-400">{{ $senderLabel }}</span>
                                    </div>
                                @else
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $senderLabel }}</span>
                                @endif
                                <span class="text-[10px] text-gray-400">
                                    @if($isPro)
                                        {{ $message->created_at->format('d/m/Y H:i') }}
                                    @else
                                        {{ $message->created_at->format('H:i') }}
                                    @endif
                                </span>
                            </div>

                            <div class="p-4 rounded-2xl text-sm leading-relaxed whitespace-pre-wrap shadow-sm border
                                {{ $isSystem
                                    ? 'bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20 text-amber-900 dark:text-amber-100'
                                    : ($message->user_id === Auth::id()
                                        ? 'bg-primary/10 dark:bg-primary/20 text-gray-800 dark:text-gray-200 rounded-tr-none border-primary/20 dark:border-primary/30'
                                        : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 rounded-tl-none border-gray-100 dark:border-slate-700')
                                }}">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                            <x-icon name="comments" style="duotone" class="w-8 h-8 text-slate-400 dark:text-slate-500 opacity-50" />
                        </div>
                        <p class="text-sm font-bold text-gray-500 dark:text-slate-400">Nenhuma mensagem encontrada.</p>
                    </div>
                @endforelse
            </div>

            {{-- Reply / Actions --}}
            <div class="border-t border-gray-200 dark:border-white/5 p-6 md:p-8 bg-white dark:bg-slate-900">
                @if($ticket->status === 'closed')
                    <div class="bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-6 text-center border border-gray-200 dark:border-white/5 max-w-xl mx-auto">
                        <div class="w-12 h-12 bg-gray-200 dark:bg-slate-700 rounded-xl flex items-center justify-center mx-auto mb-3 text-gray-500 dark:text-slate-400">
                            <x-icon name="lock" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-1">Chamado Fechado</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mb-6">
                            Fechado em <span class="font-bold">{{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : '' }}</span>
                        </p>

                        @if(!$ticket->rating)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-white/5">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Como foi sua experiência?</h4>
                                <form action="{{ route('user.tickets.rate', $ticket) }}" method="POST" x-data="{ rating: 0, hover: 0 }">
                                    @csrf
                                    <div class="flex justify-center gap-2 mb-4">
                                        <template x-for="i in [1,2,3,4,5]" :key="i">
                                            <button type="button"
                                                @click="rating = i"
                                                @mouseenter="hover = i"
                                                @mouseleave="hover = 0"
                                                class="transition-transform hover:scale-110 focus:outline-none p-1">
                                                <x-icon name="star" style="solid" class="w-8 h-8 transition-colors duration-200"
                                                    :class="(hover >= i || rating >= i) ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600'" />
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
                        @else
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
                        @endif
                    </div>
                @else
                    <form action="{{ route('user.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="relative">
                            <textarea name="message" rows="3"
                                class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 dark:border-white/10 dark:bg-slate-800 text-gray-900 dark:text-white placeholder:text-gray-400 focus:ring-4 focus:ring-primary-500/20 focus:border-primary-500 resize-none text-sm font-medium transition-all"
                                placeholder="Digite sua resposta aqui..." required></textarea>
                            <div class="absolute bottom-3 right-3">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/20 text-sm">
                                    <span>Enviar</span>
                                    <x-icon name="paper-plane" style="solid" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
