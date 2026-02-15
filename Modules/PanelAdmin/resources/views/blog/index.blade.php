<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Gestão do Blog</x-slot>

    <x-paneladmin::page title="Gestão do Blog" subtitle="Publique, edite e gerencie todo o conteúdo do seu blog.">
        <x-slot name="header">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blog.categories') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Categorias
                </a>
                <a href="{{ route('admin.blog.create') }}" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Novo Post
                </a>
            </div>
        </x-slot>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Total de Posts</span>
                <span class="text-3xl font-black text-slate-800 dark:text-white">{{ $posts->total() }}</span>
            </div>
            <div class="bg-indigo-600 p-6 rounded-xl text-white shadow-sm">
                <span class="text-[10px] font-black text-indigo-200 uppercase tracking-widest block mb-1">Publicados</span>
                <span class="text-3xl font-black text-white">{{ \Modules\Blog\Models\Post::where('status', 'published')->count() }}</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Comentários Pendentes</span>
                <span class="text-3xl font-black text-slate-800 dark:text-white">{{ \Modules\Blog\Models\Comment::where('is_approved', false)->count() }}</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Total de Acessos</span>
                <span class="text-3xl font-black text-slate-800 dark:text-white">{{ format_number(\Modules\Blog\Models\Post::sum('views'), 0) }}</span>
            </div>
        </div>

        <!-- Posts Table -->
        <x-paneladmin::card>
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Postagem</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Categoria</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Acessos</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                        @forelse($posts as $post)
                            <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        @if($post->featured_image)
                                            <div class="w-16 h-12 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden shrink-0">
                                                <img src="{{ asset($post->featured_image) }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-16 h-12 rounded-xl bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-slate-300 shrink-0">
                                                <x-icon name="image" style="duotone" class="text-xs" />
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
                                    @if($post->is_premium)
                                        <span class="ml-1 px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[9px] font-black uppercase">PRO</span>
                                    @endif
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
                                    {{ format_number($post->views, 0) }}
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('paneluser.blog.show', $post->slug) }}" target="_blank" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                            <x-icon name="eye" style="duotone" class="text-xs" />
                                        </a>
                                        <a href="{{ route('admin.blog.edit', $post) }}" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                            <x-icon name="pen" style="duotone" class="text-xs" />
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Apagar este post permanentemente?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors">
                                                <x-icon name="trash" style="duotone" class="text-xs" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <x-icon name="file-circle-plus" style="duotone" class="text-6xl text-slate-300 dark:text-slate-600 mb-6" />
                                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Ainda não há posts cadastrados.</p>
                                        <a href="{{ route('admin.blog.create') }}" class="mt-6 text-primary font-black text-sm hover:underline">Criar meu primeiro post</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
            </x-paneladmin::table-wrapper>

            @if($posts->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $posts->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
