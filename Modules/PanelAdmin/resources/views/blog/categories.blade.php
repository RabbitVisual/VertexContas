<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Categorias do Blog</x-slot>

    <x-paneladmin::page title="Categorias do Blog" subtitle="Organize os posts por assunto e melhore o SEO.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <x-slot name="header">
            <button type="button" @click="$dispatch('open-modal', 'create-category-modal')" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova Categoria
            </button>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($categories as $category)
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm hover:border-[#11C76F]/20 transition-all flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-black text-lg">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $category->posts_count }} postagens</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">{{ $category->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed min-h-[2.5rem]">{{ $category->description ?? 'Sem descrição.' }}</p>

                    <div class="mt-6 flex items-center gap-3">
                        <button type="button" @click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')" class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 dark:hover:bg-slate-600/50 transition-colors flex items-center justify-center gap-2">
                            <x-icon name="pen" style="duotone" class="w-3.5 h-3.5" /> Editar
                        </button>
                        <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apagar esta categoria?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                                <x-icon name="trash" style="duotone" class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>

                <x-core::modal name="edit-category-{{ $category->id }}" maxWidth="md">
                    <div class="p-8 space-y-6">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white">Editar Categoria</h3>
                        <form action="{{ route('admin.blog.categories.update', $category) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nome</label>
                                <input type="text" name="name" required value="{{ old('name', $category->name) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Descrição</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium resize-none">{{ old('description', $category->description) }}</textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" @click="$dispatch('close-modal', 'edit-category-{{ $category->id }}')" class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-bold text-sm">Cancelar</button>
                                <button type="submit" class="flex-1 py-3 rounded-xl bg-[#11C76F] text-white font-bold text-sm hover:bg-[#0EA85A] transition-colors">Salvar</button>
                            </div>
                        </form>
                    </div>
                </x-core::modal>
            @empty
                <div class="col-span-full">
                    <x-paneladmin::card>
                        <div class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                                <x-icon name="folder" style="duotone" class="w-8 h-8 text-slate-400" />
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhuma categoria cadastrada.</p>
                            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Crie a primeira para organizar seus posts.</p>
                            <button type="button" @click="$dispatch('open-modal', 'create-category-modal')" class="mt-4 text-[#11C76F] font-bold text-sm hover:underline flex items-center gap-2 justify-center mx-auto">
                                <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova Categoria
                            </button>
                        </div>
                    </x-paneladmin::card>
                </div>
            @endforelse
        </div>

        <x-core::modal name="create-category-modal" maxWidth="md">
            <div class="p-8 space-y-6">
                <h3 class="text-xl font-black text-slate-900 dark:text-white">Nova Categoria</h3>
                <form action="{{ route('admin.blog.categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nome</label>
                        <input type="text" name="name" required placeholder="Ex: Finanças Pessoais" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Descrição</label>
                        <textarea name="description" rows="3" placeholder="O que será abordado nesta categoria?" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium resize-none"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="$dispatch('close-modal', 'create-category-modal')" class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-bold text-sm">Cancelar</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-[#11C76F] text-white font-bold text-sm hover:bg-[#0EA85A] transition-colors">Criar Categoria</button>
                    </div>
                </form>
            </div>
        </x-core::modal>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
