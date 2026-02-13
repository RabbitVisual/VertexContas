<x-paneladmin::layouts.master>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Categorias da Wiki</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Gerencie as categorias para organizar a base de conhecimento.</p>
            </div>
            <button onclick="document.getElementById('modal-add-category').classList.remove('hidden')"
                class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-2xl font-black text-sm shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                <x-icon name="plus" /> Nova Categoria
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                            <x-icon name="{{ $category->icon ?? 'book' }}" class="text-xl" />
                        </div>
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="editCategory({{ json_encode($category) }})" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                <x-icon name="pen" class="text-xs" />
                            </button>
                            <form action="{{ route('admin.wiki.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Tem certeza? Isso apagará todos os artigos desta categoria.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors">
                                    <x-icon name="trash" class="text-xs" />
                                </button>
                            </form>
                        </div>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 dark:text-white mb-1">{{ $category->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4 font-medium">{{ $category->description ?? 'Sem descrição disponível.' }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-50 dark:border-gray-800">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $category->articles_count }} Artigos</span>
                        <span class="text-[10px] font-black px-2 py-1 bg-gray-100 dark:bg-slate-800 text-gray-500 rounded-lg uppercase">Ordem: {{ $category->order }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="modal-add-category" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl w-full max-w-lg p-8 transform transition-all border border-white/20">
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-6">Nova Categoria</h3>
                <form action="{{ route('admin.wiki.categories.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nome da Categoria</label>
                        <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Ícone (FontAwesome)</label>
                        <input type="text" name="icon" placeholder="ex: book, shield, gear" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Descrição</label>
                        <textarea name="description" rows="3" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold resize-none"></textarea>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="this.closest('#modal-add-category').classList.add('hidden')" class="flex-1 px-6 py-4 bg-gray-100 dark:bg-slate-800 text-slate-600 dark:text-gray-400 font-black rounded-2xl hover:bg-gray-200 transition-all">Cancelar</button>
                        <button type="submit" class="flex-1 px-6 py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all">Criar Agora</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="modal-edit-category" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl w-full max-w-lg p-8 transform transition-all border border-white/20">
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-6">Editar Categoria</h3>
                <form id="edit-category-form" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nome da Categoria</label>
                        <input type="text" name="name" id="edit-category-name" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Ícone (FontAwesome)</label>
                        <input type="text" name="icon" id="edit-category-icon" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Descrição</label>
                        <textarea name="description" id="edit-category-description" rows="3" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold resize-none"></textarea>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="this.closest('#modal-edit-category').classList.add('hidden')" class="flex-1 px-6 py-4 bg-gray-100 dark:bg-slate-800 text-slate-600 dark:text-gray-400 font-black rounded-2xl hover:bg-gray-200 transition-all">Cancelar</button>
                        <button type="submit" class="flex-1 px-6 py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function editCategory(category) {
            document.getElementById('edit-category-form').action = `/admin/wiki/categories/${category.id}`;
            document.getElementById('edit-category-name').value = category.name;
            document.getElementById('edit-category-icon').value = category.icon;
            document.getElementById('edit-category-description').value = category.description;
            document.getElementById('modal-edit-category').classList.remove('hidden');
        }
    </script>
    @endpush
</x-paneladmin::layouts.master>
