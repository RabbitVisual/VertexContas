<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Versões PWA</x-slot>

    <x-paneladmin::page title="Versões do App" subtitle="Releases e atualização forçada.">
        <x-slot name="header">
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.pwa.dashboard') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium">
                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
                </a>
                <a href="{{ route('admin.pwa.versions.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova versão
                </a>
            </div>
        </x-slot>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <x-paneladmin::card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 sticky top-0">
                        <tr>
                            <th class="px-6 py-4">Versão</th>
                            <th class="px-6 py-4">Notas</th>
                            <th class="px-6 py-4">Atualização forçada</th>
                            <th class="px-6 py-4">Publicada em</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($versions as $v)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900 dark:text-white">{{ $v->version }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400 max-w-xs truncate">{{ Str::limit($v->release_notes, 50) ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($v->is_force_update)
                                        <span class="px-2 py-1 text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-500/20 rounded">Sim</span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500">Não</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $v->released_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.pwa.versions.edit', $v) }}" class="inline-flex items-center gap-1 text-[#11C76F] hover:underline font-medium">
                                        <x-icon name="pencil" style="duotone" class="w-4 h-4" /> Editar
                                    </a>
                                    <form action="{{ route('admin.pwa.versions.destroy', $v) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Remover esta versão?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline font-medium text-sm">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">Nenhuma versão cadastrada. Crie a primeira para ativar atualização forçada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($versions->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $versions->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
