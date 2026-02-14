<x-paneluser::layouts.master :title="'Chamado #' . $ticket->id">
    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-4xl mx-auto space-y-8 px-6 pt-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <li><a href="{{ route('user.tickets.index') }}" class="hover:text-primary transition-colors">Central de Ajuda</a></li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="text-primary">Chamado #{{ $ticket->id }}</li>
                        </ol>
                    </nav>
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $ticket->subject }}</h1>
                        @if($isPro ?? false)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                                <x-icon name="crown" style="solid" class="w-3.5 h-3.5" /> Suporte VIP
                            </span>
                        @endif
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
                    </div>
                    <p class="text-gray-500 dark:text-slate-400 text-sm">{{ $ticket->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>
                <a href="{{ route('user.tickets.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white transition-colors group w-fit">
                    <x-icon name="arrow-left" style="solid" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                    Voltar aos chamados
                </a>
            </div>

            @if(!($isPro ?? false))
                <div class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-500/20 flex items-center gap-3">
                    <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                        <x-icon name="crown" style="solid" class="text-amber-600 dark:text-amber-400 w-5 h-5" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Tenha suporte prioritário com Vertex PRO</p>
                        <p class="text-xs text-amber-700/80 dark:text-amber-400/80">Respostas mais rápidas e exportação do histórico.</p>
                    </div>
                    <a href="{{ route('user.subscription.index') }}" class="shrink-0 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">
                        Fazer Upgrade →
                    </a>
                </div>
            @endif

            {{-- Chat Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2.5 bg-primary/10 rounded-xl">
                        <x-icon name="comments" style="solid" class="text-primary w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Conversa</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Mensagens com a equipe de suporte</p>
                    </div>
                </div>

                <div class="p-6 md:p-8 space-y-8 bg-gray-50/30 dark:bg-slate-900/30">
                    @forelse($ticket->messages as $message)
                        <div class="flex gap-4 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
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

                            <div class="max-w-[80%] lg:max-w-[70%]">
                                <div class="flex items-center gap-2 mb-1 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $message->is_admin_reply ? 'Equipe de Suporte' : 'Você' }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $message->created_at->format('H:i') }}</span>
                                </div>

                                <div class="p-4 rounded-2xl text-sm leading-relaxed whitespace-pre-wrap shadow-sm border
                                    {{ $message->user_id === Auth::id()
                                        ? 'bg-primary/10 dark:bg-primary/20 text-gray-800 dark:text-gray-200 rounded-tr-none border-primary/20 dark:border-primary/30'
                                        : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 rounded-tl-none border-gray-100 dark:border-slate-700'
                                    }}">
                                    {!! nl2br(e($message->message)) !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                                <x-icon name="ghost" style="solid" class="w-8 h-8 text-slate-400 dark:text-slate-500" />
                            </div>
                            <p class="text-sm font-bold text-gray-500 dark:text-slate-400">Nenhuma mensagem encontrada.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Reply / Actions --}}
                <div class="border-t border-gray-100 dark:border-slate-800 p-6 md:p-8 bg-white dark:bg-slate-900">
                    @if($ticket->status === 'closed')
                        <div class="bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-6 text-center border border-gray-100 dark:border-slate-700 max-w-xl mx-auto">
                            <div class="w-12 h-12 bg-gray-200 dark:bg-slate-700 rounded-xl flex items-center justify-center mx-auto mb-3 text-gray-500 dark:text-slate-400">
                                <x-icon name="lock" style="solid" class="w-6 h-6" />
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-1">Chamado Fechado</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mb-6">
                                Fechado em <span class="font-bold">{{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : '' }}</span>
                            </p>

                            @if(!$ticket->rating)
                                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
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
                                            class="w-full py-3 px-4 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-sm transition-all shadow-lg shadow-primary/20">
                                            Enviar Avaliação
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="inline-block px-6 py-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-500/20">
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
                                    class="w-full px-4 py-4 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white placeholder:text-gray-400 focus:ring-4 focus:ring-primary/10 focus:border-primary resize-none text-sm font-medium transition-all"
                                    placeholder="Digite sua resposta aqui..." required></textarea>
                                <div class="absolute bottom-3 right-3">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 text-sm">
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
    </div>
</x-paneluser::layouts.master>
