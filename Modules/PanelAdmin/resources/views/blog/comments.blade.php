<x-paneladmin::layouts.master>
    <div class="space-y-8 animate-in fade-in duration-500">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.blog.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                    <x-icon name="arrow-left" />
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Moderação de Comentários</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Gerencie as interações dos usuários em suas postagens.</p>
                </div>
            </div>
        </div>

        <!-- Comments Table -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Usuário / Artigo</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Comentário</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($comments as $comment)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-all">
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-xs">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $comment->user->name }}</span>
                                            <a href="{{ route('paneluser.blog.show', $comment->post->slug) }}" target="_blank" class="text-[9px] text-primary font-black uppercase tracking-tight hover:underline">
                                                No post: {{ $comment->post->title }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 max-w-md">
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic">
                                        "{{ $comment->message }}"
                                    </p>
                                    <span class="text-[9px] text-slate-400 font-bold mt-2 block">{{ $comment->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    @if($comment->is_approved)
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 text-[10px] font-black rounded-lg uppercase tracking-wider">
                                            Aprovado
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 text-[10px] font-black rounded-lg uppercase tracking-wider">
                                            Pendente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if(!$comment->is_approved)
                                            <form action="{{ route('admin.blog.comments.approve', $comment) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Aprovar Comentário">
                                                    <x-icon name="check" class="text-xs" />
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.blog.comments.reject', $comment) }}" method="POST" onsubmit="return confirm('Excluir este comentário permanentemente?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Rejeitar/Excluir">
                                                <x-icon name="xmark" class="text-xs" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <x-icon name="comments" class="text-6xl text-slate-200 mb-6" />
                                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhum comentário para moderar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($comments->hasPages())
                <div class="p-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $comments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-paneladmin::layouts.master>
