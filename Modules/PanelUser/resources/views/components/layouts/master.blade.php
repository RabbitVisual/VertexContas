<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
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

    <title>{{ (config('pwa.app_name') ?? config('app.name')) . ' - ' . ($title ?? 'Início') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ branding_favicon_url() }}">

    @if(config('pwa.enabled', true))
    <link rel="manifest" href="{{ url(config('pwa.manifest_url', '/manifest.webmanifest')) }}">
    <meta name="theme-color" content="{{ config('pwa.theme_color', '#11C76F') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="pwa-version" content="{{ config('pwa.cache_version', 'v1') }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(request()->routeIs('core.dashboard') || request()->routeIs('core.reports.*') || request()->routeIs('user.index'))
        @vite('resources/js/charts-apex.js')
    @endif
</head>
@php $isPro = auth()->user()?->isPro() ?? false; @endphp
<body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased">
    @if($isPro)
        {{-- Layout PRO: Vertex CBAV style - sidebar com logo, navbar complementar --}}
        <div class="flex h-screen overflow-hidden">
            <x-paneluser::layouts.sidebar />
            <div class="flex-1 flex flex-col overflow-hidden sm:ml-64 transition-[margin] duration-300">
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
        {{-- Layout FREE: mesma estrutura que PRO — sidebar à esquerda, navbar + conteúdo à direita (sem sobreposição) --}}
        <div class="flex min-h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
            <x-paneluser::layouts.sidebar />
            <div class="flex-1 flex flex-col min-h-screen sm:ml-64 transition-[margin] duration-300">
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
    @endif
    <x-loading-overlay />

    @if(auth()->check() && (auth()->user()->show_assistant ?? true) && isset($vertexBot) && ($vertexBot['insight'] ?? null))
        <x-gamification::vertex-bot :insight="$vertexBot['insight']" :financial-score="$vertexBot['financial_score'] ?? 0" :tour-id="$pageTourId ?? null" />
    @endif

    <x-vertexchat::widget />

    @if(config('pwa.enabled', true))
    {{-- Banner "Instalar app" (escondido se já instalado ou se usuário dispensou) --}}
    <div id="pwa-install-banner" class="hidden fixed bottom-0 left-0 right-0 z-40 p-4 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 shadow-lg safe-area-bottom md:left-64" style="padding-left: max(1rem, env(safe-area-inset-left)); padding-right: max(1rem, env(safe-area-inset-right)); padding-bottom: max(1rem, env(safe-area-inset-bottom));" role="region" aria-label="Instalar aplicativo">
        <div class="flex flex-wrap items-center justify-between gap-3 max-w-4xl mx-auto">
            <div class="flex items-center gap-3 min-w-0">
                <span class="flex items-center justify-center w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl bg-[#11C76F]/10 text-[#11C76F] shrink-0">
                    <i class="fa-pro fa-solid fa-mobile-screen"></i>
                </span>
                <div class="min-w-0">
                    <p class="font-semibold text-slate-900 dark:text-white text-sm">Instalar {{ config('pwa.short_name', 'Vertex') }}</p>
                    <p id="pwa-install-hint" class="text-xs text-slate-500 dark:text-slate-400">Use como app no celular: atalho na tela inicial.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" id="pwa-install-btn" class="hidden min-h-[44px] min-w-[44px] inline-flex items-center justify-center px-4 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors text-sm">
                    Instalar
                </button>
                <button type="button" id="pwa-install-dismiss" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg transition-colors" aria-label="Dispensar">
                    <i class="fa-pro fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Tour completion: envia para o backend para analytics e gamificação --}}
    @auth
    <script>
    (function() {
        document.addEventListener('tour-completed', function(e) {
            var tourId = e.detail && e.detail.tourId;
            if (!tourId) return;
            var url = '{{ route("user.tour.complete") }}';
            var csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').content;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ tour_id: tourId })
            }).catch(function() {});
        });
    })();
    </script>
    @endauth

    @stack('scripts')

    @if(config('pwa.enabled', true) && config('pwa.sw_registration', true))
    <script>
    (function() {
        var swUrl = '{{ url(config("pwa.sw_url", "/sw.js")) }}';
        var versionUrl = '{{ url("/api/pwa/version") }}';
        var installedUrl = '{{ url("/api/pwa/installed") }}';
        var currentVersion = document.querySelector('meta[name="pwa-version"]') && document.querySelector('meta[name="pwa-version"]').getAttribute('content') || 'v1';
        var csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').content;

        function getFingerprint() {
            var s = (navigator.userAgent || '') + (screen.width + 'x' + screen.height) + (new Date().getTimezoneOffset());
            var h = 0;
            for (var i = 0; i < s.length; i++) {
                h = ((h << 5) - h) + s.charCodeAt(i) | 0;
            }
            return 'fp_' + Math.abs(h).toString(36);
        }

        function recordInstall() {
            fetch(installedUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    app_version: currentVersion,
                    device_fingerprint: getFingerprint(),
                    platform: /Android/i.test(navigator.userAgent) ? 'android' : (/iPad|iPhone|iPod/i.test(navigator.userAgent) ? 'ios' : 'web')
                })
            }).catch(function() {});
        }

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register(swUrl).then(function() {
                navigator.serviceWorker.addEventListener('controllerchange', function() {
                    window.location.reload();
                });
            }).catch(function() {});
        }

        window.addEventListener('appinstalled', recordInstall);
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            recordInstall();
        }

        fetch(versionUrl, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.is_force_update && data.version && data.version !== currentVersion) {
                    if (window.confirm('Uma nova versão do app está disponível. Recarregar agora?')) {
                        if (navigator.serviceWorker && navigator.serviceWorker.controller) {
                            navigator.serviceWorker.controller.postMessage({ type: 'SKIP_WAITING' });
                        } else {
                            window.location.reload();
                        }
                    }
                }
            })
            .catch(function() {});

        var installBanner = document.getElementById('pwa-install-banner');
        var installBtn = document.getElementById('pwa-install-btn');
        var installHint = document.getElementById('pwa-install-hint');
        var dismissBtn = document.getElementById('pwa-install-dismiss');
        var deferredPrompt = null;
        var isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        var dismissed = localStorage.getItem('pwa-install-dismissed') === '1';

        function showInstallBanner() {
            if (!installBanner || isStandalone || dismissed) return;
            installBanner.classList.remove('hidden');
        }

        function hideInstallBanner() {
            if (installBanner) installBanner.classList.add('hidden');
            localStorage.setItem('pwa-install-dismissed', '1');
        }

        if (installBanner && dismissBtn) {
            dismissBtn.addEventListener('click', function() { hideInstallBanner(); });
        }

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            if (installBtn) {
                installBtn.classList.remove('hidden');
                if (installHint) installHint.textContent = 'Adicione à tela inicial para abrir como app.';
            }
            showInstallBanner();
        });

        if (installBtn) {
            installBtn.addEventListener('click', function() {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choice) {
                    if (choice.outcome === 'accepted') hideInstallBanner();
                    deferredPrompt = null;
                });
            });
        }

        if (/iPad|iPhone|iPod/i.test(navigator.userAgent) && !isStandalone && !dismissed) {
            if (installHint) installHint.innerHTML = 'Toque em <strong>Compartilhar</strong> <i class="fa-solid fa-arrow-up-from-bracket text-xs"></i> e depois em <strong>Adicionar à Tela de Início</strong>.';
            showInstallBanner();
        }
    })();
    </script>
    @endif

    @if($inspectionSyncActive ?? false)
    {{-- Real-time sync: segue a mesma tela que o agente está visualizando --}}
    <script>
    (function() {
        var syncUrl = '{{ route("user.inspection.sync") }}';
        var currentPath = window.location.pathname + (window.location.search || '');
        var interval = 2500;

        setInterval(function() {
            fetch(syncUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data && data.active && data.url) {
                        var targetPath = data.url.replace(window.location.origin, '').split('#')[0];
                        if (targetPath && targetPath !== currentPath) {
                            window.location.href = data.url;
                        }
                    }
                })
                .catch(function() {});
        }, interval);
    })();
    </script>
    @endif

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
