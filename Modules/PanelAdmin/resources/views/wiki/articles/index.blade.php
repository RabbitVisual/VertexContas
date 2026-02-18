<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Artigos Wiki</x-slot>

    <x-paneladmin::page title="Artigos da Wiki" subtitle="Base de conhecimento técnica para o suporte.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <x-slot name="header">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.wiki.categories') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="folder" style="duotone" class="w-4 h-4" /> Categorias
                </a>
                <a href="{{ route('admin.wiki.articles.create') }}" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Novo Artigo
                </a>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <x-icon name="file-lines" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="circle-check" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Publicados</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $publishedCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <x-icon name="file-pen" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Rascunhos</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $draftCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <x-paneladmin::card>
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Artigo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Categoria</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Acessos</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($articles as $article)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0">
                                    <x-icon name="file-lines" style="duotone" class="w-5 h-5 text-slate-500 dark:text-slate-400" />
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-900 dark:text-white block truncate">{{ $article->title }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Autor: {{ $article->author?->name ?? '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/20 dark:text-emerald-400">
                                <x-icon name="folder" style="duotone" class="w-3.5 h-3.5" />
                                {{ $article->category?->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($article->is_published)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    <x-icon name="circle-check" style="duotone" class="w-3.5 h-3.5" /> Publicado
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    <x-icon name="file-pen" style="duotone" class="w-3.5 h-3.5" /> Rascunho
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center font-bold text-slate-600 dark:text-slate-400 text-sm tabular-nums">
                            {{ format_number($article->views ?? 0, 0) }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.wiki.articles.edit', $article) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-[#11C76F]/10 hover:text-[#11C76F] transition-colors" title="Editar">
                                    <x-icon name="pen" style="duotone" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('admin.wiki.articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este artigo permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors" title="Excluir">
                                        <x-icon name="trash" style="duotone" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <x-icon name="file-lines" style="duotone" class="w-8 h-8 text-slate-400" />
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum artigo cadastrado.</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">Crie o primeiro artigo da base de conhecimento.</p>
                                <a href="{{ route('admin.wiki.articles.create') }}" class="text-[#11C76F] font-bold text-sm hover:underline flex items-center gap-2">
                                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Novo artigo
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>
            @if($articles->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $articles->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
