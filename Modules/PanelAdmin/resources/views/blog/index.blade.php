<x-paneladmin::layouts.master>
    <div class="space-y-8 animate-in fade-in duration-500">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Gestão do Blog</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Publique, edite e gerencie todo o conteúdo do seu blog.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blog.categories') }}" class="px-6 py-3 rounded-2xl bg-gray-100 dark:bg-slate-800 text-slate-600 dark:text-gray-400 font-black text-sm hover:bg-gray-200 transition-all">
                    Categorias
                </a>
                <a href="{{ route('admin.blog.create') }}" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-2xl font-black text-sm shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                    <x-icon name="plus" /> Novo Post
                </a>
            </div>
        </div>

        <!-- Stats Grid (Optional but nice) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total de Posts</span>
                <span class="text-3xl font-black text-slate-800 dark:text-white">{{ $posts->total() }}</span>
            </div>
            <div class="bg-indigo-600 p-6 rounded-[2rem] text-white shadow-xl shadow-indigo-500/20">
                <span class="text-[10px] font-black text-indigo-200 uppercase tracking-widest block mb-1">Publicados</span>
                <span class="text-3xl font-black text-white">{{ \Modules\Blog\Models\Post::where('status', 'published')->count() }}</span>
            </div>
             <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Comentários Pendentes</span>
                <span class="text-3xl font-black text-slate-800 dark:text-white">{{ \Modules\Blog\Models\Comment::where('is_approved', false)->count() }}</span>
            </div>
             <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total de Acessos</span>
                <span class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format(\Modules\Blog\Models\Post::sum('views')) }}</span>
            </div>
        </div>

        <!-- Posts Table -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Postagem</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Categoria</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Acessos</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($posts as $post)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-all">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        @if($post->featured_image)
                                            <div class="w-16 h-12 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden shrink-0">
                                                <img src="{{ asset($post->featured_image) }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-16 h-12 rounded-xl bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-slate-300 shrink-0">
                                                <x-icon name="image" class="text-xs" />
                                            </div>
                                        @endif
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 dark:text-white text-sm line-clamp-1">{{ $post->title }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Postado em: {{ $post->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black rounded-lg uppercase tracking-wider">
                                        {{ $post->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $statusTheme = [
                                            'published' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'draft' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                            'pending_review' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                        ];
                                        $statusLabels = [
                                            'published' => 'Publicado',
                                            'draft' => 'Rascunho',
                                            'pending_review' => 'Pendente',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $statusTheme[$post->status] }}">
                                        {{ $statusLabels[$post->status] }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center font-bold text-slate-500 dark:text-slate-400 text-sm">
                                    {{ number_format($post->views) }}
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                            <x-icon name="eye" class="text-xs" />
                                        </a>
                                        <a href="{{ route('admin.blog.edit', $post) }}" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                            <x-icon name="pen" class="text-xs" />
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Apagar este post permanentemente?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors">
                                                <x-icon name="trash" class="text-xs" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <x-icon name="file-circle-plus" class="text-6xl text-slate-200 mb-6" />
                                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Ainda não há posts cadastrados.</p>
                                        <a href="{{ route('admin.blog.create') }}" class="mt-6 text-primary font-black text-sm hover:underline">Criar meu primeiro post</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($posts->hasPages())
                <div class="p-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-paneladmin::layouts.master>
