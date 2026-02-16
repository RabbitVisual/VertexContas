<x-paneluser::layouts.master :title="'Chat #' . $conversation->id">
    @push('scripts')
    <script>
        window.vertexChatConfig = {
            conversationId: {{ $conversation->id }},
            userId: {{ auth()->id() }},
            pusherKey: "{{ config('broadcasting.connections.pusher.key') }}",
            pusherCluster: "{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}"
        };
    </script>
    @vite('resources/js/vertex-chat.js')
    @endpush

    <div class="max-w-2xl mx-auto py-8">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                <h1 class="font-black text-slate-900 dark:text-white">Conversa #{{ $conversation->id }}</h1>
                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ $conversation->sector }}</span>
            </div>
            <div id="chat-messages" class="p-4 space-y-4 max-h-[400px] overflow-y-auto">
                @foreach($conversation->messages as $msg)
                    <div class="{{ $msg->isSystemNotice() ? 'text-center' : ($msg->sender_id === auth()->id() ? 'text-right' : 'text-left') }}">
                        @if($msg->isSystemNotice())
                            <span class="inline-block px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-sm">{!! nl2br(e($msg->body)) !!}</span>
                        @else
                            <div class="{{ $msg->sender_id === auth()->id() ? 'inline-block ml-auto' : 'inline-block' }}">
                                <p class="text-xs font-bold text-slate-500 mb-1">{{ $msg->sender?->first_name }} {{ $msg->sender?->last_name }}</p>
                                <div class="px-4 py-2 rounded-2xl {{ $msg->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200' }}">
                                    {{ $msg->body }}
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $msg->created_at->format('d/m H:i') }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
                <div id="chat-typing-indicator" class="hidden py-2">
                    <p class="text-xs text-slate-500 italic flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        Agente digitando...
                    </p>
                </div>
            </div>
            <form action="{{ route('vertexchat.chat.send', $conversation) }}" method="POST" class="p-4 border-t border-gray-100 dark:border-slate-800">
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="body" required placeholder="Digite sua mensagem..."
                           class="flex-1 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20">
                    <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-all">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
