<x-panelsuporte::layouts.master title="Gerenciar Blog - Suporte">
    <div class="space-y-8 animate-in fade-in duration-500">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-primary/10 rounded-2xl text-primary">
                    <x-icon name="newspaper" style="duotone" class="text-2xl" />
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Gerenciar Blog</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Crie e edite posts do blog.</p>
                </div>
            </div>
            <a href="{{ route('suporte.blog.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-primary/20">
                <x-icon name="plus" style="duotone" class="w-4 h-4" />
                Novo Post
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full text-left border-collapse">
            <thead class="sticky top-0 z-10 bg-gray-50/95 dark:bg-slate-800/95 backdrop-blur border-b border-gray-100 dark:border-gray-800 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Título</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Autor</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Categoria</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Premium</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($posts as $post)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors even:bg-slate-50/30 dark:even:bg-slate-800/20">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($post->featured_image)
                                <img class="h-10 w-10 rounded-full mr-3" src="{{ asset($post->featured_image) }}" alt="">
                            @endif
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->title }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $post->author->name ?? 'Desconhecido' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $post->category->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : ($post->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        @if($post->is_premium)
                            <x-icon name="crown" style="duotone" class="w-5 h-5 text-amber-500" />
                        @else
                            <span class="text-xs text-gray-400">Livre</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('suporte.blog.edit', $post) }}" class="text-primary hover:text-primary-dark font-bold mr-3">Editar</a>
                        <form action="{{ route('suporte.blog.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @if($posts->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-slate-800/20">
            {{ $posts->links() }}
        </div>
        @endif
        </div>
    </div>
</x-panelsuporte::layouts.master>
