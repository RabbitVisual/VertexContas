<x-blog::layouts.master :title="$post->title" :description="$post->meta_description" :image="$post->og_image">
    <!-- Progress Bar -->
    <div class="fixed top-0 left-0 w-full h-1 z-50 bg-gray-200 dark:bg-slate-800">
        <div class="h-full bg-indigo-600 transition-all duration-150" id="scroll-progress" style="width: 0%"></div>
    </div>

    <div class="relative min-h-screen bg-white dark:bg-slate-900">

        <!-- Hero Header -->
        <div class="relative h-[400px] md:h-[500px] overflow-hidden">
            @if($post->featured_image)
                <img src="{{ asset($post->featured_image) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-indigo-900 to-slate-900"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-slate-900 via-transparent to-black/30"></div>

            <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 z-10">
                <div class="max-w-4xl mx-auto">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="px-3 py-1 rounded-lg bg-indigo-600 text-white text-xs font-black uppercase tracking-wider shadow-lg hover:bg-indigo-500 transition-colors">
                            {{ $post->category->name }}
                        </a>
                        <span class="flex items-center text-white/90 text-xs font-bold gap-1 bg-black/30 px-2 py-1 rounded-lg backdrop-blur-md">
                            <x-icon name="clock" class="w-3 h-3" /> {{ $post->read_time }}
                        </span>
                        @if($post->is_premium)
                             <span class="px-3 py-1 rounded-lg bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-black uppercase tracking-wider shadow-lg flex items-center gap-1">
                                <x-icon name="crown" class="w-3 h-3" /> PRO
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-6 leading-tight drop-shadow-sm">
                        {{ $post->title }}
                    </h1>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white font-bold border border-white/20">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            <div class="text-sm">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $post->author->name }}</p>
                                <p class="text-slate-600 dark:text-slate-400">{{ $post->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="lg:grid lg:grid-cols-12 lg:gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-12">

                    <!-- Content Body -->
                    @if($post->is_premium && !auth()->user()?->hasRole(['pro_user', 'admin', 'support']))
                        <div class="relative">
                            <div class="blur-md select-none pointer-events-none opacity-50 h-96 overflow-hidden prose dark:prose-invert max-w-none">
                                {!! Str::limit(strip_tags($post->content), 400) !!}
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            </div>

                            <!-- Premium Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm z-20">
                                <div class="text-center p-8 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-indigo-100 dark:border-indigo-900 max-w-md mx-4 relative overflow-hidden transform hover:scale-105 transition-transform duration-300">
                                     <!-- Golden Gradient Border Effect -->
                                     <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-300 via-yellow-500 to-amber-300"></div>

                                    <div class="w-20 h-20 bg-gradient-to-br from-amber-300 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-amber-500/30 ring-4 ring-amber-100 dark:ring-amber-900/30">
                                        <x-icon name="crown" class="w-10 h-10 text-white drop-shadow-md" />
                                    </div>

                                    <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">Conteúdo Exclusivo</h2>
                                    <p class="text-slate-600 dark:text-slate-400 mb-8 font-medium leading-relaxed">
                                        Desbloqueie estratégias de nível institucional. Este artigo é reservado para membros <span class="text-amber-600 dark:text-amber-500 font-bold">Vertex PRO</span>.
                                    </p>

                                    <button onclick="trackConversion({{ $post->id }})" class="w-full bg-gradient-to-r from-slate-900 to-slate-800 hover:from-slate-800 hover:to-slate-700 text-white font-bold py-4 px-6 rounded-xl transition-all shadow-lg hover:shadow-slate-900/30 transform hover:-translate-y-1 flex items-center justify-center gap-2 group">
                                        <span>Quero ser PRO agora</span>
                                        <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                    </button>

                                    <p class="mt-6 text-xs text-slate-400 font-medium">
                                        Já é assinante? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 underline decoration-2 underline-offset-2">Faça login</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <article class="prose dark:prose-invert prose-lg md:prose-xl mx-auto prose-indigo prose-headings:font-black prose-a:text-indigo-600 hover:prose-a:text-indigo-500 prose-img:rounded-2xl prose-img:shadow-lg trix-content">
                            {!! $post->content !!}
                        </article>
                    @endif

                    <!-- Actions Bar -->
                    <div class="mt-12 flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-8">
                        <div class="flex items-center gap-6">
                            @auth
                            <button onclick="toggleLike({{ $post->id }})" class="flex items-center gap-2 text-slate-500 hover:text-red-500 transition-colors group" id="like-btn-{{ $post->id }}">
                                <div class="p-2 rounded-full bg-slate-50 dark:bg-slate-800 group-hover:bg-red-50 dark:group-hover:bg-red-900/20 transition-colors">
                                    <x-icon name="heart" class="w-6 h-6 transition-transform group-hover:scale-110 {{ $post->isLikedBy(auth()->user()) ? 'fill-current text-red-500' : '' }}" />
                                </div>
                                <span class="font-bold text-sm" id="like-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                            </button>

                            <button onclick="toggleSave({{ $post->id }})" class="flex items-center gap-2 text-slate-500 hover:text-indigo-500 transition-colors group" id="save-btn-{{ $post->id }}">
                                <div class="p-2 rounded-full bg-slate-50 dark:bg-slate-800 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 transition-colors">
                                    <x-icon name="bookmark" class="w-6 h-6 transition-transform group-hover:scale-110 {{ $post->isSavedBy(auth()->user()) ? 'fill-current text-indigo-500' : '' }}" />
                                </div>
                                <span class="font-bold text-sm" id="save-text-{{ $post->id }}">{{ $post->isSavedBy(auth()->user()) ? 'Salvo' : 'Salvar' }}</span>
                            </button>
                            @endauth

                            <!-- Share Button (Simple implementation) -->
                            <button onclick="navigator.share({title: '{{ $post->title }}', url: window.location.href})" class="flex items-center gap-2 text-slate-500 hover:text-indigo-500 transition-colors group md:hidden">
                                <div class="p-2 rounded-full bg-slate-50 dark:bg-slate-800 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 transition-colors">
                                    <x-icon name="share-nodes" class="w-6 h-6" />
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Author Bio -->
                    <div class="mt-12 bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-8 flex items-center gap-6 border border-slate-100 dark:border-slate-800">
                        <div class="w-16 h-16 rounded-full bg-indigo-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white mb-1">Sobre o Autor</h3>
                            <p class="text-slate-600 dark:text-slate-400 font-medium">
                                Artigo escrito por <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $post->author->name }}</span>. Especialista em finanças e investimentos da equipe Vertex.
                            </p>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-16" id="comments">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                            <x-icon name="comments" class="text-indigo-500" />
                            Comentários ({{ $post->approvedComments->count() }})
                        </h3>

                        @auth
                            <form id="comment-form" onsubmit="submitComment(event, {{ $post->id }})" class="mb-12 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold flex-shrink-0">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <textarea id="comment-content" rows="3" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 p-4 transition-all" placeholder="Compartilhe sua opinião..."></textarea>
                                        <div class="flex justify-end mt-4">
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-lg hover:shadow-indigo-500/30">
                                                Publicar Comentário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 p-8 rounded-2xl text-center mb-12 border border-indigo-100 dark:border-indigo-500/30">
                                <p class="text-indigo-900 dark:text-indigo-200 font-medium mb-4">Entre na conversa e compartilhe suas ideias.</p>
                                <a href="{{ route('login') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all">Faça login para comentar</a>
                            </div>
                        @endauth

                        <div class="space-y-8" id="comments-list">
                            @foreach($post->approvedComments as $comment)
                                <div class="flex gap-4 group">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-bold">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-bold text-slate-900 dark:text-white">{{ $comment->user->name }}</h4>
                                                <span class="text-xs text-slate-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $comment->content }}</p>
                                        </div>

                                        <!-- Comment Actions -->
                                        <div class="flex items-center gap-4 mt-2 ml-2">
                                            @auth
                                                <button onclick="toggleCommentLike({{ $comment->id }})" class="flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-red-500 transition-colors {{ $comment->isLikedBy(auth()->user()) ? 'text-red-500' : '' }}" id="comment-like-btn-{{ $comment->id }}">
                                                    <x-icon name="heart" class="w-3 h-3 {{ $comment->isLikedBy(auth()->user()) ? 'fill-current' : '' }}" />
                                                    <span id="comment-like-count-{{ $comment->id }}">{{ $comment->likes()->count() }}</span>
                                                    Curtir
                                                </button>
                                                <button class="flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-indigo-500 transition-colors">
                                                    <x-icon name="reply" class="w-3 h-3" /> Responder
                                                </button>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <!-- Related Posts -->
            @if(count($relatedPosts) > 0)
            <div class="mt-24 pt-12 border-t border-slate-200 dark:border-slate-800">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-8">Continue Lendo</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $related)
                         <article class="group cursor-pointer" onclick="window.location='{{ route('blog.show', $related->slug) }}'">
                            <div class="relative h-48 rounded-2xl overflow-hidden mb-4 shadow-md">
                                @if($related->featured_image)
                                    <img src="{{ asset($related->featured_image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-slate-800"></div>
                                @endif
                                <div class="absolute bottom-2 left-2">
                                     <span class="px-2 py-1 rounded bg-black/50 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                        {{ $related->category->name }}
                                    </span>
                                </div>
                            </div>
                            <h4 class="font-bold text-lg text-slate-900 dark:text-white leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $related->title }}
                            </h4>
                            <p class="text-sm text-slate-500 mt-2">{{ $related->created_at->format('d M, Y') }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        // Scroll Progress Bar
        window.onscroll = function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("scroll-progress").style.width = scrolled + "%";
        };

        function trackConversion(postId) {
             fetch(`{{ url('blog/track-conversion') }}/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                // Redirect to subscription page
                window.location.href = "{{ route('paneluser.subscription.index') }}";
            });
        }

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
                        btn.querySelector('svg').classList.add('fill-current', 'text-red-500');
                        btn.classList.add('text-red-500');
                    } else {
                         btn.querySelector('svg').classList.remove('fill-current', 'text-red-500');
                         btn.classList.remove('text-red-500');
                    }
                }
            });
        }

        function toggleCommentLike(commentId) {
             fetch(`{{ url('blog/comment/like') }}/${commentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const btn = document.getElementById(`comment-like-btn-${commentId}`);
                    const count = document.getElementById(`comment-like-count-${commentId}`);

                    count.innerText = data.count;
                    if(data.liked) {
                        btn.querySelector('svg').classList.add('fill-current');
                        btn.classList.add('text-red-500');
                    } else {
                         btn.querySelector('svg').classList.remove('fill-current');
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
                         btn.querySelector('svg').classList.add('fill-current', 'text-indigo-500');
                         btn.classList.add('text-indigo-500');
                        text.innerText = 'Salvo';
                    } else {
                         btn.querySelector('svg').classList.remove('fill-current', 'text-indigo-500');
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
                    // Ideally use a Toast notification here
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
