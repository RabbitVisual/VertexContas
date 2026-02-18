<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Comentários do Blog</x-slot>

    <x-paneladmin::page title="Moderação de Comentários" subtitle="Aprove ou rejeite os comentários dos usuários nas postagens.">
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
            <a href="{{ route('admin.blog.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar ao Blog
            </a>
        </x-slot>

        <x-paneladmin::card>
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Usuário / Post</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Comentário</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($comments as $comment)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-600 dark:text-slate-400 text-sm shrink-0">
                                    <x-icon name="user" style="duotone" class="w-5 h-5" />
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-900 dark:text-white text-sm block truncate">{{ $comment->user?->name ?? '—' }}</span>
                                    <a href="{{ route('paneluser.blog.show', $comment->post->slug) }}" target="_blank" class="text-xs text-[#11C76F] font-medium hover:underline truncate block">
                                        {{ Str::limit($comment->post->title, 40) }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 max-w-md">
                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">"{{ $comment->message }}"</p>
                            <span class="text-[10px] text-slate-400 mt-1 block">{{ $comment->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($comment->is_approved)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    <x-icon name="circle-check" style="duotone" class="w-3.5 h-3.5" /> Aprovado
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    <x-icon name="clock" style="duotone" class="w-3.5 h-3.5" /> Pendente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$comment->is_approved)
                                    <form action="{{ route('admin.blog.comments.approve', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors" title="Aprovar">
                                            <x-icon name="check" style="duotone" class="w-4 h-4" />
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.blog.comments.reject', $comment) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este comentário permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors" title="Rejeitar / Excluir">
                                        <x-icon name="trash" style="duotone" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <x-icon name="comments" style="duotone" class="w-8 h-8 text-slate-400" />
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum comentário para moderar.</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">Os comentários aparecerão aqui quando os usuários interagirem com os posts.</p>
                                <a href="{{ route('admin.blog.index') }}" class="text-[#11C76F] font-bold text-sm hover:underline flex items-center gap-2">
                                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar ao Blog
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>

            @if($comments->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $comments->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
