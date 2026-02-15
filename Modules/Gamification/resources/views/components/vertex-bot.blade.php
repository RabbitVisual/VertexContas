@props([
    'insight' => [],
    'financialScore' => 0,
    'insightKey' => null,
    'dismissUrl' => null,
])

@php
    $levelStyles = [
        'info' => 'bg-blue-500/10 dark:bg-blue-500/20 border-blue-500/30 text-blue-700 dark:text-blue-300',
        'success' => 'bg-emerald-500/10 dark:bg-emerald-500/20 border-emerald-500/30 text-emerald-700 dark:text-emerald-300',
        'warning' => 'bg-amber-500/10 dark:bg-amber-500/20 border-amber-500/30 text-amber-700 dark:text-amber-300',
        'danger' => 'bg-rose-500/10 dark:bg-rose-500/20 border-rose-500/30 text-rose-700 dark:text-rose-300',
    ];
    $bubbleClass = $levelStyles[$insight['level'] ?? 'info'] ?? $levelStyles['info'];
    $isSuccess = ($insight['level'] ?? '') === 'success';
    $key = $insightKey ?? $insight['insight_key'] ?? null;
    $medalUnlocked = $insight['medal'] ?? null;
@endphp

<div
    x-data="{ showBubble: true, dismissed: false }"
    class="fixed bottom-20 right-6 sm:bottom-6 z-40 flex flex-col items-end gap-3"
>
    {{-- Speech bubble (above robot) --}}
    <div
        x-show="showBubble && !dismissed"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="max-w-sm rounded-2xl border backdrop-blur-md shadow-xl p-4 {{ $bubbleClass }} {{ $medalUnlocked ? 'ring-2 ring-amber-400/50 animate-pulse' : '' }}"
    >
        <div class="flex items-start gap-3">
            <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center bg-white/50 dark:bg-black/20">
                <x-icon name="comment-dots" style="solid" class="w-5 h-5 text-current" />
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium leading-relaxed">{{ $insight['content'] ?? '' }}</p>
                @if(Route::has('user.achievements.index'))
                    <a href="{{ route('user.achievements.index') }}" class="mt-2 block text-xs font-bold text-current/80 hover:underline">
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
                    class="mt-3 text-xs font-bold uppercase tracking-wider hover:underline"
                >
                    Entendi
                </button>
            </div>
        </div>
    </div>

    {{-- Robot icon (floating animation, glow when success) --}}
    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center animate-bounce {{ $isSuccess ? 'shadow-lg shadow-emerald-500/50' : 'shadow-lg' }}">
        <x-icon name="robot" style="solid" class="w-8 h-8 text-emerald-600 dark:text-emerald-400" />
    </div>
</div>
