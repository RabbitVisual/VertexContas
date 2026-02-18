<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Gestão do Blog</x-slot>

    <x-paneladmin::page title="Gestão do Blog" subtitle="Publique, edite e gerencie todo o conteúdo do blog.">
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
                <a href="{{ route('admin.blog.categories') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="folder" style="duotone" class="w-4 h-4" /> Categorias
                </a>
                <a href="{{ route('admin.blog.comments') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="comments" style="duotone" class="w-4 h-4" /> Comentários
                </a>
                <a href="{{ route('admin.blog.create') }}" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Novo Post
                </a>
            </div>
        </x-slot>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <x-icon name="file-lines" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total de Posts</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $posts->total() }}</p>
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
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ \Modules\Blog\Models\Post::where('status', 'published')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <x-icon name="comments" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Comentários Pendentes</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ \Modules\Blog\Models\Comment::where('is_approved', false)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                        <x-icon name="chart-simple" style="duotone" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total de Acessos</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ format_number(\Modules\Blog\Models\Post::sum('views'), 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela --}}
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
                                    <div class="w-16 h-12 rounded-xl border border-slate-100 dark:border-slate-700 overflow-hidden shrink-0">
                                        <img src="{{ asset($post->featured_image) }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-16 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 shrink-0">
                                        <x-icon name="image" style="duotone" class="w-6 h-6" />
                                    </div>
                                @endif
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-slate-900 dark:text-white text-sm line-clamp-1">{{ $post->title }}</span>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Postado em {{ $post->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">
                                <x-icon name="folder" style="duotone" class="w-3.5 h-3.5" />
                                {{ $post->category->name }}
                            </span>
                            @if($post->is_premium)
                                <span class="ml-1 inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[9px] font-bold uppercase">
                                    <x-icon name="crown" style="duotone" class="w-3 h-3" /> PRO
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusTheme = [
                                    'published' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'draft' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'pending_review' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                ];
                                $statusLabels = ['published' => 'Publicado', 'draft' => 'Rascunho', 'pending_review' => 'Pendente'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusTheme[$post->status] ?? 'bg-slate-100 text-slate-700' }}">
                                <x-icon name="circle-dot" style="duotone" class="w-3.5 h-3.5" />
                                {{ $statusLabels[$post->status] ?? $post->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center font-bold text-slate-600 dark:text-slate-400 text-sm tabular-nums">
                            {{ format_number($post->views ?? 0, 0) }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.blog.show', $post) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-[#11C76F]/10 hover:text-[#11C76F] transition-colors" title="Ver no painel">
                                    <x-icon name="eye" style="duotone" class="w-4 h-4" />
                                </a>
                                <a href="{{ route('paneluser.blog.show', $post->slug) }}" target="_blank" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-[#11C76F] transition-colors" title="Abrir no site">
                                    <x-icon name="arrow-up-right-from-square" style="duotone" class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.blog.edit', $post) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-[#11C76F] transition-colors" title="Editar">
                                    <x-icon name="pen" style="duotone" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Apagar este post permanentemente?');" class="inline">
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
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum post cadastrado.</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">Crie o primeiro artigo do blog.</p>
                                <a href="{{ route('admin.blog.create') }}" class="mt-2 text-[#11C76F] font-bold text-sm hover:underline flex items-center gap-2">
                                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Criar primeiro post
                                </a>
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
