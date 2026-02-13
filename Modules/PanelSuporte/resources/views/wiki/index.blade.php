<x-panelsuporte::layouts.master>
    <div x-data="{ suggestionModal: false }" class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header & Search -->
        <div class="relative bg-gradient-to-br from-primary to-primary-dark rounded-[3rem] p-10 overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 text-center space-y-6">
                <h1 class="text-4xl font-black text-white tracking-tight">Wiki Técnica & Suporte</h1>
                <p class="text-white/80 font-medium max-w-2xl mx-auto">Encontre soluções rápidas, tutoriais e orientações técnicas para ajudar nossos usuários com excelência.</p>
                <form action="{{ route('support.wiki.index') }}" method="GET" class="max-w-2xl mx-auto relative group">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="O que você está procurando hoje?"
                        class="w-full pl-14 pr-6 py-5 bg-white rounded-[2rem] border-none shadow-xl focus:ring-4 focus:ring-primary/20 text-slate-800 font-bold placeholder-slate-400 transition-all">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                        <x-icon name="magnifying-glass" class="text-xl" />
                    </div>
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-primary text-white px-6 py-3 rounded-[1.5rem] font-black text-sm hover:bg-primary-dark transition-all shadow-lg active:scale-95">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar: Categories -->
            <aside class="space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-6 ml-2">Categorias</h3>
                    <nav class="space-y-2">
                        <a href="{{ route('support.wiki.index') }}"
                           class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all {{ !request('category') ? 'bg-primary/10 text-primary font-black shadow-sm' : 'text-slate-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 font-bold' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="grid-2" class="text-lg" />
                                <span>Todas as Categorias</span>
                            </div>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('support.wiki.index', ['category' => $category->slug]) }}"
                               class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all {{ request('category') == $category->slug ? 'bg-primary/10 text-primary font-black shadow-sm' : 'text-slate-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 font-bold' }}">
                                <div class="flex items-center gap-3">
                                    <x-icon name="{{ $category->icon ?? 'folder' }}" class="text-lg" />
                                    <span>{{ $category->name }}</span>
                                </div>
                                <span class="text-[10px] bg-gray-100 dark:bg-slate-800 px-2 py-0.5 rounded-lg opacity-60">{{ $category->articles_count }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-primary rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md">
                            <x-icon name="lightbulb" class="text-2xl" />
                        </div>
                        <h4 class="text-lg font-black leading-tight">Sugira um Artigo</h4>
                        <p class="text-white/70 text-sm font-medium">Sentiu falta de algo? Sugira novos tópicos para o time administrativo.</p>
                        <button @click="suggestionModal = true" class="w-full py-3 bg-white text-primary font-black rounded-2xl shadow-lg hover:scale-105 transition-all active:scale-95 text-sm uppercase tracking-wider">Enviar Sugestão</button>
                    </div>
                </div>
            </aside>

            <!-- Main Content: Articles -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-[1.5rem] p-6 text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center gap-4 animate-in fade-in slide-in-from-top duration-500">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center shrink-0">
                            <x-icon name="check" />
                        </div>
                        {{ session('success') }}
                    </div>
                @endif

                @if($articles->isEmpty())
                    <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-20 text-center border border-dashed border-gray-200 dark:border-gray-800">
                        <div class="w-24 h-24 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                            <x-icon name="face-frown" class="text-4xl text-gray-300" />
                        </div>
                        <h2 class="text-xl font-black text-slate-800 dark:text-white mb-2">Nenhum artigo encontrado</h2>
                        <p class="text-slate-500 font-medium">Tente ajustar sua busca ou navegar pelas categorias laterais.</p>
                        <a href="{{ route('support.wiki.index') }}" class="inline-block mt-8 text-primary font-black uppercase tracking-widest text-xs hover:underline">Limpar filtros</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($articles as $article)
                            <a href="{{ route('support.wiki.show', $article->slug) }}" class="group bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col items-start text-left">
                                <div class="px-3 py-1 bg-primary/5 text-primary text-[10px] font-black rounded-lg uppercase tracking-wider mb-4">
                                    {{ $article->category->name }}
                                </div>
                                <h3 class="text-xl font-black text-slate-800 dark:text-white mb-3 leading-tight group-hover:text-primary transition-colors">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 font-medium mb-6">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>
                                <div class="mt-auto flex items-center justify-between w-full pt-6 border-t border-gray-50 dark:border-gray-800 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <x-icon name="eye" class="text-[10px]" />
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $article->views }} Views</span>
                                    </div>
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 dark:bg-slate-800 flex items-center justify-center text-slate-300 group-hover:bg-primary group-hover:text-white transition-all">
                                        <x-icon name="arrow-right" />
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Suggestion Modal -->
        <div x-show="suggestionModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>

            <div @click.away="suggestionModal = false"
                 x-show="suggestionModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800">

                <div class="p-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                                <x-icon name="lightbulb" class="text-2xl" />
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white leading-tight">Sugira um Tópico</h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Colabore com a base de conhecimento</p>
                            </div>
                        </div>
                        <button @click="suggestionModal = false" class="text-slate-300 hover:text-red-500 transition-colors">
                            <x-icon name="xmark" class="text-2xl" />
                        </button>
                    </div>

                    <form action="{{ route('support.wiki.suggestion.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Título da Sugestão</label>
                            <input type="text" name="title" required placeholder="Ex: Procedimento para Estorno PIX"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Descrição / Explicação</label>
                            <textarea name="description" rows="5" required placeholder="Descreva brevemente o que este artigo deveria cobrir..."
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all resize-none"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-5 bg-primary text-white font-black rounded-[1.5rem] shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all hover:scale-[1.02] active:scale-95 text-sm uppercase tracking-[0.15em]">
                                Enviar para Revisão
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-panelsuporte::layouts.master>
