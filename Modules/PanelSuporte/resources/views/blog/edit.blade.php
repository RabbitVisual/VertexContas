<x-panelsuporte::layouts.master>
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    @endpush

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Editar Post: {{ $post->title }}</h1>

            <form action="{{ route('suporte.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                    <input type="text" name="title" id="title" value="{{ $post->title }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white" required>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo</label>
                    <input id="x" type="hidden" name="content" value="{{ $post->content }}">
                    <trix-editor input="x" class="trix-content dark:bg-slate-700 dark:text-white min-h-[300px]"></trix-editor>
                </div>

                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagem de Destaque</label>
                    @if($post->featured_image)
                        <img src="{{ asset($post->featured_image) }}" alt="Current Image" class="h-20 w-20 object-cover mb-2 rounded">
                    @endif
                    <input type="file" name="featured_image" id="featured_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-300">
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input id="is_premium" name="is_premium" type="checkbox" value="1" {{ $post->is_premium ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_premium" class="ml-2 block text-sm text-gray-900 dark:text-white">Conteúdo Premium (PRO)</label>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                        <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Rascunho</option>
                        <option value="pending_review" {{ $post->status == 'pending_review' ? 'selected' : '' }}>Revisão Pendente</option>
                        <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Publicado</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('suporte.blog.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Atualizar Post</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
</x-panelsuporte::layouts.master>
