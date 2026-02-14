@php
    $class = $class ?? 'mb-6';
@endphp

@if(session('success') || session('error') || $errors->any())
<div class="{{ trim($class . ' space-y-4') }}">
@if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3">
        <x-icon name="circle-check" style="solid" class="text-emerald-600 dark:text-emerald-400 shrink-0" />
        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 flex items-center gap-3">
        <x-icon name="triangle-exclamation" style="solid" class="text-rose-600 dark:text-rose-400 shrink-0" />
        <p class="text-sm font-medium text-rose-800 dark:text-rose-200">{{ session('error') }}</p>
    </div>
@endif

@if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800">
        <div class="flex items-center gap-3 mb-2">
            <x-icon name="triangle-exclamation" style="solid" class="text-rose-600 dark:text-rose-400 shrink-0" />
            <p class="text-sm font-bold text-rose-800 dark:text-rose-200">Corrija os erros abaixo antes de continuar.</p>
        </div>
        <ul class="text-sm text-rose-700 dark:text-rose-300 space-y-1 ml-7">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
</div>
@endif
