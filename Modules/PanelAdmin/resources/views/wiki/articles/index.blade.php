<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Artigos Wiki</x-slot>

    <x-paneladmin::page title="Artigos da Wiki" subtitle="Gerencie o conteúdo técnico da base de conhecimento.">
        <x-slot name="header">
            <a href="{{ route('admin.wiki.articles.create') }}" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                <x-icon name="plus" style="duotone" class="w-4 h-4" /> Novo Artigo
            </a>
        </x-slot>

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
                        @foreach($articles as $article)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 dark:text-white">{{ $article->title }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium tracking-tight">Autor: {{ $article->author->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-[#11C76F]/10 text-[#11C76F] text-xs font-bold rounded-lg uppercase">
                                        {{ $article->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-center">
                                        @if($article->is_published)
                                            <span class="flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black rounded-lg uppercase">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Publicado
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1.5 px-3 py-1 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black rounded-lg uppercase">
                                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div> Rascunho
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center font-bold text-slate-500 dark:text-slate-400 text-sm">
                                    {{ format_number($article->views, 0) }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.wiki.articles.edit', $article) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-[#11C76F] transition-colors">
                                            <x-icon name="pen" style="duotone" class="text-xs" />
                                        </a>
                                        <form action="{{ route('admin.wiki.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors">
                                                <x-icon name="trash" style="duotone" class="text-xs" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
            </x-paneladmin::table-wrapper>
            @if($articles->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $articles->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
