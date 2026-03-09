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
@php
    $user = auth()->user();
    $isPro = $user?->isPro() ?? false;
    $limitService = $user ? app(\Modules\Core\Services\SubscriptionLimitService::class) : null;
    $exceededResources = $user && $limitService ? $limitService->getExceededResources($user) : [];
    $subscriptionReadOnly = !empty($exceededResources);
@endphp
<body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased">
    @if($isPro)
        {{-- Layout PRO: Vertex CBAV style - sidebar com logo, navbar complementar --}}
        <div class="flex h-screen overflow-hidden">
            <x-paneluser::layouts.sidebar />
            <div class="flex-1 flex flex-col overflow-hidden sm:ml-64 transition-[margin] duration-300">
                <x-paneluser::layouts.navbar />
                <main id="main-content" class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
                    <x-core::inspection-banner />
                    @if(!empty($subscriptionReadOnly))
                        <div class="mb-4 rounded-2xl border border-amber-300/70 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 sm:px-5 sm:py-4 flex flex-col sm:flex-row gap-3 sm:items-center">
                            <div class="flex items-start gap-3 flex-1">
                                <div class="mt-0.5 shrink-0 w-8 h-8 rounded-xl bg-amber-500/15 flex items-center justify-center text-amber-700 dark:text-amber-300">
                                    <x-icon name="triangle-exclamation" style="duotone" class="w-4 h-4" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-100">
                                        Você possui recursos acima do limite do seu plano atual.
                                    </p>
                                    <p class="text-xs text-amber-800/90 dark:text-amber-100/80 mt-0.5">
                                        Enquanto essa folga existir, o sistema entra em modo somente leitura:
                                        você continua vendo tudo, mas precisa <strong>remover itens</strong> ou <strong>fazer upgrade</strong> para voltar a criar e editar.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-amber-950 text-xs font-black uppercase tracking-[0.18em] shadow-sm transition-colors">
                                    <x-icon name="crown" style="solid" class="w-4 h-4 mr-1" />
                                    Fazer upgrade
                                </a>
                            </div>
                        </div>
                    @endif
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
                    @if(!empty($subscriptionReadOnly))
                        <div class="mb-4 rounded-2xl border border-amber-300/70 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 sm:px-5 sm:py-4 flex flex-col sm:flex-row gap-3 sm:items-center">
                            <div class="flex items-start gap-3 flex-1">
                                <div class="mt-0.5 shrink-0 w-8 h-8 rounded-xl bg-amber-500/15 flex items-center justify-center text-amber-700 dark:text-amber-300">
                                    <x-icon name="triangle-exclamation" style="duotone" class="w-4 h-4" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-100">
                                        Você possui recursos acima do limite do seu plano atual.
                                    </p>
                                    <p class="text-xs text-amber-800/90 dark:text-amber-100/80 mt-0.5">
                                        Você pode continuar consultando seus dados, mas novas criações e edições ficam temporariamente bloqueadas até ajustar seus cadastros ou atualizar o plano.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-amber-950 text-xs font-black uppercase tracking-[0.18em] shadow-sm transition-colors">
                                    <x-icon name="crown" style="solid" class="w-4 h-4 mr-1" />
                                    Ver planos
                                </a>
                            </div>
                        </div>
                    @endif
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

    @if(auth()->check() && !($inspectionReadOnly ?? false) && !($subscriptionReadOnly ?? false) && Route::has('core.transactions.create'))
        <a href="{{ route('core.transactions.create') }}"
           class="fixed bottom-6 right-6 z-40 flex items-center gap-3 px-6 py-4 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-base shadow-xl shadow-primary-500/30 hover:scale-105 active:scale-100 transition-all focus:ring-4 focus:ring-primary-500/30"
           aria-label="Nova transação">
            <x-icon name="plus" style="solid" class="w-6 h-6" />
            <span class="hidden sm:inline">Nova Transação</span>
        </a>
    @endif

    @if(config('pwa.enabled', true))
        <x-pwa::install-banner />
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
