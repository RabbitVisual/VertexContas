<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Instalações PWA</x-slot>

    <x-paneladmin::page title="Instalações do App" subtitle="Quem instalou o app, versão e plano.">
        <x-slot name="header">
            <a href="{{ route('admin.pwa.dashboard') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
            </a>
        </x-slot>

        <x-paneladmin::card>
            <x-slot name="header">
                <form action="{{ route('admin.pwa.installs') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="text" name="version" value="{{ request('version') }}" placeholder="Versão" class="rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-800 text-sm w-32">
                    <select name="platform" class="rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-800 text-sm">
                        <option value="">Plataforma</option>
                        <option value="web" {{ request('platform') === 'web' ? 'selected' : '' }}>Web</option>
                        <option value="android" {{ request('platform') === 'android' ? 'selected' : '' }}>Android</option>
                        <option value="ios" {{ request('platform') === 'ios' ? 'selected' : '' }}>iOS</option>
                    </select>
                    <select name="plan" class="rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-800 text-sm">
                        <option value="">Plano</option>
                        <option value="pro" {{ request('plan') === 'pro' ? 'selected' : '' }}>Pro</option>
                        <option value="free" {{ request('plan') === 'free' ? 'selected' : '' }}>Gratuito</option>
                    </select>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 text-sm font-medium">
                        <x-icon name="magnifying-glass" style="duotone" class="w-4 h-4" /> Filtrar
                    </button>
                </form>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 sticky top-0">
                        <tr>
                            <th class="px-6 py-4">Usuário</th>
                            <th class="px-6 py-4">Versão</th>
                            <th class="px-6 py-4">Plataforma</th>
                            <th class="px-6 py-4">Plano</th>
                            <th class="px-6 py-4">Instalado em</th>
                            <th class="px-6 py-4">Última vez</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($installs as $install)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                <td class="px-6 py-4">
                                    @if($install->user)
                                        <a href="{{ route('admin.users.show', $install->user) }}" class="font-medium text-[#11C76F] hover:underline">{{ $install->user->email }}</a>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $install->app_version }}</td>
                                <td class="px-6 py-4">{{ $install->platform }}</td>
                                <td class="px-6 py-4">
                                    @if($install->is_pro)
                                        <span class="px-2 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-500/20 rounded">Pro</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold text-slate-500 bg-slate-100 dark:bg-slate-600 rounded">Gratuito</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $install->installed_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">{{ $install->last_seen_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">Nenhuma instalação encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($installs->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $installs->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
