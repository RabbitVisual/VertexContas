<x-blog::layouts.master :description="$post->meta_description ?? Str::limit(strip_tags($post->content), 160)" :ogImage="$post->og_image ? asset($post->og_image) : null">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <article class="prose dark:prose-invert lg:prose-xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden">
                    @if($post->featured_image)
                        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover mb-6 rounded-t-2xl">
                    @endif
                    <div class="px-8 pb-8 pt-6">
                        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <span>{{ $post->created_at->format('d M, Y') }}</span>
                            <span>•</span>
                            <span>{{ $post->category->name }}</span>
                            <span>•</span>
                            <span>{{ $post->views }} visualizações</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

                        <!-- Content Body -->
                        @if($post->is_premium && !auth()->user()?->hasRole(['pro_user', 'admin', 'support']))
                            <div class="relative">
                                <div class="blur-md select-none pointer-events-none opacity-50 h-96 overflow-hidden">
                                    {!! Str::limit(strip_tags($post->content), 300) !!}
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-lg">
                                    <div class="text-center p-8 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-indigo-100 dark:border-indigo-900 max-w-md mx-4">
                                        <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                            <x-icon name="crown" class="w-8 h-8 text-white" />
                                        </div>
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Conteúdo Exclusivo PRO</h2>
                                        <p class="text-gray-600 dark:text-gray-300 mb-6">Este artigo contém estratégias avançadas reservadas para assinantes Vertex PRO.</p>
                                        <a href="{{ route('paneluser.subscription.index') }}" class="inline-block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            Desbloquear Acesso Agora
                                        </a>
                                        <p class="mt-4 text-xs text-gray-400">Já é assinante? <a href="{{ route('login') }}" class="text-indigo-500 hover:underline">Faça login</a></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="prose dark:prose-invert max-w-none trix-content">
                                {!! $post->content !!}
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="mt-8 flex items-center justify-between border-t border-gray-100 dark:border-slate-700 pt-6">
                            <div class="flex space-x-4">
                                @auth
                                <button onclick="toggleLike({{ $post->id }})" class="flex items-center text-gray-500 hover:text-red-500 transition-colors group {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : '' }}" id="like-btn-{{ $post->id }}">
                                    <x-icon name="heart" class="w-6 h-6 mr-1 group-hover:animate-pulse {{ $post->isLikedBy(auth()->user()) ? 'fill-current' : '' }}" />
                                    <span id="like-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                                </button>
                                <button onclick="toggleSave({{ $post->id }})" class="flex items-center text-gray-500 hover:text-indigo-500 transition-colors {{ $post->isSavedBy(auth()->user()) ? 'text-indigo-500' : '' }}" id="save-btn-{{ $post->id }}">
                                    <x-icon name="bookmark" class="w-6 h-6 mr-1 {{ $post->isSavedBy(auth()->user()) ? 'fill-current' : '' }}" />
                                    <span id="save-text-{{ $post->id }}">{{ $post->isSavedBy(auth()->user()) ? 'Salvo' : 'Salvar' }}</span>
                                </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Comments Section -->
                <div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <x-icon name="comments" class="w-6 h-6 mr-2 text-indigo-500" />
                        Comentários
                    </h3>

                    <!-- Comment Form -->
                    @auth
                        <form id="comment-form" onsubmit="submitComment(event, {{ $post->id }})" class="mb-8">
                            <div class="mb-4">
                                <textarea id="comment-content" rows="3" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" placeholder="Deixe seu comentário..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                                    Publicar
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg text-center text-gray-600 dark:text-gray-300 mb-8">
                            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Faça login</a> para comentar.
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="space-y-6" id="comments-list">
                        @foreach($post->approvedComments as $comment)
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="bg-gray-50 dark:bg-slate-700/50 rounded-2xl px-4 py-3">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $comment->user->name }}</h4>
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 mt-8 lg:mt-0 space-y-8">
                <!-- Related Posts -->
                 <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Relacionados</h3>
                    <div class="space-y-4">
                        @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug) }}" class="flex items-start group">
                                @if($related->featured_image)
                                    <img src="{{ asset($related->featured_image) }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0 mr-3 group-hover:opacity-80 transition-opacity">
                                @else
                                    <div class="w-16 h-16 bg-indigo-100 rounded-lg flex-shrink-0 mr-3"></div>
                                @endif
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $related->title }}</h4>
                                    <span class="text-xs text-gray-500">{{ $related->created_at->format('d M') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                 </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleLike(postId) {
            fetch(`{{ url('blog/like') }}/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const btn = document.getElementById(`like-btn-${postId}`);
                    const count = document.getElementById(`like-count-${postId}`);

                    count.innerText = data.count;
                    if(data.liked) {
                        btn.classList.add('text-red-500');
                    } else {
                        btn.classList.remove('text-red-500');
                    }
                }
            });
        }

        function toggleSave(postId) {
             fetch(`{{ url('blog/save') }}/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const btn = document.getElementById(`save-btn-${postId}`);
                    const text = document.getElementById(`save-text-${postId}`);

                    if(data.saved) {
                        btn.classList.add('text-indigo-500');
                        text.innerText = 'Salvo';
                    } else {
                        btn.classList.remove('text-indigo-500');
                        text.innerText = 'Salvar';
                    }
                }
            });
        }

        function submitComment(e, postId) {
            e.preventDefault();
            const content = document.getElementById('comment-content').value;

            fetch(`{{ url('blog/comment') }}/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ content: content })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('comment-content').value = '';
                    alert(data.message);
                    if(data.comment) {
                         location.reload();
                    }
                }
            });
        }
    </script>
    @endpush
</x-blog::layouts.master>
