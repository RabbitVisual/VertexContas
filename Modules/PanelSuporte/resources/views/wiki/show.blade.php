<x-panelsuporte::layouts.master>
    <div class="space-y-8 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-3 text-xs font-black uppercase tracking-widest text-slate-400">
            <a href="{{ route('support.wiki.index') }}" class="hover:text-primary transition-colors">Wiki</a>
            <x-icon name="chevron-right" class="text-[10px]" />
            <a href="{{ route('support.wiki.index', ['category' => $article->category->slug]) }}" class="hover:text-primary transition-colors">{{ $article->category->name }}</a>
            <x-icon name="chevron-right" class="text-[10px]" />
            <span class="text-slate-600 dark:text-gray-300">{{ Str::limit($article->title, 20) }}</span>
        </nav>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <article class="bg-white dark:bg-slate-900 rounded-[3.5rem] p-12 border border-gray-100 dark:border-gray-800 shadow-xl overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl"></div>

                    <header class="space-y-6 mb-10 pb-10 border-b border-gray-50 dark:border-gray-800">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-2xl text-[10px] font-black uppercase tracking-[0.1em]">
                            <x-icon name="{{ $article->category->icon ?? 'book-open' }}" />
                            {{ $article->category->name }}
                        </div>
                        <h1 class="text-4xl font-black text-slate-900 dark:text-white leading-[1.1] tracking-tight">{{ $article->title }}</h1>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 text-slate-500 font-medium text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <x-icon name="user" class="text-xs" />
                                    </div>
                                    <span>Escrito por <span class="font-black text-slate-800 dark:text-white">{{ $article->author->name }}</span></span>
                                </div>
                                <span class="text-slate-200">|</span>
                                <div class="flex items-center gap-2">
                                    <x-icon name="calendar" class="text-xs text-slate-400" />
                                    <span>{{ $article->updated_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-slate-800/50 rounded-2xl text-slate-400 text-xs font-bold">
                                <x-icon name="eye" />
                                <span>{{ $article->views }} visualizações</span>
                            </div>
                        </div>
                    </header>
                    <div class="prose prose-slate dark:prose-invert max-w-none
                        prose-headings:font-black prose-headings:tracking-tight prose-headings:text-slate-800 dark:prose-headings:text-white
                        prose-p:text-slate-600 dark:prose-p:text-slate-400 prose-p:font-medium prose-p:leading-relaxed
                        prose-strong:text-primary prose-strong:font-black
                        prose-li:text-slate-600 dark:prose-li:text-slate-400 prose-li:font-medium
                        prose-img:rounded-[2rem] prose-img:shadow-2xl
                        prose-code:bg-primary/5 prose-code:text-primary prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded-lg prose-code:font-black prose-code:before:content-none prose-code:after:content-none
                        prose-pre:bg-slate-900 prose-pre:rounded-[2rem] prose-pre:p-8 prose-pre:shadow-2xl prose-pre:border prose-pre:border-white/5">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                    <footer class="mt-16 pt-10 border-t border-gray-50 dark:border-gray-800 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex flex-col items-center md:items-start gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Este artigo foi útil?</span>
                            <div class="flex gap-2">
                                <button class="px-6 py-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-black rounded-xl hover:bg-emerald-100 transition-all">Sim, ajudou!</button>
                                <button class="px-6 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-black rounded-xl hover:bg-red-100 transition-all">Não muito</button>
                            </div>
                        </div>
                        <button onclick="window.print()" class="text-xs font-black text-slate-400 hover:text-primary transition-colors flex items-center gap-2 uppercase tracking-widest">
                            <x-icon name="print" /> Imprimir Instruções
                        </button>
                    </footer>
                </article>
            </div>
            <!-- Sidebar: Related & Actions -->
            <aside class="space-y-8">
                @if($related->isNotEmpty())
                    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-xl">
                        <h3 class="text-xs font-black text-slate-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-6">Artigos Relacionados</h3>
                        <div class="space-y-6">
                            @foreach($related as $rel)
                                <a href="{{ route('support.wiki.show', $rel->slug) }}" class="group block space-y-2">
                                    <h4 class="text-sm font-black text-slate-800 dark:text-white group-hover:text-primary transition-colors leading-snug">{{ $rel->title }}</h4>
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase">
                                        <x-icon name="eye" class="text-[8px]" />
                                        <span>{{ $rel->views }} visualizações</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 space-y-6">
                        <h4 class="text-xl font-black leading-tight tracking-tight">Precisa de mais informações?</h4>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">Você pode escalar esta dúvida para os desenvolvedores ou consultar os logs do sistema.</p>
                        <div class="space-y-3">
                            <a href="#" class="flex items-center justify-between w-full px-6 py-4 bg-white/5 hover:bg-white/10 text-white rounded-[1.5rem] transition-all group/link">
                                <span class="font-black text-xs uppercase tracking-widest">Consultar Devs</span>
                                <x-icon name="arrow-right" class="group-hover/link:translate-x-2 transition-transform" />
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-panelsuporte::layouts.master>
