@php
    $enabled = \Illuminate\Support\Facades\Schema::hasTable('settings')
        ? (bool) (setting('homepage_show_back_to_top') ?? true)
        : true;
@endphp
@if($enabled)
<div x-data="{ show: false }"
     x-init="
        window.addEventListener('scroll', () => {
            show = window.scrollY > 400;
        });
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-75"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-75"
     class="fixed bottom-6 right-6 z-40"
     style="display: none;">
    <button type="button"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="w-12 h-12 rounded-full bg-primary hover:bg-primary-dark text-white shadow-lg shadow-primary/30 flex items-center justify-center transition-all hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
            aria-label="Voltar ao topo">
        <x-icon name="arrow-up" style="solid" class="w-5 h-5" />
    </button>
</div>
@endif
