@php
    $statusLabels = ['open' => 'Aberta', 'transferred' => 'Transferida', 'closed' => 'Encerrada'];
    $sectorLabels = ['support' => 'Suporte', 'technical' => 'Técnico', 'billing' => 'Financeiro', 'admin' => 'Administração'];
@endphp
<x-paneluser::layouts.master :title="'Chat VIP - Suporte em tempo real'">
    <div class="max-w-3xl mx-auto py-8 px-4">
        {{-- Cabeçalho elegante --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 shadow-sm overflow-hidden mb-8">
            <div class="p-6 sm:p-8 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                        <x-icon name="headset" style="duotone" class="w-7 h-7 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-900 dark:text-white">Chat VIP</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">Suporte prioritário em tempo real. Inicie uma conversa ou continue uma existente.</p>
                    </div>
                </div>
            </div>
            <div class="p-6 sm:p-8">
                <form action="{{ route('vertexchat.chat.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow-lg shadow-primary/25 hover:shadow-xl transition-all">
                        <x-icon name="comments" style="duotone" class="w-5 h-5" />
                        Iniciar nova conversa
                    </button>
                </form>
            </div>
        </div>

        @if($conversations->isNotEmpty())
            <div class="space-y-3">
                <h2 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Conversas ativas</h2>
                @foreach($conversations as $conv)
                    <a href="{{ route('vertexchat.chat.show', $conv) }}"
                       class="block rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 hover:border-primary/30 hover:shadow-md transition-all overflow-hidden group">
                        <div class="p-4 sm:p-5 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 group-hover:bg-primary/10 transition-colors">
                                <x-icon name="comment-dots" style="duotone" class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-slate-900 dark:text-white">Conversa #{{ $conv->id }}</span>
                                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase {{ $conv->status === 'open' ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' : ($conv->status === 'transferred' ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400' : 'bg-slate-200 dark:bg-slate-600 text-slate-500 dark:text-slate-400') }}">
                                        {{ $statusLabels[$conv->status] ?? $conv->status }}
                                    </span>
                                </div>
                                @if($conv->latestMessage)
                                    <p class="text-sm text-slate-500 dark:text-slate-400 truncate mt-1">{{ Str::limit($conv->latestMessage->body, 60) }}</p>
                                @endif
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $conv->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <x-icon name="chevron-right" style="solid" class="w-5 h-5 text-slate-400 group-hover:text-primary shrink-0 transition-colors" />
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-8">Nenhuma conversa ainda. Clique em &quot;Iniciar nova conversa&quot; acima.</p>
        @endif
    </div>
</x-paneluser::layouts.master>
