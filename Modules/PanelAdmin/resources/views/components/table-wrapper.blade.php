@props([])

<div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="sticky top-0 z-10 bg-slate-50 dark:bg-slate-800/95 backdrop-blur border-b border-slate-200 dark:border-slate-700">
            {{ $thead ?? '' }}
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
