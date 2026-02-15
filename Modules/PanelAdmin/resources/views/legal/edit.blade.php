<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar Documento Legal</x-slot>

    @push('scripts')
        @vite('resources/js/legal-editor.js')
    @endpush

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.legal.index') }}" class="p-3 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 text-slate-500 hover:text-primary transition-colors">
                <x-icon name="arrow-left" style="duotone" />
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Editar Documento Legal</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">{{ $document->title }} — Vertex Solutions LTDA</p>
            </div>
        </div>
        <form action="{{ route('admin.legal.update', $document) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-800 shadow-xl space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Título</label>
                        <input type="text" name="title" value="{{ old('title', $document->title) }}" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Versão</label>
                        <input type="text" name="version" value="{{ old('version', $document->version) }}" placeholder="1.0.0" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 dark:text-white text-sm font-bold">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Conteúdo</label>
                    <input id="legal-content" type="hidden" name="content" value="{{ old('content', $document->content) }}">
                    <trix-editor input="legal-content" class="trix-content min-h-[300px] bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] px-6 py-4 focus:ring-2 focus:ring-primary/20 dark:text-white prose prose-slate dark:prose-invert max-w-none"></trix-editor>
                </div>
                <div class="flex flex-wrap items-center gap-6 px-1 pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $document->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Ativo</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="requires_acceptance" value="0">
                        <input type="checkbox" name="requires_acceptance" value="1" class="sr-only peer" {{ old('requires_acceptance', $document->requires_acceptance) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Exige aceite do usuário</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="force_reamendment" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500"></div>
                        <span class="ml-3 text-sm font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">Exigir reaceite por todos os usuários ao publicar</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.legal.index') }}" class="px-8 py-4 bg-white dark:bg-slate-900 text-slate-600 dark:text-gray-400 font-black rounded-2xl border border-gray-100 dark:border-gray-800 hover:bg-gray-100 transition-all">Cancelar</a>
                <button type="submit" class="px-12 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/30 hover:bg-primary-dark transition-all hover:scale-105 active:scale-95">Atualizar Documento</button>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
