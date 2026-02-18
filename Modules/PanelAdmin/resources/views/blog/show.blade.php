<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Post: {{ Str::limit($post->title, 30) }}</x-slot>

    <x-paneladmin::page title="Visualizar Post" :subtitle="$post->title">
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

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Conteúdo principal --}}
            <div class="flex-1 min-w-0 space-y-6">
                <x-paneladmin::card>
                    <div class="p-6 space-y-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $post->title }}</h1>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                        <x-icon name="folder" style="duotone" class="w-3.5 h-3.5" />
                                        {{ $post->category->name }}
                                    </span>
                                    @php
                                        $statusTheme = [
                                            'published' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'draft' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            'pending_review' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        ];
                                        $statusLabels = ['published' => 'Publicado', 'draft' => 'Rascunho', 'pending_review' => 'Pendente'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $statusTheme[$post->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        <x-icon name="circle-dot" style="duotone" class="w-3.5 h-3.5" />
                                        {{ $statusLabels[$post->status] ?? $post->status }}
                                    </span>
                                    @if($post->is_premium)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">
                                            <x-icon name="crown" style="duotone" class="w-3 h-3" /> PRO
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#11C76F] text-white text-sm font-bold hover:bg-[#0EA85A] transition-colors">
                                    <x-icon name="pen" style="duotone" class="w-4 h-4" /> Editar
                                </a>
                                <a href="{{ route('paneluser.blog.show', $post->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <x-icon name="arrow-up-right-from-square" style="duotone" class="w-4 h-4" /> Ver público
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Apagar este post permanentemente?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 rounded-xl border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <x-icon name="trash" style="duotone" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                            <span class="inline-flex items-center gap-1.5">
                                <x-icon name="user" style="duotone" class="w-4 h-4" />
                                {{ $post->author?->name ?? '—' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <x-icon name="calendar-days" style="duotone" class="w-4 h-4" />
                                Publicado em {{ $post->created_at->format('d/m/Y H:i') }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <x-icon name="chart-simple" style="duotone" class="w-4 h-4" />
                                {{ format_number($post->views ?? 0, 0) }} acessos
                            </span>
                            @if($post->updated_at->ne($post->created_at))
                                <span class="inline-flex items-center gap-1.5">
                                    <x-icon name="clock-rotate-left" style="duotone" class="w-4 h-4" />
                                    Atualizado em {{ $post->updated_at->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </div>

                        @if($post->featured_image)
                            <div class="rounded-xl overflow-hidden border border-slate-100 dark:border-slate-700">
                                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full aspect-video object-cover">
                            </div>
                        @endif

                        <div class="prose prose-slate dark:prose-invert max-w-none prose-headings:font-black prose-p:text-slate-600 dark:prose-p:text-slate-400">
                            {!! $post->content !!}
                        </div>
                    </div>
                </x-paneladmin::card>

                @if($post->meta_description || $post->og_image)
                    <x-paneladmin::card title="SEO" subtitle="Meta e redes sociais.">
                        <div class="p-6 space-y-4">
                            @if($post->meta_description)
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Meta descrição</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">{{ $post->meta_description }}</p>
                                </div>
                            @endif
                            @if($post->og_image)
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Imagem OG</p>
                                    <img src="{{ asset($post->og_image) }}" alt="OG" class="rounded-lg border border-slate-200 dark:border-slate-700 max-h-24 object-cover">
                                </div>
                            @endif
                        </div>
                    </x-paneladmin::card>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="w-full lg:w-80 shrink-0 space-y-6">
                <x-paneladmin::card title="Resumo" subtitle="Estatísticas do post.">
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Acessos</span>
                            <span class="font-bold text-slate-900 dark:text-white tabular-nums">{{ format_number($post->views ?? 0, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Comentários</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $post->comments->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Aprovados</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $post->comments->where('is_approved', true)->count() }}</span>
                        </div>
                    </div>
                </x-paneladmin::card>

                <x-paneladmin::card title="Comentários" subtitle="Últimos no post.">
                    <div class="p-6">
                        @if($post->comments->count() > 0)
                            <ul class="space-y-3 max-h-64 overflow-y-auto">
                                @foreach($post->comments->take(5) as $comment)
                                    <li class="flex gap-3 text-sm">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-600 dark:text-slate-400 shrink-0">
                                            {{ substr($comment->user?->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-slate-900 dark:text-white truncate">{{ $comment->user?->name }}</p>
                                            <p class="text-slate-500 dark:text-slate-400 text-xs line-clamp-2">{{ $comment->message }}</p>
                                            <span class="text-[10px] text-slate-400">{{ $comment->created_at->format('d/m H:i') }} · {{ $comment->is_approved ? 'Aprovado' : 'Pendente' }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('admin.blog.comments') }}" class="mt-4 block text-center text-sm font-bold text-[#11C76F] hover:underline">
                                Ver moderação de comentários
                            </a>
                        @else
                            <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">Nenhum comentário ainda.</p>
                            <a href="{{ route('admin.blog.comments') }}" class="block text-center text-sm font-bold text-[#11C76F] hover:underline">Ir para moderação</a>
                        @endif
                    </div>
                </x-paneladmin::card>
            </div>
        </div>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
