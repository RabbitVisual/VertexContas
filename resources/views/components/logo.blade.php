@props(['type' => 'full', 'class' => '', 'light' => false, 'size' => 'text-2xl'])

@php
    $basePath = asset('storage/logos');

    // Size mapping for the container/image
    $dimensions = match($size) {
        'text-sm' => 'h-6',
        'text-base' => 'h-8',
        'text-xl' => 'h-10',
        'text-2xl' => 'h-12',
        'text-3xl' => 'h-14',
        default => 'h-10'
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 select-none ' . $class]) }}>
    @if($type === 'icon')
        <img src="{{ $basePath . '/favicon.svg' }}"
             alt="Vertex Contas"
             class="{{ $dimensions }} w-auto transition-transform duration-300 hover:scale-105"
             loading="eager">
    @else
        {{-- Light Mode Logo --}}
        <img src="{{ $basePath . '/logo.svg' }}"
             alt="Vertex Contas"
             class="{{ $dimensions }} w-auto transition-transform duration-300 hover:scale-105 {{ $light ? 'hidden' : 'dark:hidden' }}"
             loading="eager">

        {{-- Dark Mode / Light Variant Logo --}}
        <img src="{{ $basePath . '/logo-white.svg' }}"
             alt="Vertex Contas"
             class="{{ $dimensions }} w-auto transition-transform duration-300 hover:scale-105 {{ $light ? 'block' : 'hidden dark:block' }}"
             loading="eager">
    @endif
</div>
