@php
    $sectorLabels = ['support' => 'Suporte', 'technical' => 'Técnico', 'billing' => 'Financeiro', 'admin' => 'Administração'];
    $sectorLabel = $sectorLabels[$conversation->sector] ?? $conversation->sector;
@endphp
<x-paneluser::layouts.master :title="'Conversa #' . $conversation->id . ' - Chat VIP'">
    @push('scripts')
    <script>
        window.vertexChatConfig = {
            conversationId: {{ $conversation->id }},
            userId: {{ auth()->id() }},
            sendUrl: "{{ route('vertexchat.chat.send', $conversation) }}",
            csrfToken: "{{ csrf_token() }}",
            pusherKey: "{{ config('broadcasting.connections.pusher.key') }}",
            pusherCluster: "{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}"
        };
    </script>
    @vite('resources/js/vertex-chat.js')
    @endpush

    <div class="max-w-3xl mx-auto py-8 px-4">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl overflow-hidden">
            {{-- Cabeçalho da conversa --}}
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                        <x-icon name="headset" style="duotone" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <h1 class="font-black text-slate-900 dark:text-white">Conversa #{{ $conversation->id }}</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Chat VIP · Atendimento prioritário</p>
                    </div>
                </div>
                <span class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-300">{{ $sectorLabel }}</span>
            </div>

            {{-- Área de mensagens --}}
            <div id="chat-messages" class="p-4 sm:p-5 space-y-4 max-h-[420px] overflow-y-auto bg-slate-50/50 dark:bg-slate-900/30">
                @foreach($conversation->messages as $msg)
                    <div class="{{ $msg->isSystemNotice() ? 'flex justify-center' : ($msg->sender_id === auth()->id() ? 'flex justify-end' : 'flex justify-start') }}">
                        @if($msg->isSystemNotice())
                            <span class="inline-block px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm">{!! nl2br(e($msg->body)) !!}</span>
                        @else
                            @php
                                $sender = $msg->sender;
                                $avatarUrl = $sender ? $sender->photo_url : null;
                                $initial = $sender ? strtoupper(mb_substr($sender->first_name ?? '?', 0, 1)) : '?';
                                $isOwn = $msg->sender_id === auth()->id();
                            @endphp
                            <div class="max-w-[85%] flex gap-2 {{ $isOwn ? 'flex-row-reverse' : '' }}">
                                <div class="shrink-0 w-9 h-9 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-600 flex items-center justify-center ring-2 ring-white dark:ring-slate-800 shadow">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="" class="w-full h-full object-cover" loading="lazy" />
                                    @else
                                        <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $initial }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">{{ $msg->sender?->first_name }} {{ $msg->sender?->last_name }}</p>
                                    <div class="px-4 py-2.5 rounded-2xl {{ $isOwn ? 'bg-primary text-white rounded-br-md' : 'bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-100 dark:border-slate-600 rounded-bl-md' }}">
                                        {!! nl2br(e($msg->body)) !!}
                                    </div>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">{{ $msg->created_at->format('d/m H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
                <div id="chat-typing-indicator" class="hidden py-2">
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic flex items-center gap-2">
                        <span class="inline-flex gap-1">
                            <span class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 300ms"></span>
                        </span>
                        Agente digitando...
                    </p>
                </div>
            </div>

            {{-- Formulário: envio via AJAX (vertex-chat.js intercepta) --}}
            <form id="vertex-chat-form" action="{{ route('vertexchat.chat.send', $conversation) }}" method="POST" class="p-4 sm:p-5 border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800" data-no-loading>
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="body" id="vertex-chat-input" required placeholder="Digite sua mensagem..."
                           class="flex-1 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <button type="submit" id="vertex-chat-submit" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-all shrink-0">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
