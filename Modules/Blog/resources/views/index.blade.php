<x-blog::layouts.master title="Vertex Insights - Blog Financeiro">
    <div class="relative min-h-screen bg-gray-50 dark:bg-slate-900 overflow-hidden" x-data="{ mobileFiltersOpen: false }">

        <!-- Decoration Background -->
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-indigo-600 to-transparent opacity-10 dark:opacity-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">

            <!-- Header & Search -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-4">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                            <x-icon name="newspaper" class="w-6 h-6" />
                        </div>
                        Vertex Insights
                    </h1>
                    <p class="mt-2 text-lg text-slate-500 dark:text-slate-400 font-medium max-w-xl">
                        Estratégias financeiras de elite, análises de mercado e guias exclusivos para multiplicar seu patrimônio.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="mobileFiltersOpen = true" class="md:hidden p-2 rounded-lg bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300">
                        <x-icon name="filter" class="w-5 h-5" />
                    </button>
                    <!-- Search Input (Visual Only for now) -->
                    <div class="relative hidden md:block group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-icon name="magnifying-glass" class="text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                        </div>
                        <input type="text" class="pl-10 pr-4 py-2.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-64 shadow-sm transition-all text-sm" placeholder="Buscar artigos...">
                    </div>
                </div>
            </div>

            <!-- Categories (Desktop) -->
            <div class="hidden md:flex items-center gap-3 mb-10 overflow-x-auto pb-2 scrollbar-hide">
                <a href="{{ route('blog.index') }}" class="px-5 py-2 rounded-full bg-indigo-600 text-white font-bold text-sm shadow-md shadow-indigo-500/20 whitespace-nowrap">
                    Todos
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="px-5 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-bold text-sm hover:border-indigo-500 hover:text-indigo-500 transition-all shadow-sm whitespace-nowrap flex items-center gap-2">
                        @if($category->icon) <i class="{{ $category->icon }}"></i> @endif
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Hero Section (First Post) -->
            @if($posts->onFirstPage() && $posts->count() > 0)
                @php $heroPost = $posts->first(); @endphp
                <div class="mb-16 relative rounded-3xl overflow-hidden shadow-2xl group cursor-pointer" onclick="window.location='{{ route('blog.show', $heroPost->slug) }}'">
                    <div class="absolute inset-0">
                         @if($heroPost->featured_image)
                            <img src="{{ asset($heroPost->featured_image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
                    </div>

                    <div class="relative z-10 p-8 md:p-12 flex flex-col justify-end h-[500px] md:h-[600px] max-w-4xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-lg bg-indigo-500/80 backdrop-blur-md text-white text-xs font-black uppercase tracking-wider shadow-lg">
                                {{ $heroPost->category->name }}
                            </span>
                            <span class="flex items-center text-slate-300 text-xs font-bold gap-1 bg-black/30 px-2 py-1 rounded-lg backdrop-blur-md">
                                <x-icon name="clock" class="w-3 h-3" /> {{ $heroPost->read_time }}
                            </span>
                            @if($heroPost->is_premium)
                                <span class="px-3 py-1 rounded-lg bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-black uppercase tracking-wider shadow-lg flex items-center gap-1">
                                    <x-icon name="crown" class="w-3 h-3" /> PRO
                                </span>
                            @endif
                        </div>

                        <h2 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight group-hover:text-indigo-200 transition-colors">
                            {{ $heroPost->title }}
                        </h2>

                        <p class="text-lg text-slate-300 line-clamp-2 md:line-clamp-3 mb-8 max-w-2xl font-medium">
                            {{ Str::limit(strip_tags($heroPost->content), 200) }}
                        </p>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-white/20">
                                    {{ substr($heroPost->author->name, 0, 1) }}
                                </div>
                                <div class="text-sm">
                                    <p class="font-bold text-white">{{ $heroPost->author->name }}</p>
                                    <p class="text-slate-400">{{ $heroPost->created_at->format('d M, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grid Posts -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    @if($loop->first && $posts->onFirstPage()) @continue @endif

                    <article class="glass-card rounded-2xl overflow-hidden hover:transform hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 group flex flex-col h-full">
                        <a href="{{ route('blog.show', $post->slug) }}" class="relative h-56 overflow-hidden block">
                             @if($post->featured_image)
                                <img src="{{ asset($post->featured_image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <x-icon name="newspaper" class="w-12 h-12 text-white/50" />
                                </div>
                            @endif

                            @if($post->is_premium)
                                <div class="absolute top-4 right-4 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg flex items-center gap-1 z-10">
                                    <x-icon name="crown" class="w-3 h-3" /> PRO
                                </div>
                            @endif

                            <div class="absolute bottom-4 left-4 z-10">
                                <span class="px-2 py-1 rounded bg-black/60 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider border border-white/10">
                                    {{ $post->category->name }}
                                </span>
                            </div>
                        </a>

                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-3 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                <span class="flex items-center gap-1">
                                    <x-icon name="calendar" class="w-3 h-3" /> {{ $post->created_at->format('d M') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <x-icon name="clock" class="w-3 h-3" /> {{ $post->read_time }}
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 line-clamp-2 leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p class="text-slate-600 dark:text-slate-400 text-sm mb-5 line-clamp-3 flex-1">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-[10px]">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 truncate max-w-[100px]">{{ $post->author->name }}</span>
                                </div>

                                <a href="{{ route('blog.show', $post->slug) }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-wider hover:underline flex items-center gap-1">
                                    Ler <x-icon name="arrow-right" class="w-3 h-3" />
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Mobile Filter Bottom Sheet -->
        <div x-show="mobileFiltersOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             class="fixed inset-x-0 bottom-0 z-50 bg-white dark:bg-slate-800 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.2)] p-6 md:hidden"
             style="display: none;">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-slate-900 dark:text-white">Filtrar por Categoria</h3>
                <button @click="mobileFiltersOpen = false" class="p-2 bg-slate-100 dark:bg-slate-700 rounded-full text-slate-500">
                    <x-icon name="xmark" />
                </button>
            </div>

            <div class="space-y-2 max-h-[60vh] overflow-y-auto">
                <a href="{{ route('blog.index') }}" class="flex items-center justify-between p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-500/30">
                    <span class="font-bold text-indigo-700 dark:text-indigo-300">Todos os Artigos</span>
                    <x-icon name="check" class="text-indigo-600" />
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 border border-transparent hover:border-slate-200 transition-colors">
                        @if($category->icon)
                            <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 shadow-sm flex items-center justify-center text-slate-500">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                        @endif
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Backdrop -->
        <div x-show="mobileFiltersOpen" @click="mobileFiltersOpen = false"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-40 md:hidden"
             style="display: none;"></div>
    </div>
</x-blog::layouts.master>
