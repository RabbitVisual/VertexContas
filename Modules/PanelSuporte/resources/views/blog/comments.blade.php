<x-panelsuporte::layouts.master title="Moderação de Comentários - Suporte">
    <div class="space-y-8 animate-in fade-in duration-500">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-primary/10 rounded-2xl text-primary">
                <x-icon name="comments" style="duotone" class="text-2xl" />
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Moderação de Comentários</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Aprove ou rejeite comentários pendentes do blog.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            @if($comments->isEmpty())
                <div class="p-16 text-center text-gray-500 dark:text-gray-400">
                    <div class="w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4 border border-gray-100 dark:border-gray-800">
                        <x-icon name="check-double" style="duotone" class="w-8 h-8 text-primary/50" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Nenhum comentário pendente</h3>
                    <p class="text-sm">Todos os comentários foram moderados.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($comments as $comment)
                    <li class="p-6 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                                    <x-icon name="user" style="duotone" class="w-6 h-6" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 space-y-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $comment->user->name ?? 'Usuário Desconhecido' }}</h3>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Em: <a href="{{ route('paneluser.blog.show', $comment->post->slug) }}" class="text-primary hover:text-primary-dark font-bold" target="_blank">{{ $comment->post->title }}</a>
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $comment->content }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-3 pl-16">
                            <form action="{{ route('suporte.blog.comments.reject', $comment) }}" method="POST" onsubmit="return confirm('Rejeitar e excluir este comentário?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl text-red-700 bg-red-50 dark:bg-red-500/10 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors">
                                    <x-icon name="xmark" style="duotone" class="w-4 h-4" />
                                    Rejeitar
                                </button>
                            </form>
                            <form action="{{ route('suporte.blog.comments.approve', $comment) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl text-emerald-700 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors">
                                    <x-icon name="check" style="duotone" class="w-4 h-4" />
                                    Aprovar
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-slate-800/20">
                    {{ $comments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-panelsuporte::layouts.master>
