<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Categorias Wiki</x-slot>

    <x-paneladmin::page title="Categorias da Wiki" subtitle="Organize a base de conhecimento por assunto.">
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
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.wiki.articles') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="file-lines" style="duotone" class="w-4 h-4" /> Artigos
                </a>
                <button type="button" @click="$dispatch('open-modal', 'wiki-add-category')" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova Categoria
                </button>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm hover:border-slate-200 dark:hover:border-slate-600 transition-all">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center shrink-0">
                            <x-icon name="{{ $category->icon ?? 'book' }}" style="duotone" class="w-6 h-6" />
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="wikiEditCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->icon ?? '') }}', '{{ addslashes($category->description ?? '') }}')" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-[#11C76F] transition-colors">
                                <x-icon name="pen" style="duotone" class="w-4 h-4" />
                            </button>
                            <form action="{{ route('admin.wiki.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta categoria? Os artigos vinculados podem ser afetados.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                                    <x-icon name="trash" style="duotone" class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-1">{{ $category->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 min-h-[2.5rem]">{{ $category->description ?? 'Sem descrição.' }}</p>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $category->articles_count }} artigos</span>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">Ordem {{ $category->order }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <x-paneladmin::card>
                        <div class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                                <x-icon name="folder" style="duotone" class="w-8 h-8 text-slate-400" />
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhuma categoria cadastrada.</p>
                            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Crie a primeira para organizar os artigos da wiki.</p>
                            <button type="button" @click="$dispatch('open-modal', 'wiki-add-category')" class="mt-4 text-[#11C76F] font-bold text-sm hover:underline flex items-center gap-2 justify-center mx-auto">
                                <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova Categoria
                            </button>
                        </div>
                    </x-paneladmin::card>
                </div>
            @endforelse
        </div>

        <x-core::modal name="wiki-add-category" maxWidth="md">
            <div class="p-8 space-y-6">
                <h3 class="text-xl font-black text-slate-900 dark:text-white">Nova Categoria</h3>
                <form action="{{ route('admin.wiki.categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nome</label>
                        <input type="text" name="name" required placeholder="Ex: Configurações" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ícone (FontAwesome)</label>
                        <input type="text" name="icon" placeholder="book, gear, shield..." class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Descrição</label>
                        <textarea name="description" rows="3" placeholder="O que será abordado nesta categoria?" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium resize-none"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="$dispatch('close-modal', 'wiki-add-category')" class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-bold text-sm">Cancelar</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-[#11C76F] text-white font-bold text-sm hover:bg-[#0EA85A]">Criar</button>
                    </div>
                </form>
            </div>
        </x-core::modal>

        <x-core::modal name="wiki-edit-category" maxWidth="md">
            <div class="p-8 space-y-6">
                <h3 class="text-xl font-black text-slate-900 dark:text-white">Editar Categoria</h3>
                <form id="wiki-edit-category-form" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nome</label>
                        <input type="text" name="name" id="wiki-edit-name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ícone</label>
                        <input type="text" name="icon" id="wiki-edit-icon" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Descrição</label>
                        <textarea name="description" id="wiki-edit-description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium resize-none"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="$dispatch('close-modal', 'wiki-edit-category')" class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-bold text-sm">Cancelar</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-[#11C76F] text-white font-bold text-sm hover:bg-[#0EA85A]">Salvar</button>
                    </div>
                </form>
            </div>
        </x-core::modal>
    </x-paneladmin::page>

    @push('scripts')
    <script>
        function wikiEditCategory(id, name, icon, description) {
            document.getElementById('wiki-edit-category-form').action = '{{ url('admin/wiki/categories') }}/' + id;
            document.getElementById('wiki-edit-name').value = name || '';
            document.getElementById('wiki-edit-icon').value = icon || '';
            document.getElementById('wiki-edit-description').value = description || '';
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'wiki-edit-category' }));
        }
    </script>
    @endpush
</x-paneladmin::layouts.master>
