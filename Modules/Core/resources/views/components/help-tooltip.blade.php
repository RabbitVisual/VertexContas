@props([
    'content' => '',
    'tourTrigger' => null,
    'position' => 'top',
    'teleport' => true,
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center shrink-0']) }}
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
              left = Math.max(margin, Math.min(document.documentElement.clientWidth - pop.offsetWidth - margin, left));
              let top = tr.top - pop.offsetHeight - gap;
              if (top < margin) top = tr.bottom + gap;
              top = Math.max(margin, Math.min(document.documentElement.clientHeight - pop.offsetHeight - margin, top));
              this.popoverStyle = 'left:'.concat(left, 'px;top:', top, 'px;');
          },
          scheduleClose() { clearTimeout(this.closeTimer); this.closeTimer = setTimeout(() => { this.open = false }, 200); },
          cancelClose() { clearTimeout(this.closeTimer); this.closeTimer = null; }
      }"
      @mouseenter="cancelClose(); open = true; $nextTick(() => positionPopover())"
      @mouseleave="scheduleClose()"
      class="relative inline-flex">
    <button type="button" x-ref="trigger" aria-label="Dica"
            class="inline-flex items-center justify-center w-6 h-6 rounded-full text-slate-400 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">
        <i class="fa-pro fa-solid fa-circle-question text-sm" aria-hidden="true"></i>
    </button>

    @if($teleport)
    <template x-teleport="body">
        <div x-show="open" x-ref="popover" x-cloak x-transition
             @mouseenter="cancelClose()" @mouseleave="open = false"
             :style="'position:fixed;z-index:9999;'.concat(popoverStyle)"
             class="w-72 min-w-0 max-w-[calc(100vw-1.5rem)] p-4 text-sm rounded-xl shadow-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700">
            <p class="text-gray-700 dark:text-gray-300 normal-case">{{ $content }}</p>
            @if($tourTrigger)
            <p class="mt-2">
                <button type="button"
                        @click="open = false; if (window.startVertexTour) window.startVertexTour('{{ $tourTrigger }}');"
                        class="text-sm font-medium text-primary hover:underline focus:outline-none normal-case">
                    Ver tour completo
                </button>
            </p>
            @endif
        </div>
    </template>
    @else
    <div x-show="open" x-cloak x-transition
         @class([
             'absolute z-50 w-64 p-3 text-sm rounded-xl shadow-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700',
             'bottom-full left-1/2 -translate-x-1/2 mb-2' => $position === 'top',
             'top-full left-1/2 -translate-x-1/2 mt-2' => $position === 'bottom',
             'left-full top-1/2 -translate-y-1/2 ml-2' => $position === 'right',
             'right-full top-1/2 -translate-y-1/2 mr-2' => $position === 'left',
         ])
         style="display: none;">
        <p class="text-gray-700 dark:text-gray-300 normal-case">{{ $content }}</p>
        @if($tourTrigger)
        <p class="mt-2">
            <button type="button"
                    @click="open = false; if (window.startVertexTour) window.startVertexTour('{{ $tourTrigger }}');"
                    class="text-sm font-medium text-primary hover:underline focus:outline-none normal-case">
                Ver tour completo
            </button>
        </p>
        @endif
    </div>
    @endif
</span>
