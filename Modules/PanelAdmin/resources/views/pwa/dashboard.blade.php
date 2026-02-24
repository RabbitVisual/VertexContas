<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">PWA</x-slot>

    <x-paneladmin::page title="App PWA" subtitle="Instalações e versões do aplicativo instalável.">
        <x-slot name="header">
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.pwa.installs') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    <x-icon name="mobile-screen" style="duotone" class="w-4 h-4" /> Instalações
                </a>
                <a href="{{ route('admin.pwa.versions.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                    <x-icon name="code-branch" style="duotone" class="w-4 h-4" /> Versões
                </a>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-[#11C76F]/10 flex items-center justify-center">
                        <x-icon name="mobile-screen" style="duotone" class="w-6 h-6 text-[#11C76F]" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total de instalações</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalInstalls }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="crown" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Pro</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $proCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <x-icon name="user" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Gratuito</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $freeCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                        <x-icon name="code-branch" style="duotone" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Última versão</p>
                        <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $latestRelease?->version ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <x-paneladmin::card>
            <div class="p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4">Instalações por versão</h3>
                @if($installsByVersion->isNotEmpty())
                    <ul class="space-y-2">
                        @foreach($installsByVersion as $ver => $count)
                            <li class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                                <span class="font-mono text-sm text-slate-700 dark:text-slate-300">{{ $ver }}</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Nenhuma instalação registrada ainda.</p>
                @endif
            </div>
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
