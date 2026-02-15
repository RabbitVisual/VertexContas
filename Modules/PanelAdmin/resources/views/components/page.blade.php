@props(['title', 'subtitle' => null])

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        @isset($header)
            {{ $header }}
        @endisset
    </div>
    {{ $slot }}
</div>
