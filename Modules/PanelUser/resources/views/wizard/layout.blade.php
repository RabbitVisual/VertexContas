<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function() {
            var isDark = localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    <title>{{ $title ?? 'Configuração inicial - ' . config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ branding_favicon_url() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased flex flex-col">
    <header class="shrink-0 border-b border-gray-200 dark:border-white/5 bg-white dark:bg-gray-950" role="banner">
        <div class="max-w-3xl mx-auto px-4 py-4 sm:px-6 flex items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors shrink-0" aria-label="{{ config('app.name') }} - Início">
                <x-logo class="h-8 w-auto" />
            </a>
            @if(isset($isPro) && !$isPro)
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 shrink-0" title="Seu plano atual">{{ plan_free_name() }}</span>
            @endif
            @isset($step)
                @php $total = $totalSteps ?? 5; $current = $step + 1; @endphp
                <nav class="flex items-center gap-2" aria-label="Progresso da configuração">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest sr-only sm:not-sr-only">Passo {{ $current }} de {{ $total }}</span>
                    <ol class="flex items-center gap-1 sm:gap-2" role="list">
                        @for ($i = 1; $i <= $total; $i++)
                            <li class="flex items-center" {{ $i < $total ? 'aria-hidden="true"' : '' }}>
                                <span class="inline-flex h-2 w-2 rounded-full transition-colors {{ $i <= $current ? 'bg-primary-500 dark:bg-primary-400' : 'bg-gray-200 dark:bg-white/10' }}" {{ $i === $current ? 'aria-current="step"' : '' }}></span>
                                @if($i < $total)
                                    <span class="hidden sm:inline w-4 h-0.5 bg-gray-200 dark:bg-white/10 mx-0.5" aria-hidden="true"></span>
                                @endif
                            </li>
                        @endfor
                    </ol>
                </nav>
            @endisset
        </div>
        @isset($step)
            <div class="max-w-3xl mx-auto px-4 pb-3">
                <div class="h-1.5 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden" role="progressbar" aria-valuenow="{{ $step + 1 }}" aria-valuemin="1" aria-valuemax="{{ $totalSteps ?? 5 }}" aria-label="Progresso">
                    <div class="h-full bg-primary-500 dark:bg-primary-400 rounded-full transition-all duration-300" style="width: {{ (($step + 1) / ($totalSteps ?? 5)) * 100 }}%"></div>
                </div>
            </div>
        @endisset
    </header>

    <main class="flex-1 flex flex-col items-center justify-center w-full max-w-3xl mx-auto px-4 py-8 sm:py-12">
        @include('paneluser::components.flash-messages', ['class' => 'mb-6 w-full'])
        @yield('content')
    </main>
</body>
</html>
