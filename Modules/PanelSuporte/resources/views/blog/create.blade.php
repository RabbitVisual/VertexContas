<x-panelsuporte::layouts.master>
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    @endpush

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Criar Novo Post</h1>

            <form action="{{ route('suporte.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white" required>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo</label>
                    <input id="x" type="hidden" name="content">
                    <trix-editor input="x" class="trix-content dark:bg-slate-700 dark:text-white min-h-[300px]"></trix-editor>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagem de Destaque</label>
                        <input type="file" name="featured_image" id="featured_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-300">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                            <option value="draft">Rascunho</option>
                            <option value="pending_review">Revisão Pendente</option>
                            <option value="published">Publicado</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">SEO & Social</h3>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white"></textarea>
                            <p class="mt-2 text-sm text-gray-500">Breve descrição para motores de busca (máx 160 caracteres).</p>
                        </div>

                        <div>
                            <label for="og_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagem Social (OG Image)</label>
                            <input type="file" name="og_image" id="og_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-300">
                            <p class="mt-2 text-sm text-gray-500">Imagem específica para compartilhamento no Facebook/Twitter/LinkedIn (1200x630 recomendado).</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-6 border-t border-gray-200 dark:border-slate-700 pt-6">
                    <div class="flex items-center">
                        <input id="is_premium" name="is_premium" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_premium" class="ml-2 block text-sm text-gray-900 dark:text-white font-bold">Conteúdo Premium (PRO)</label>
                    </div>

                    <div class="flex items-center">
                        <input id="notify_users" name="notify_users" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="notify_users" class="ml-2 block text-sm text-gray-900 dark:text-white">Notificar Usuários</label>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <a href="{{ route('suporte.blog.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Salvar Post</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
</x-panelsuporte::layouts.master>
