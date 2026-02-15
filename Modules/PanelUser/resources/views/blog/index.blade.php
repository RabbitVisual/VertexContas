<x-paneluser::layouts.master :title="'Blog'">
<div class="max-w-6xl mx-auto space-y-8 px-4 pb-12">
    {{-- Hero (CBAV-style) --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white/80 dark:bg-gray-900/80 backdrop-blur border border-slate-200 dark:border-slate-700 p-8 sm:p-12 shadow-sm">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-500/10 dark:bg-amber-500/20 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="relative z-10">
            <nav class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                <a href="{{ route('paneluser.index') }}" class="hover:underline">Painel</a>
                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600" aria-hidden="true"></span>
                <span class="text-slate-400 dark:text-slate-500">Blog</span>
            </nav>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.1] mb-3">
                Blog <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500 dark:from-amber-400 dark:to-orange-400">Vertex</span>
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-lg max-w-xl">Dicas e conteúdos sobre controle financeiro e produtividade.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('paneluser.blog.index') }}" class="flex flex-wrap items-stretch gap-4">
        <div class="flex-1 min-w-[200px] flex">
            <label for="q" class="sr-only">Buscar</label>
            <div class="relative flex-1 flex items-center">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-slate-400 dark:text-slate-500 pointer-events-none">
                    <x-icon name="magnifying-glass" style="duotone" class="w-5 h-5 block" />
                </span>
                <input type="search" name="q" id="q" value="{{ request('q') }}"
                    placeholder="Buscar artigos..."
                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
            </div>
        </div>
        <div class="w-full sm:w-auto flex sm:flex-none">
            <label for="category_id" class="sr-only">Categoria</label>
            <select name="category_id" id="category_id"
                class="w-full sm:w-48 h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                <option value="">Todas as categorias</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="h-11 inline-flex items-center justify-center gap-2 px-5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition-colors shrink-0">
            <x-icon name="magnifying-glass" style="solid" class="w-4 h-4" />
            Filtrar
        </button>
    </form>

    @if($posts->isEmpty())
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-500">
                <x-icon name="newspaper" style="duotone" class="w-8 h-8" />
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Nenhum artigo encontrado</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">Tente outro termo de busca ou categoria.</p>
            <a href="{{ route('paneluser.blog.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition-colors">
                <x-icon name="arrow-rotate-left" style="solid" class="w-4 h-4" />
                Limpar filtros
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <a href="{{ route('paneluser.blog.show', $post->slug) }}" class="group block rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:border-amber-500/30 dark:hover:border-amber-500/20">
                    @if($post->featured_image)
                        <div class="aspect-video overflow-hidden bg-slate-100 dark:bg-slate-800">
                            <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="aspect-video bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <x-icon name="newspaper" style="duotone" class="w-12 h-12 text-slate-400 dark:text-slate-500" />
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-2">
                            <span>{{ $post->created_at->format('d/m/Y') }}</span>
                            <span>·</span>
                            <span>{{ $post->category?->name ?? '—' }}</span>
                            @if($post->is_premium)
                                <span class="ml-auto px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 font-bold text-[10px] uppercase">PRO</span>
                            @endif
                        </div>
                        <h2 class="font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $post->title }}</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $posts->links() }}</div>
    @endif
</div>
</x-paneluser::layouts.master>
