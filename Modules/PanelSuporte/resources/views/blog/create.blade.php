<x-panelsuporte::layouts.master title="Criar Post - Suporte">
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    @endpush

    <div class="space-y-8 animate-in fade-in duration-500 max-w-4xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-primary/10 rounded-2xl text-primary">
                <x-icon name="newspaper" style="duotone" class="text-2xl" />
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Criar Novo Post</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Preencha os dados para publicar no blog.</p>
            </div>
        </div>

        <form action="{{ route('suporte.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-slate-800 dark:text-white" required>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-slate-800 dark:text-white">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo</label>
                    <input id="x" type="hidden" name="content">
                    <trix-editor input="x" class="trix-content dark:bg-slate-800 dark:text-white min-h-[300px] rounded-xl"></trix-editor>
                </div>

                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagem de Destaque</label>
                    <input type="file" name="featured_image" id="featured_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 dark:text-gray-300">
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input id="is_premium" name="is_premium" type="checkbox" value="1" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="is_premium" class="ml-2 block text-sm text-gray-900 dark:text-white">Conteúdo Premium (PRO)</label>
                    </div>

                    <div class="flex items-center">
                        <input id="notify_users" name="notify_users" type="checkbox" value="1" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="notify_users" class="ml-2 block text-sm text-gray-900 dark:text-white">Notificar Usuários</label>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-slate-800 dark:text-white">
                        <option value="draft">Rascunho</option>
                        <option value="pending_review">Revisão Pendente</option>
                        <option value="published">Publicado</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('suporte.blog.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-colors">Cancelar</a>
                    <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/20">Salvar Post</button>
                </div>
            </form>
    </div>

    @push('scripts')
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
</x-panelsuporte::layouts.master>
