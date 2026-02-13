<x-paneladmin::layouts.master>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.wiki.articles') }}" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 text-slate-500 hover:text-primary transition-colors">
                <x-icon name="arrow-left" />
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Novo Artigo Wiki</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Escreva uma nova orientação técnica para o suporte.</p>
            </div>
        </div>
        <form action="{{ route('admin.wiki.articles.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-800 shadow-xl space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Título do Artigo</label>
                        <input type="text" name="title" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold" placeholder="ex: Como configurar o PIX">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Categoria</label>
                        <select name="category_id" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold appearance-none">
                            <option value="">Selecione uma categoria...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Conteúdo (Markdown Suportado)</label>
                    <textarea name="content" rows="15" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold resize-none" placeholder="Escreva o conteúdo técnico aqui..."></textarea>
                </div>
                <div class="flex items-center gap-3 px-1 pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Publicar Imediatamente</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.wiki.articles') }}" class="px-8 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-gray-400 font-black rounded-2xl border border-gray-100 dark:border-gray-800 hover:bg-gray-100 transition-all">Descartar</a>
                <button type="submit" class="px-12 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/30 hover:bg-primary-dark transition-all hover:scale-105 active:scale-95">Salvar Artigo</button>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
