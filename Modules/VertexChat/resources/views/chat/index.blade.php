<x-paneluser::layouts.master :title="'Chat VIP'">
    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Chat VIP</h1>
        <p class="text-slate-600 dark:text-slate-400 mb-6">Converse com nosso suporte em tempo real.</p>
        <form action="{{ route('vertexchat.chat.store') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-all">
                <x-icon name="comments" style="duotone" class="w-5 h-5" />
                Iniciar Conversa
            </button>
        </form>
        @if($conversations->isNotEmpty())
            <div class="mt-8 space-y-2">
                <h2 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Conversas ativas</h2>
                @foreach($conversations as $conv)
                    <a href="{{ route('vertexchat.chat.show', $conv) }}"
                       class="block p-4 rounded-xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 hover:border-primary/30 transition-all">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800 dark:text-white">#{{ $conv->id }}</span>
                            <span class="text-xs font-bold uppercase {{ $conv->status === 'open' ? 'text-emerald-600' : 'text-slate-500' }}">{{ $conv->status }}</span>
                        </div>
                        @if($conv->latestMessage)
                            <p class="text-sm text-slate-500 truncate mt-1">{{ Str::limit($conv->latestMessage->body, 50) }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-paneluser::layouts.master>
