@php
    $user = auth()->user();
    $isPro = $user?->isPro() ?? false;
    $chatEnabled = vertex_chat_enabled();
    $hasChatRoute = Route::has('vertexchat.chat.index');
    $showWidget = $chatEnabled && $hasChatRoute && auth()->check();
@endphp

@if($showWidget)
<div x-data="{ expanded: false }" class="fixed bottom-4 right-4 z-40 sm:bottom-6 sm:right-6">
    @if($isPro)
        {{-- PRO: Mentor Vertex VIP --}}
        <div class="transition-all duration-300 ease-out flex flex-col items-end gap-2"
             :class="expanded ? 'w-80 sm:w-96' : 'w-auto'">
            <div x-show="expanded" x-collapse x-cloak
                 class="w-full rounded-2xl border border-amber-400/60 dark:border-amber-500/70 bg-slate-950/95 backdrop-blur-xl shadow-2xl shadow-amber-500/25 overflow-hidden">
                <div class="p-4 border-b border-amber-500/20 flex items-center justify-between bg-linear-to-r from-amber-500/20 via-purple-600/15 to-slate-900">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-linear-to-br from-amber-400 to-violet-600 flex items-center justify-center shadow-lg shadow-amber-500/40">
                            <x-icon name="crown" style="duotone" class="w-5 h-5 text-slate-950" />
                        </div>
                        <div>
                            <p class="font-black text-slate-50 text-sm">Mentor Vertex VIP</p>
                            <p class="text-[10px] text-amber-100">Apoio financeiro empático em tempo quase real</p>
                        </div>
                    </div>
                    <button type="button" @click="expanded = false"
                            class="p-2 rounded-lg hover:bg-slate-900/70 text-amber-100 transition-colors">
                        <x-icon name="xmark" style="solid" class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-4 space-y-3 text-slate-100">
                    <p class="text-xs text-slate-200">
                        Use este espaço para tirar dúvidas sobre seus números, metas e próximos passos.
                        O Mentor Vertex sempre vai sugerir ajustes suaves, sem julgamentos.
                    </p>
                    <a href="{{ route('vertexchat.chat.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-linear-to-r from-amber-500 to-violet-600 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/40 hover:shadow-xl hover:scale-[1.02] transition-all">
                        <x-icon name="comments" style="duotone" class="w-5 h-5" />
                        Abrir Mentor Vertex VIP
                    </a>
                    <p class="mt-1 text-[10px] text-slate-400 text-center">
                        Respostas em linguagem simples, focadas em pequenos passos que cabem na sua rotina.
                    </p>
                </div>
            </div>
            <button type="button" @click="expanded = !expanded"
                    class="flex items-center justify-center gap-2 w-14 h-14 rounded-2xl bg-linear-to-br from-amber-500 to-violet-600 text-slate-950 shadow-lg shadow-amber-500/40 hover:shadow-xl hover:scale-105 active:scale-95 transition-all">
                <x-icon name="crown" style="duotone" class="w-6 h-6" />
                <span class="sr-only">Mentor Vertex VIP</span>
            </button>
        </div>
    @else
        {{-- Free: Suporte Vertex + CTA para PRO --}}
        <div class="flex flex-col items-end gap-2">
            <div x-show="expanded" x-collapse x-cloak
                 class="w-80 sm:w-96 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/90 backdrop-blur-xl shadow-xl overflow-hidden">
                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <x-icon name="life-ring" style="duotone" class="w-5 h-5 text-sky-600 dark:text-sky-400" />
                        </div>
                        <div>
                            <p class="font-black text-slate-900 dark:text-white text-sm">Suporte Vertex</p>
                            <p class="text-[10px] text-slate-500">Dúvidas sobre como usar o app</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400">
                        Aqui você pode tirar dúvidas sobre telas, cadastros e relatórios do Vertex Contas.
                        Para receber orientações personalizadas sobre sua vida financeira, conheça o plano Vertex Pro.
                    </p>
                    <a href="{{ route('user.subscription.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all">
                        Conhecer Vertex Pro
                    </a>
                </div>
            </div>
            <button type="button" @click="expanded = !expanded"
                    class="flex items-center justify-center gap-2 w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 shadow-lg transition-all hover:scale-105 active:scale-95">
                <x-icon name="life-ring" style="duotone" class="w-5 h-5" />
                <span class="sr-only">Suporte Vertex</span>
            </button>
        </div>
    @endif
</div>
@endif
