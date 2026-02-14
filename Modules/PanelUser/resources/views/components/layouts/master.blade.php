<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Anti-FOUC: deve ser o primeiro script (Flowbite/Tailwind dark mode + modo privacidade) --}}
    <script>
        (function() {
            var isDark = localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);

            var isHidden = localStorage.getItem('sensitive-hidden') === 'true';
            if (document.body) {
                document.body.classList.toggle('sensitive-hidden', isHidden);
            } else {
                window.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.toggle('sensitive-hidden', isHidden);
                });
            }
        })();
    </script>

    <title>{{ $title ?? 'Vertex Contas - Painel do Usuário' }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('storage/logos/favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php $isPro = auth()->user()?->isPro() ?? false; @endphp
<body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased">
    @if($isPro)
        {{-- Layout PRO: Vertex CBAV style - sidebar com logo, navbar complementar --}}
        <div class="flex h-screen overflow-hidden">
            <x-paneluser::layouts.sidebar />
            <div class="flex-1 flex flex-col overflow-hidden sm:ml-64 transition-all duration-300">
                <x-paneluser::layouts.navbar />
                <main id="main-content" class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
                    <x-core::inspection-banner />
                    @include('paneluser::components.flash-messages', ['class' => 'mb-6'])
                    {{ $slot }}
                    <x-paneluser::inspection-modal />
                    <x-notifications::toast />
                </main>
            </div>
        </div>
    @else
        {{-- Layout FREE: navbar full width, sidebar lateral --}}
        <div class="antialiased bg-gray-50 dark:bg-gray-900 min-h-screen">
            <x-paneluser::layouts.navbar />
            <x-paneluser::layouts.sidebar />
            <main id="main-content" class="p-4 md:ml-64 h-auto pt-20">
                <x-core::inspection-banner />
                @include('paneluser::components.flash-messages', ['class' => 'mb-6'])
                {{ $slot }}
                <x-paneluser::inspection-modal />
                <x-notifications::toast />
            </main>
        </div>
    @endif
    <x-loading-overlay />
    @stack('scripts')

    {{-- Corrige aviso aria-hidden: usa inert em vez de aria-hidden e move foco ao fechar (WAI-ARIA) --}}
    <script>
    (function() {
        var aside = document.getElementById('logo-sidebar');
        var toggle = document.getElementById('drawer-toggle-btn');
        if (!aside) return;

        function syncInert() {
            var hidden = aside.getAttribute('aria-hidden') === 'true';
            if (hidden) {
                if (aside.contains(document.activeElement)) {
                    (toggle && toggle.offsetParent) ? toggle.focus() : document.body.focus();
                }
                aside.setAttribute('inert', '');
                aside.removeAttribute('aria-hidden');
            } else {
                aside.removeAttribute('inert');
            }
        }

        var observer = new MutationObserver(function() { syncInert(); });
        observer.observe(aside, { attributes: true, attributeFilter: ['aria-hidden'] });

        if (aside.getAttribute('aria-hidden') === 'true') syncInert();
    })();
    </script>
</body>
</html>
