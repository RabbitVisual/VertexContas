@props([
    'content' => '',
    'tourTrigger' => null,
    'position' => 'top',
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center']) }}
      x-data="{ open: false }"
      @mouseenter="open = true"
      @mouseleave="open = false"
      class="relative inline-flex">
    <button type="button"
            aria-label="Dica"
            class="inline-flex items-center justify-center w-6 h-6 rounded-full text-slate-400 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">
        <i class="fa-pro fa-solid fa-circle-question text-sm"></i>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak
         @class([
             'absolute z-50 w-64 p-3 text-sm rounded-xl shadow-xl border bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-slate-200/50 dark:border-slate-700/50',
             'bottom-full left-1/2 -translate-x-1/2 mb-2' => $position === 'top',
             'top-full left-1/2 -translate-x-1/2 mt-2' => $position === 'bottom',
             'left-full top-1/2 -translate-y-1/2 ml-2' => $position === 'right',
             'right-full top-1/2 -translate-y-1/2 mr-2' => $position === 'left',
         ])
         style="display: none;">
        <p class="text-gray-700 dark:text-gray-300">{{ $content }}</p>
        @if($tourTrigger)
        <p class="mt-2">
            <button type="button"
                    @click="open = false; if (window.startVertexTour) window.startVertexTour('{{ $tourTrigger }}');"
                    class="text-sm font-medium text-primary hover:underline focus:outline-none">
                Ver tour completo
            </button>
        </p>
        @endif
    </div>
</span>
