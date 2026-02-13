<x-paneladmin::layouts.master>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Artigos da Wiki</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Gerencie o conteúdo técnico da base de conhecimento.</p>
            </div>
            <a href="{{ route('admin.wiki.articles.create') }}"
                class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-2xl font-black text-sm shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                <x-icon name="plus" /> Novo Artigo
            </a>
        </div>
\n        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Artigo</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Categoria</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Acessos</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                        @foreach($articles as $article)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 dark:text-white">{{ $article->title }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium tracking-tight">Autor: {{ $article->author->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-primary/5 text-primary text-[10px] font-black rounded-lg uppercase">
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
                                    {{ number_format($article->views) }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.wiki.articles.edit', $article) }}" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                            <x-icon name="pen" class="text-xs" />
                                        </a>
                                        <form action="{{ route('admin.wiki.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors">
                                                <x-icon name="trash" class="text-xs" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($articles->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/30 border-t border-gray-50 dark:border-gray-800">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-paneladmin::layouts.master>
