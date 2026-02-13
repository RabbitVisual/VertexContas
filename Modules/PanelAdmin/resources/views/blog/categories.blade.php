<x-paneladmin::layouts.master>
    <div class="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-500">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.blog.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                    <x-icon name="arrow-left" />
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Categorias do Blog</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Organize seus posts por assunto e melhore o SEO.</p>
                </div>
            </div>
            <button @click="$dispatch('open-modal', 'create-category-modal')" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-2xl font-black text-sm shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                <x-icon name="plus" /> Nova Categoria
            </button>
        </div>

        <!-- Categories List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($categories as $category)
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm group hover:border-primary/30 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-lg">
                                {{ substr($category->name, 0, 1) }}
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $category->posts_count }} Postagens</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed h-10">{{ $category->description ?? 'Sem descrição definida.' }}</p>
                    </div>

                    <div class="mt-8 flex items-center gap-3">
                        <button @click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')" class="flex-1 py-3 bg-gray-50 dark:bg-slate-800 text-slate-600 dark:text-gray-400 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-gray-100 transition-all">
                            Editar
                        </button>
                        <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apagar esta categoria?')" class="flex-none">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-3 bg-red-50 dark:bg-red-900/20 text-red-600 rounded-xl hover:bg-red-100 transition-colors">
                                <x-icon name="trash" class="text-xs" />
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Edit Modal -->
                <x-core::modal name="edit-category-{{ $category->id }}">
                    <div class="p-8 space-y-6">
                        <h3 class="text-xl font-black text-slate-800 dark:text-white">Editar Categoria</h3>
                        <form action="{{ route('admin.blog.categories.update', $category) }}" method="POST" class="space-y-6">
                            @csrf @method('PUT')
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nome da Categoria</label>
                                <input type="text" name="name" required value="{{ $category->name }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Descrição</label>
                                <textarea name="description" rows="3" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-medium resize-none">{{ $category->description }}</textarea>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" @click="$dispatch('close-modal', 'edit-category-{{ $category->id }}')" class="flex-1 py-4 bg-gray-100 dark:bg-slate-800 text-slate-600 font-black rounded-2xl">Cancelar</button>
                                <button type="submit" class="flex-1 py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20">Salvar</button>
                            </div>
                        </form>
                    </div>
                </x-core::modal>
            @empty
                <div class="col-span-full py-20 bg-white dark:bg-slate-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 text-center">
                    <x-icon name="tags" class="text-6xl text-slate-200 mb-6" />
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhuma categoria encontrada.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Modal -->
    <x-core::modal name="create-category-modal">
        <div class="p-8 space-y-6">
            <h3 class="text-xl font-black text-slate-800 dark:text-white">Nova Categoria</h3>
            <form action="{{ route('admin.blog.categories.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nome da Categoria</label>
                    <input type="text" name="name" required placeholder="Ex: Finanças Pessoais" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Descrição</label>
                    <textarea name="description" rows="3" placeholder="O que será abordado aqui?" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-medium resize-none"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="button" @click="$dispatch('close-modal', 'create-category-modal')" class="flex-1 py-4 bg-gray-100 dark:bg-slate-800 text-slate-600 font-black rounded-2xl">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20">Criar Categoria</button>
                </div>
            </form>
        </div>
    </x-core::modal>
</x-paneladmin::layouts.master>
