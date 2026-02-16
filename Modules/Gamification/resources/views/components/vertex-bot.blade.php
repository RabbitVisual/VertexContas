@props([
    'insight' => [],
    'financialScore' => 0,
    'insightKey' => null,
    'dismissUrl' => null,
])

@php
    $levelStyles = [
        'info' => 'bg-slate-950/90 dark:bg-slate-900/95 border-slate-600/30 text-white dark:text-slate-100',
        'success' => 'bg-slate-950/90 dark:bg-slate-900/95 border-emerald-500/40 text-white dark:text-slate-100',
        'warning' => 'bg-slate-950/90 dark:bg-slate-900/95 border-amber-500/40 text-white dark:text-slate-100',
        'danger' => 'bg-slate-950/90 dark:bg-slate-900/95 border-rose-500/40 text-white dark:text-slate-100',
    ];
    $bubbleClass = $levelStyles[$insight['level'] ?? 'info'] ?? $levelStyles['info'];
    $isSuccess = ($insight['level'] ?? '') === 'success';
    $key = $insightKey ?? $insight['insight_key'] ?? null;
    $medalUnlocked = $insight['medal'] ?? null;
    $content = $insight['content'] ?? '';
@endphp

{{-- Vertex Bot: na área de conteúdo (à direita do sidebar), na zona entre cards e lista, sempre visível. z-50 acima do sidebar. --}}
<div
    x-data="{
        showBubble: true,
        dismissed: false,
        fullText: '',
        displayedText: '',
        index: 0,
        isTyping: true,
        init() {
            const el = this.$el.querySelector('[data-vertex-content]');
            this.fullText = el ? el.textContent : '';
            const type = () => {
                if (this.index < this.fullText.length) {
                    this.displayedText += this.fullText[this.index];
                    this.index++;
                    setTimeout(type, 22);
                } else {
                    this.isTyping = false;
                }
            };
            setTimeout(type, 350);
        }
    }"
    class="fixed z-50 flex flex-col items-start gap-3 max-w-[calc(100vw-3rem)] sm:max-w-sm
           bottom-6 left-6
           sm:left-[17rem] sm:bottom-[10rem]"
>
    {{-- Hidden content source for typewriter (LGPD: no PII) --}}
    <span data-vertex-content class="hidden">{{ $content }}</span>

    {{-- Speech bubble: Premium Fintech Elite - backdrop-blur-xl, larger, Spring animation --}}
    <div
        x-show="showBubble && !dismissed"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-95 -translate-x-4"
        x-transition:enter-end="opacity-100 scale-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-4"
        class="rounded-2xl border backdrop-blur-xl shadow-2xl p-5 {{ $bubbleClass }} {{ $medalUnlocked ? 'ring-2 ring-amber-400/50' : '' }} max-w-md"
        style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;"
    >
        {{-- Badge: Mentor Vertex --}}
        <div class="flex items-center gap-2 mb-3">
            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-300 dark:text-indigo-400">Mentor Vertex</span>
        </div>
        <div class="flex items-start gap-4">
            {{-- Avatar: pulsing when typing --}}
            <div
                class="shrink-0 w-11 h-11 rounded-xl flex items-center justify-center bg-gradient-to-br from-[#2563eb] to-[#1e3a8a] shadow-lg shadow-indigo-500/25 border border-indigo-400/20"
                :class="{ 'animate-pulse': $data.isTyping }"
            >
                <x-icon name="robot" style="solid" class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[14px] font-medium leading-relaxed text-white dark:text-slate-100 min-h-[2.5rem]" x-text="displayedText"></p>
                @php
                    $analysisUrl = (auth()->user()?->isPro() ?? false) && Route::has('core.dashboard')
                        ? route('core.dashboard')
                        : route('paneluser.index');
                @endphp
                @if(Route::has('paneluser.index'))
                    <a href="{{ $analysisUrl }}" class="mt-2 block text-xs font-bold text-indigo-300 dark:text-indigo-400 hover:underline">
                        Ver análise detalhada
                    </a>
                @endif
                <button
                    @click="
                        const key = {{ json_encode($key) }};
                        if (key) {
                            fetch('{{ route('user.vertex-bot.dismiss') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                                },
                                body: JSON.stringify({ insight_key: key })
                            }).then(() => {}).catch(() => {});
                        }
                        dismissed = true;
                    "
                    class="mt-3 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white dark:hover:text-slate-200 transition-colors"
                >
                    Entendi
                </button>
            </div>
        </div>
    </div>

    {{-- Bot avatar: Vertex gradient, pulsing when analyzing --}}
    <div
        class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#2563eb] to-[#1e3a8a] flex items-center justify-center shadow-lg shadow-indigo-500/25 border border-indigo-400/20 hover:shadow-indigo-500/35 transition-all duration-300 {{ $isSuccess ? 'ring-2 ring-emerald-400/50' : '' }}"
        :class="{ 'animate-pulse': $data.isTyping }"
    >
        <x-icon name="robot" style="solid" class="w-7 h-7 text-white" />
    </div>
</div>
