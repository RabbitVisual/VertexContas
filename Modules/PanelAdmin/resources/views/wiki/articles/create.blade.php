<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Novo Artigo Wiki</x-slot>

    <x-paneladmin::page title="Novo Artigo Wiki" subtitle="Escreva uma nova orientação técnica para a base de conhecimento.">
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif
        <x-slot name="header">
            <a href="{{ route('admin.wiki.articles') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar aos Artigos
            </a>
        </x-slot>
        <form action="{{ route('admin.wiki.articles.store') }}" method="POST" class="max-w-4xl space-y-6">
            @csrf
            <x-paneladmin::card title="Conteúdo" subtitle="Título, categoria e corpo (Markdown).">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Título</label>
                            <input type="text" name="title" required value="{{ old('title') }}" placeholder="Ex: Como configurar o PIX" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Categoria</label>
                            <div class="relative">
                                <select name="category_id" required class="wiki-select w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] appearance-none [&::-ms-expand]:hidden">
                                    <option value="">Selecione...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"><x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" /></span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Conteúdo (Markdown)</label>
                        <textarea name="content" rows="14" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium text-sm resize-y focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="Escreva o conteúdo técnico aqui...">{{ old('content') }}</textarea>
                    </div>
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700">
                        <div>
                            <span class="text-sm font-bold text-slate-900 dark:text-white block">Publicar imediatamente</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Visível na wiki do suporte</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 dark:bg-slate-600 rounded-full peer peer-checked:bg-[#11C76F] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>
                </div>
            </x-paneladmin::card>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.wiki.articles') }}" class="px-6 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold rounded-xl flex items-center gap-2">
                    <x-icon name="floppy-disk" style="duotone" class="w-5 h-5" /> Salvar Artigo
                </button>
            </div>
        </form>
    </x-paneladmin::page>
    @push('styles')
    <style>.wiki-select{appearance:none!important;-webkit-appearance:none!important;-moz-appearance:none!important;background-image:none!important}</style>
    @endpush
</x-paneladmin::layouts.master>
