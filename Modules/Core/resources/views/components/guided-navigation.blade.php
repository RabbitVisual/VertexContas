@props([
    'steps' => [],
    'title' => 'Próximos passos recomendados',
])

@if(count($steps) > 0)
<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 p-4']) }}>
    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
        <x-icon name="route" style="duotone" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
        {{ $title }}
    </h4>
    <ul class="space-y-2">
        @foreach($steps as $step)
        <li class="flex items-center gap-2 text-sm">
            @if(!empty($step['url']))
            <a href="{{ $step['url'] }}" class="text-emerald-600 dark:text-emerald-400 hover:underline font-medium">{{ $step['label'] }}</a>
            @else
            <span class="text-gray-600 dark:text-gray-400">{{ $step['label'] }}</span>
            @endif
        </li>
        @endforeach
    </ul>
</div>
@endif
