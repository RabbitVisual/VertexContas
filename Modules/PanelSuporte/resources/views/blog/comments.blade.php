<x-panelsuporte::layouts.master>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Moderação de Comentários</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden">
        @if($comments->isEmpty())
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                Nenhum comentário pendente de aprovação.
            </div>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-slate-700">
                @foreach($comments as $comment)
                <li class="p-6">
                    <div class="flex space-x-3">
                        <div class="flex-shrink-0">
                            <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                        </div>
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $comment->user->name ?? 'Usuário Desconhecido' }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Em: <a href="{{ route('blog.show', $comment->post->slug) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank">{{ $comment->post->title }}</a></p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">{{ $comment->content }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <form action="{{ route('blog.comments.reject', $comment->id) }}" method="POST" onsubmit="return confirm('Rejeitar e excluir este comentário?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Rejeitar
                            </button>
                        </form>
                        <form action="{{ route('blog.comments.approve', $comment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Aprovar
                            </button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</x-panelsuporte::layouts.master>
