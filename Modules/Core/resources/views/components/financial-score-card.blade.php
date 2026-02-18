@props([
    'score' => 0,
    'label' => 'Em análise',
    'scoreTextClass' => 'text-slate-500 dark:text-slate-400',
    'scoreBorderClass' => 'hover:border-slate-400/30',
    'description' => '0-100: poupança, reserva e consistência',
])

@php
    // Meia-lua: um único path para track e progresso; progresso = stroke-dasharray no mesmo traço.
    $pct = min(100, max(0, (int) $score)) / 100;
    $r = 80;
    $arcLength = M_PI * $r; // comprimento do semicírculo (πr)
    $dashVisible = $pct * $arcLength;
    $dashGap = $arcLength - $dashVisible;
    $strokeHex = $score === 0 ? '#94a3b8' : ($score <= 40 ? '#ef4444' : ($score <= 70 ? '#f59e0b' : '#22c55e'));
@endphp

<div {{ $attributes->merge([
    'class' => "group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors duration-200 rounded-3xl border border-gray-200 dark:border-white/5 {$scoreBorderClass} shadow-sm hover:shadow-xl",
]) }}>
    <div class="relative p-6 flex flex-col">
        {{-- Header: SCORE FINANCEIRO --}}
        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4 whitespace-nowrap">
            Score Financeiro
        </p>

        {{-- Center: Gauge + Number + Status --}}
        <div class="flex flex-col items-center justify-center">
            <div class="relative w-24 h-24 shrink-0">
                <svg viewBox="0 0 200 100" class="w-full h-full -scale-y-100" aria-hidden="true">
                    {{-- Track (meia-lua): path único --}}
                    <path d="M 20 90 A 80 80 0 0 1 180 90" fill="none" stroke="currentColor" stroke-width="10" stroke-linecap="round" class="text-gray-200 dark:text-gray-700" />
                    {{-- Progresso: mesmo path, só os primeiros pct% com dasharray = mesmo traço, mesma espessura --}}
                    <path d="M 20 90 A 80 80 0 0 1 180 90" fill="none" stroke="{{ $strokeHex }}" stroke-width="10" stroke-linecap="round" stroke-dasharray="{{ round($dashVisible, 2) }} {{ round($dashGap, 2) }}" stroke-dashoffset="0" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center pt-2">
                    <span class="text-2xl font-black text-gray-900 dark:text-white tabular-nums">{{ $score }}</span>
                </div>
            </div>
            <p class="text-sm font-bold {{ $scoreTextClass }} mt-2">{{ $label }}</p>
        </div>

        {{-- Description: Section at bottom --}}
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/5">
            <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $description }}</p>
        </div>
    </div>
</div>
