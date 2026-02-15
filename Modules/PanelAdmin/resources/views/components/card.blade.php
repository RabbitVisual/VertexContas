@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden']) }}>
    @if($title || $subtitle || isset($header))
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            @if($title)
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
            @endif
            @if($subtitle)
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
            @endif
            @isset($header)
                {{ $header }}
            @endisset
        </div>
    @endif
    {{ $slot }}
</div>
