@props([
    'score' => 0,
    'label' => 'Em análise',
    'scoreTextClass' => 'text-slate-500 dark:text-slate-400',
    'scoreBorderClass' => 'hover:border-slate-400/30',
    'description' => 'Baseado em poupança, orçamento, reserva de emergência e consistência de registro (GFP).',
    'showHelp' => true,
    'isPro' => false,
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
    'class' => "group relative overflow-visible bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors duration-200 rounded-3xl border border-gray-200 dark:border-white/5 {$scoreBorderClass} shadow-sm hover:shadow-xl",
]) }}>
    <div class="relative p-6 flex flex-col">
        {{-- Header: SCORE FINANCEIRO + dica de cálculo --}}
        <div class="flex items-center justify-between gap-2 mb-4">
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest whitespace-nowrap">
                Score Financeiro
            </p>
            @if($showHelp)
                <span class="relative inline-flex shrink-0"
                      x-data="{
                          open: false,
                          popoverStyle: '',
                          closeTimer: null,
                          positionPopover() {
                              if (!this.$refs.trigger || !this.$refs.popover) return;
                              const tr = this.$refs.trigger.getBoundingClientRect();
                              const pop = this.$refs.popover;
                              const gap = 8;
                              const margin = 12;
                              let left = tr.left + (tr.width / 2) - (pop.offsetWidth / 2);
                              let top = tr.top - pop.offsetHeight - gap;
                              left = Math.max(margin, Math.min(document.documentElement.clientWidth - pop.offsetWidth - margin, left));
                              top = Math.max(margin, Math.min(document.documentElement.clientHeight - pop.offsetHeight - margin, top));
                              this.popoverStyle = 'left:'.concat(left, 'px;top:', top, 'px;');
                          },
                          scheduleClose() {
                              clearTimeout(this.closeTimer);
                              this.closeTimer = setTimeout(() => { this.open = false }, 200);
                          },
                          cancelClose() {
                              clearTimeout(this.closeTimer);
                              this.closeTimer = null;
                          }
                      }"
                      @mouseenter="cancelClose(); open = true; $nextTick(() => positionPopover())"
                      @mouseleave="scheduleClose()">
                    <button type="button" x-ref="trigger" aria-label="Como o score é calculado" class="inline-flex items-center justify-center w-6 h-6 rounded-full text-gray-400 hover:text-primary dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <i class="fa-pro fa-solid fa-circle-question text-sm" aria-hidden="true"></i>
                    </button>
                    <template x-teleport="body">
                        <div x-show="open"
                             x-ref="popover"
                             x-cloak
                             x-transition
                             @mouseenter="cancelClose()"
                             @mouseleave="open = false"
                             :style="'position:fixed;z-index:9999;'.concat(popoverStyle)"
                             class="w-72 min-w-0 max-w-[calc(100vw-1.5rem)] p-4 text-sm rounded-xl shadow-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700">
                            <p class="font-bold text-gray-900 dark:text-white mb-2">Como o score é calculado</p>
                            <p class="text-gray-700 dark:text-gray-300 mb-2">O score (0–100) mede sua saúde financeira com base em quatro pilares das melhores práticas de GFP:</p>
                            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-1 mb-2 text-xs break-words">
                                <li><strong>Taxa de poupança</strong> (até 35 pts): o que sobra das receitas após as despesas do mês.</li>
                                <li><strong>Respeito ao orçamento</strong> (até 25 pts): gastos por categoria dentro dos limites.</li>
                                <li><strong>Reserva de emergência</strong> (até 25 pts): saldo equivalente a meses de despesas (meta: 6 meses).</li>
                                <li><strong>Consistência</strong> (até 15 pts): registrar lançamentos com frequência no Extrato.</li>
                            </ul>
                            <p class="text-gray-600 dark:text-gray-400 text-xs break-words">A regra <strong>50/30/20</strong> (50% necessidades, 30% desejos, 20% poupança e metas) ajuda a melhorar o score. Com poucos lançamentos ou sem orçamento, o score aparece como &quot;Em análise&quot;.</p>
                            @if($isPro)
                                <p class="text-primary-600 dark:text-primary-400 text-xs mt-2 font-medium">No plano Pro você acompanha o detalhamento 50/30/20 no Vertex Bot.</p>
                            @endif
                        </div>
                    </template>
                </span>
            @endif
        </div>

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

        {{-- Description: Section at bottom (GFP) --}}
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/5">
            <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $score === 0 ? 'Com mais lançamentos no Extrato e orçamentos definidos, seu score passa a ser calculado.' : $description }}</p>
        </div>
    </div>
</div>
