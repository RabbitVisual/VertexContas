<x-pwa::layouts.master :title="config('pwa.short_name') . ' — App'">
    <div class="space-y-8">
        <header class="text-center">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white font-display">
                {{ config('pwa.app_name') }}
            </h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Aplicativo progressivo (PWA) — use como app no celular ou desktop.
            </p>
        </header>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500 dark:text-gray-400">Versão do cache</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ config('pwa.cache_version', 'v1') }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500 dark:text-gray-400">PWA ativo</dt>
                    <dd class="font-medium {{ config('pwa.enabled') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500' }}">
                        {{ config('pwa.enabled') ? 'Sim' : 'Não' }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Como instalar</h2>
            <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">1</span>
                    <span><strong>Android / Chrome:</strong> Abra o menu (⋮) e toque em &quot;Instalar app&quot; ou &quot;Adicionar à tela inicial&quot;.</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">2</span>
                    <span><strong>iPhone / Safari:</strong> Toque em <strong>Compartilhar</strong> e depois em <strong>Adicionar à Tela de Início</strong>.</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">3</span>
                    <span><strong>Desktop:</strong> Procure o ícone de instalação na barra de endereço ou no menu do navegador.</span>
                </li>
            </ul>
        </div>

        @auth
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url(config('pwa.start_url', '/user')) }}" class="inline-flex items-center justify-center min-h-[44px] px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-colors">
                Abrir app
            </a>
            <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center justify-center min-h-[44px] px-6 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Nova transação
            </a>
        </div>
        @else
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center min-h-[44px] px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-colors">
                Entrar para usar o app
            </a>
        </div>
        @endauth
    </div>
</x-pwa::layouts.master>
