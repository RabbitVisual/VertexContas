@section('title', 'Ticket #' . $ticket->id)

<x-paneluser::layouts.master>
    <div class="max-w-4xl mx-auto py-6 animate-in fade-in duration-500">

        <!-- Header / Status Bar -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.tickets.index') }}" class="group p-3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 text-gray-400 hover:text-primary transition-all">
                    <x-icon name="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                </a>
                <div>
                     <h1 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                        {{ $ticket->subject }}
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
                    </h1>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                        ID: #{{ $ticket->id }} • {{ $ticket->created_at->format('d/m/Y \à\s H:i') }}
                    </p>
                </div>
            </div>

            @if($ticket->status !== 'closed')
                <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/10 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-widest rounded-xl border border-emerald-100 dark:border-emerald-500/20 animate-pulse">
                    Em Atendimento
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 relative overflow-hidden">
             <!-- Decorative Top Bar -->
             <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 dark:from-gray-700 dark:via-gray-600 dark:to-gray-700 opacity-50"></div>

            <!-- Messages Stream -->
            <div class="space-y-10 mb-12">
                @forelse($ticket->messages as $message)
                    <div class="flex gap-6 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }} group">

                        <!-- Avatar -->
                        <div class="flex-shrink-0 mt-2">
                             @if($message->is_admin_reply)
                                <div class="relative">
                                    @if($message->user && $message->user->photo)
                                        <img src="{{ asset('storage/' . $message->user->photo) }}" class="w-12 h-12 rounded-2xl object-cover ring-4 ring-gray-50 dark:ring-slate-800 shadow-sm" title="{{ $message->user->name }} (Suporte)" />
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-primary border-2 border-white dark:border-slate-800 flex items-center justify-center shadow-sm">
                                            <x-icon name="headset" style="solid" class="text-[10px] text-white" />
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-purple-600 text-white flex items-center justify-center shadow-lg shadow-primary/20 ring-4 ring-gray-50 dark:ring-slate-800">
                                            <x-icon name="headset" style="duotone" class="text-xl" />
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="relative">
                                    @if($message->user && $message->user->photo)
                                        <img src="{{ asset('storage/' . $message->user->photo) }}" class="w-12 h-12 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-800 shadow-sm" title="{{ $message->user->name }}" />
                                    @else
                                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400 flex items-center justify-center font-black text-lg ring-4 ring-white dark:ring-slate-800">
                                            {{ substr($message->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Bubble -->
                        <div class="flex-1 max-w-2xl">
                            <div class="flex items-center gap-2 mb-2 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                                <span class="text-xs font-black text-slate-900 dark:text-white">{{ $message->is_admin_reply ? 'Suporte Vertex' : 'Você' }}</span>
                                <span class="text-[10px] font-bold text-gray-400">{{ $message->created_at->format('H:i') }}</span>
                            </div>

                            <div class="p-6 rounded-3xl shadow-sm text-sm font-medium leading-relaxed whitespace-pre-wrap transition-all group-hover:shadow-md
                                {{ $message->user_id === Auth::id()
                                    ? 'bg-blue-50 dark:bg-blue-900/10 text-slate-800 dark:text-gray-200 rounded-tr-none border border-blue-100 dark:border-blue-500/20'
                                    : 'bg-gray-50 dark:bg-slate-900/50 text-slate-700 dark:text-gray-300 rounded-tl-none border border-gray-100 dark:border-gray-700'
                                }}">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 opacity-50">
                        <x-icon name="ghost" style="duotone" class="text-4xl text-gray-300 mb-2" />
                        <p class="text-sm font-bold text-gray-400">Nenhuma mensagem encontrada.</p>
                    </div>
                @endforelse
            </div>

            <!-- Interaction Area (Reply or Closed State) -->
            <div class="border-t border-gray-100 dark:border-gray-700 pt-8">
                @if($ticket->status === 'closed')
                    <div class="bg-gray-50 dark:bg-slate-900/50 rounded-3xl shadow-inner p-8 text-center border border-gray-100 dark:border-gray-700 max-w-2xl mx-auto">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 animate-bounce">
                            <x-icon name="lock" style="solid" class="text-2xl" />
                        </div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white mb-2 uppercase tracking-widest">Atendimento Encerrado</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 font-medium">
                            Finalizado em <span class="font-bold text-slate-700 dark:text-gray-300">{{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y \à\s H:i') : '' }}</span>
                        </p>

                        @if(!$ticket->rating)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 max-w-md mx-auto relative overflow-hidden">
                                <div class="absolute top-0 w-full h-1 bg-gradient-to-r from-amber-400 to-orange-500 left-0"></div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white mb-4">Como foi sua experiência?</h3>

                                <form action="{{ route('user.tickets.rate', $ticket) }}" method="POST" x-data="{ rating: 0, hover: 0 }">
                                    @csrf
                                    <div class="flex justify-center gap-2 mb-6">
                                        <template x-for="i in 5">
                                            <button type="button"
                                                @click="rating = i"
                                                @mouseenter="hover = i"
                                                @mouseleave="hover = 0"
                                                class="transition-transform hover:scale-125 focus:outline-none p-1">
                                                <x-icon name="star" style="solid"
                                                    class="w-8 h-8 transition-colors duration-200"
                                                    ::class="(hover >= i || rating >= i) ? 'text-amber-400 drop-shadow-sm' : 'text-gray-200 dark:text-gray-700'"
                                                />
                                            </button>
                                        </template>
                                    </div>
                                    <input type="hidden" name="rating" :value="rating" required>

                                    <div class="mb-4">
                                        <textarea name="rating_comment" rows="2" class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-slate-900 text-sm focus:ring-amber-500 focus:border-amber-500 placeholder:text-gray-300 transition-colors" placeholder="Deixe um comentário (opcional)..."></textarea>
                                    </div>

                                    <button type="submit" :disabled="rating === 0" class="w-full py-3 bg-slate-900 dark:bg-white hover:bg-black dark:hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-white dark:text-slate-900 font-black rounded-xl shadow-lg transition-all active:scale-95 text-xs uppercase tracking-widest">
                                        Enviar Avaliação
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="inline-block px-8 py-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-100 dark:border-emerald-500/20">
                                <div class="flex items-center gap-2 mb-2 justify-center">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-black uppercase tracking-widest text-[10px]">Avaliado com</span>
                                    <div class="flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <x-icon name="star" style="solid" class="{{ $i <= $ticket->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }} w-3 h-3" />
                                        @endfor
                                    </div>
                                </div>
                                @if($ticket->rating_comment)
                                    <p class="text-slate-600 dark:text-gray-300 italic text-xs font-medium">"{{ $ticket->rating_comment }}"</p>
                                @endif
                            </div>
                            <p class="mt-4 text-xs font-bold text-gray-400">Obrigado pelo feedback!</p>
                        @endif
                    </div>
                @else
                    <form action="{{ route('user.tickets.reply', $ticket) }}" method="POST" class="relative">
                        @csrf
                        <div class="relative group">
                             <div class="absolute top-4 left-4 text-gray-300 dark:text-gray-600 group-focus-within:text-primary transition-colors">
                                <x-icon name="message-lines" style="duotone" class="text-xl" />
                            </div>
                            <textarea name="message" rows="4" class="w-full pl-12 pr-32 py-4 bg-gray-50 dark:bg-slate-900/50 border-2 border-transparent focus:border-primary/20 rounded-[2rem] focus:ring-0 resize-none text-sm font-medium text-slate-700 dark:text-white placeholder:text-gray-400 transition-all shadow-inner" placeholder="Digite sua resposta aqui..." required></textarea>

                            <div class="absolute bottom-2 right-2">
                                <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white font-black rounded-xl shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-2 text-xs uppercase tracking-widest">
                                    <span>Enviar</span>
                                    <x-icon name="paper-plane" style="solid" />
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-paneluser::layouts.master>
