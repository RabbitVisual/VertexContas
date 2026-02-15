<x-paneluser::layouts.master :title="$post->title">
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <div class="lg:col-span-8">
            <article class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 backdrop-blur overflow-hidden shadow-sm">
                @if($post->featured_image)
                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover">
                @endif
                <div class="px-6 sm:px-8 pb-8 pt-6">
                    <nav class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-4">
                        <a href="{{ route('paneluser.blog.index') }}" class="hover:underline">Blog</a>
                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span class="text-slate-400 dark:text-slate-500">{{ $post->category?->name ?? '—' }}</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-4">
                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                        <span>·</span>
                        <span>{{ $post->views }} visualizações</span>
                        @if($post->is_premium)
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 font-bold text-[10px] uppercase">PRO</span>
                        @endif
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-6">{{ $post->title }}</h1>

                    @if($isLocked)
                        <div class="relative">
                            <div class="blur-md select-none pointer-events-none overflow-hidden max-h-96 text-slate-600 dark:text-slate-400 prose prose-slate dark:prose-invert max-w-none">
                                {{ $post->content }}
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-xl py-12">
                                <div class="text-center p-8 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 max-w-md mx-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <x-icon name="crown" style="solid" class="w-8 h-8 text-white" />
                                    </div>
                                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Conteúdo Exclusivo para Assinantes Vertex PRO</h2>
                                    <p class="text-slate-600 dark:text-slate-400 mb-6">Este artigo contém estratégias avançadas reservadas para assinantes Vertex PRO.</p>
                                    <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center gap-2 w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-lg hover:shadow-xl">
                                        <x-icon name="crown" style="solid" class="w-5 h-5" />
                                        Desbloquear Acesso Agora
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="prose prose-slate dark:prose-invert max-w-none trix-content">
                            {!! $post->content !!}
                        </div>
                    @endif

                    @auth
                        <div class="mt-8 flex items-center gap-4 border-t border-slate-200 dark:border-slate-700 pt-6">
                            @include('paneluser::blog.components.like-button', ['post' => $post])
                            <x-paneluser::blog.components.save-button :post="$post" />
                        </div>
                    @endauth
                </div>
            </article>

            @auth
                @include('paneluser::blog.components.comment-section', ['post' => $post])
            @endauth
        </div>

        <aside class="lg:col-span-4 mt-8 lg:mt-0 space-y-8">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-icon name="newspaper" style="duotone" class="w-5 h-5 text-amber-500" />
                    Relacionados
                </h3>
                <div class="space-y-4">
                    @forelse($relatedPosts as $related)
                        <a href="{{ route('paneluser.blog.show', $related->slug) }}" class="flex items-start gap-3 group">
                            @if($related->featured_image)
                                <img src="{{ asset($related->featured_image) }}" alt="" class="w-16 h-16 object-cover rounded-xl shrink-0 group-hover:opacity-90 transition-opacity">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                                    <x-icon name="newspaper" style="duotone" class="w-6 h-6 text-slate-400" />
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors line-clamp-2">{{ $related->title }}</h4>
                                <span class="text-xs text-slate-500">{{ $related->created_at->format('d/m/Y') }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum artigo relacionado.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
</x-paneluser::layouts.master>
