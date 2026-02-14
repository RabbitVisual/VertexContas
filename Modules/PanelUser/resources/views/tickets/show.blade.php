<x-paneluser::layouts.master :title="'Chamado #' . $ticket->id">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('user.tickets.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex items-center mb-4 transition-colors w-fit">
                <x-icon name="arrow-left" style="solid" class="mr-2" /> Voltar aos chamados
            </a>

            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                             #{{ $ticket->id }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('d/m/Y \a\t H:i') }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->subject }}</h1>
                </div>
                 <!-- Status Badge -->
                 @php
                    $statusConfig = [
                        'open' => ['bg' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300', 'text' => 'Aberto'],
                        'pending' => ['bg' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300', 'text' => 'Pendente'],
                        'answered' => ['bg' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300', 'text' => 'Respondido'],
                        'closed' => ['bg' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300', 'text' => 'Fechado'],
                    ];
                    $config = $statusConfig[$ticket->status] ?? $statusConfig['closed'];
                @endphp
                <span class="px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-widest {{ $config['bg'] }}">
                    {{ $config['text'] }}
                </span>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <div class="p-6 md:p-8 space-y-8 bg-gray-50/50 dark:bg-gray-900/20">
                @forelse($ticket->messages as $message)
                    <div class="flex gap-4 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }} group">
                        <!-- Avatar -->
                        <div class="shrink-0">
                            @if($message->user && $message->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $message->user->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800 shadow-sm" title="{{ $message->user->name }}" />
                            @else
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold ring-2 ring-white dark:ring-gray-800 shadow-sm
                                    {{ $message->is_admin_reply ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ substr($message->user->name ?? 'User', 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Message Bubble -->
                        <div class="max-w-[80%] lg:max-w-[70%]">
                             <div class="flex items-center gap-2 mb-1 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $message->is_admin_reply ? 'Equipe de Suporte' : 'Você' }}</span>
                                <span class="text-[10px] text-gray-400">{{ $message->created_at->format('H:i') }}</span>
                            </div>

                            <div class="p-4 rounded-2xl text-sm leading-relaxed whitespace-pre-wrap shadow-sm border
                                {{ $message->user_id === Auth::id()
                                    ? 'bg-primary-50 dark:bg-primary-900/10 text-gray-800 dark:text-gray-200 rounded-tr-none border-primary-100 dark:border-primary-900/30'
                                    : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-tl-none border-gray-100 dark:border-gray-600'
                                }}">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 opacity-50">
                        <x-icon name="ghost" style="solid" class="text-4xl text-gray-300 mb-2" />
                        <p class="text-sm font-bold text-gray-400">Nenhuma mensagem encontrada.</p>
                    </div>
                @endforelse
            </div>

            <!-- Reply / Actions -->
             <div class="border-t border-gray-100 dark:border-gray-700 p-6 md:p-8 bg-white dark:bg-gray-800">
                @if($ticket->status === 'closed')
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6 text-center border border-gray-100 dark:border-gray-700 max-w-xl mx-auto">
                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <x-icon name="lock" style="solid" />
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-1">Chamado Fechado</h3>
                        <p class="text-xs text-gray-500 mb-6">
                            Fechado em <span class="font-bold">{{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : '' }}</span>
                        </p>

                        @if(!$ticket->rating)
                            <!-- Rating Form -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-600 relative overflow-hidden">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Como foi sua experiência?</h4>
                                <form action="{{ route('user.tickets.rate', $ticket) }}" method="POST" x-data="{ rating: 0, hover: 0 }">
                                    @csrf
                                    <div class="flex justify-center gap-2 mb-4">
                                        <template x-for="i in 5">
                                            <button type="button"
                                                @click="rating = i"
                                                @mouseenter="hover = i"
                                                @mouseleave="hover = 0"
                                                class="transition-transform hover:scale-110 focus:outline-none p-1">
                                                <x-icon name="star" style="solid" class="text-2xl transition-colors duration-200"
                                                   :class="(hover >= i || rating >= i) ? 'text-amber-400 drop-shadow-sm' : 'text-gray-200 dark:text-gray-600'" />
                                            </button>
                                        </template>
                                    </div>
                                    <input type="hidden" name="rating" :value="rating" required>
                                    <textarea name="rating_comment" rows="2" class="w-full rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-900 text-sm focus:ring-amber-500 focus:border-amber-500 placeholder:text-gray-400 mb-3" placeholder="Deixe um comentário (opcional)..."></textarea>
                                    <button type="submit" :disabled="rating === 0" class="w-full py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-lg shadow hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed text-xs uppercase tracking-wide transition-all">
                                        Enviar Avaliação
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Rated State -->
                            <div class="inline-block px-6 py-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-500/20">
                                <div class="flex items-center gap-2 mb-1 justify-center">
                                    <span class="text-emerald-700 dark:text-emerald-400 font-bold uppercase tracking-widest text-[10px]">Avaliado</span>
                                    <div class="flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <x-icon name="star" style="solid" class="text-xs {{ $i <= $ticket->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" />
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
                    <form action="{{ route('user.tickets.reply', $ticket) }}" method="POST" class="relative">
                        @csrf
                        <div class="relative">
                            <textarea name="message" rows="3" class="w-full pl-4 pr-32 py-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 focus:border-primary-500 focus:ring-primary-500 rounded-2xl resize-none text-sm font-medium text-gray-700 dark:text-white placeholder:text-gray-400 transition-all shadow-inner" placeholder="Digite sua resposta aqui..." required></textarea>
                            <div class="absolute bottom-3 right-3">
                                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wide">
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
