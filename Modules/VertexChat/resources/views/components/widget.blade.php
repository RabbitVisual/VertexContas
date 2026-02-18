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
        {{-- PRO: Expandable chat widget with "Abrir Chat VIP" --}}
        <div class="transition-all duration-300 ease-out flex flex-col items-end gap-2"
             :class="expanded ? 'w-80 sm:w-96' : 'w-auto'">
            <div x-show="expanded" x-collapse x-cloak
                 class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/90 backdrop-blur-xl shadow-xl overflow-hidden">
                <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <x-icon name="headset" style="duotone" class="w-5 h-5 text-primary" />
                        </div>
                        <div>
                            <p class="font-black text-slate-900 dark:text-white text-sm">Chat VIP</p>
                            <p class="text-[10px] text-slate-500">Suporte em tempo real</p>
                        </div>
                    </div>
                    <button type="button" @click="expanded = false"
                            class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors">
                        <x-icon name="xmark" style="solid" class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-4">
                    <a href="{{ route('vertexchat.chat.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-all">
                        <x-icon name="comments" style="duotone" class="w-5 h-5" />
                        Abrir Chat VIP
                    </a>
                    <p class="mt-2 text-[10px] text-slate-500 text-center">Converse com nosso suporte prioritário.</p>
                </div>
            </div>
            <button type="button" @click="expanded = !expanded"
                    class="flex items-center justify-center gap-2 w-14 h-14 rounded-2xl bg-primary hover:bg-primary-dark text-white shadow-lg shadow-primary/25 hover:shadow-xl hover:scale-105 active:scale-95 transition-all">
                <x-icon name="comments" style="duotone" class="w-6 h-6" />
                <span class="sr-only">Chat VIP</span>
            </button>
        </div>
    @else
        {{-- Free: Lock + CTA --}}
        <div class="flex flex-col items-end gap-2">
            <div x-show="expanded" x-collapse x-cloak
                 class="w-80 sm:w-96 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/90 backdrop-blur-xl shadow-xl overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <x-icon name="lock" style="duotone" class="w-5 h-5 text-slate-500" />
                        </div>
                        <div>
                            <p class="font-black text-slate-900 dark:text-white text-sm">Chat VIP</p>
                            <p class="text-[10px] text-slate-500">Exclusivo para membros PRO</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">
                        Suporte VIP via Chat é exclusivo para membros PRO. Atendimento prioritário em tempo real.
                    </p>
                    <a href="{{ route('user.subscription.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all">
                        Ver Planos
                    </a>
                </div>
            </div>
            <button type="button" @click="expanded = !expanded"
                    class="flex items-center justify-center gap-2 w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 shadow-lg transition-all hover:scale-105 active:scale-95">
                <x-icon name="lock" style="duotone" class="w-5 h-5" />
                <x-icon name="comments" style="duotone" class="w-5 h-5" />
                <span class="sr-only">Chat VIP</span>
            </button>
        </div>
    @endif
</div>
@endif
