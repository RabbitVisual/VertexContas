@props([
    'tourId' => null,
    'label' => 'Ver tour desta página',
    'variant' => 'secondary',
])

@push('scripts')
    @vite('resources/js/tours.js')
@endpush

@if($tourId)
<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}
     x-data="tourManager({{ json_encode(auth()->user()?->isPro() ? 'pro' : 'free') }})">
    <button type="button"
            @click="startTour('{{ $tourId }}')"
            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg border transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2
                   {{ $variant === 'primary' ? 'bg-primary text-white border-primary hover:bg-primary-dark focus:ring-primary' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 focus:ring-slate-400' }}">
        <i class="fa-pro fa-solid fa-circle-question text-base"></i>
        <span>{{ $label }}</span>
    </button>
    {{ $slot ?? '' }}
</div>
@endif
