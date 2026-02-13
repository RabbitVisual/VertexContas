<x-blog::layouts.master>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <x-icon name="newspaper" class="w-8 h-8 mr-3 text-indigo-600" />
                    Vertex Insights
                </h1>

                <div class="flex space-x-2">
                    <!-- Filters/Search could go here -->
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full border border-gray-100 dark:border-slate-700">
                    <div class="relative h-48 overflow-hidden group">
                        @if($post->featured_image)
                            <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <x-icon name="newspaper" class="w-12 h-12 text-white/50" />
                            </div>
                        @endif

                        @if($post->is_premium)
                            <div class="absolute top-4 right-4 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center shadow-md">
                                <x-icon name="crown" class="w-3 h-3 mr-1" />
                                PRO
                            </div>
                        @endif

                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                            <span class="text-white/90 text-xs font-medium bg-black/30 px-2 py-1 rounded backdrop-blur-sm">
                                {{ $post->category->name }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3 space-x-3">
                            <span class="flex items-center">
                                <x-icon name="calendar" class="w-3 h-3 mr-1" />
                                {{ $post->created_at->format('d M, Y') }}
                            </span>
                            <span class="flex items-center">
                                <x-icon name="eye" class="w-3 h-3 mr-1" />
                                {{ $post->views }}
                            </span>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3 flex-1">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>

                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xs mr-2">
                                    {{ substr($post->author->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $post->author->name }}</span>
                            </div>

                            <a href="{{ route('blog.show', $post->slug) }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold hover:underline flex items-center">
                                Ler Mais <x-icon name="arrow-right" class="w-3 h-3 ml-1" />
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-blog::layouts.master>
