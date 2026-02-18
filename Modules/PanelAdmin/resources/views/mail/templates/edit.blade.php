<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar Template</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.mail.templates.index') }}" class="p-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-[#11C76F] transition-colors">
                <x-icon name="arrow-left" style="duotone" />
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Editar Template</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium font-mono">{{ $template->key }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-600 dark:text-red-400">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.mail.templates.update', $template) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
                <div class="space-y-2">
                    <label for="subject" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Assunto</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject', $template->subject) }}" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] dark:text-white text-sm font-medium">
                </div>
                <div class="space-y-2">
                    <label for="description" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Descrição (opcional)</label>
                    <input type="text" name="description" id="description" value="{{ old('description', $template->description) }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] dark:text-white text-sm font-medium"
                        placeholder="Ex.: E-mail enviado após o cadastro">
                </div>
                <div class="space-y-2">
                    <label for="variables_hint" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Variáveis disponíveis</label>
                    <input type="text" name="variables_hint" id="variables_hint" value="{{ old('variables_hint', $template->variables_hint ?? '') }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] dark:text-white text-sm font-mono"
                        placeholder="Ex.: name, link, app_url, reset_link">
                    <p class="text-xs text-slate-500 dark:text-slate-400">Liste as variáveis que este template aceita (ex.: name, link, app_url). Use no corpo como <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">@{{ name }}</code>.</p>
                </div>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_html" value="0">
                        <input type="checkbox" name="is_html" value="1" {{ old('is_html', $template->is_html ?? true) ? 'checked' : '' }}
                            class="rounded border-slate-300 dark:border-slate-600 text-[#11C76F] focus:ring-[#11C76F]/20">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Conteúdo é HTML (desmarque para texto simples com nl2br)</span>
                    </label>
                </div>
                <div class="space-y-2">
                    <label for="content_html" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Corpo (HTML ou texto)</label>
                    <textarea name="content_html" id="content_html" rows="18" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] dark:text-white text-sm font-mono">{{ old('content_html', $template->content_html ?? $template->body_html ?? '') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.mail.templates.index') }}" class="px-6 py-3 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">Voltar</a>
                <button type="submit" class="px-8 py-3 bg-[#11C76F] text-white font-bold rounded-xl shadow-sm hover:bg-[#0EA85A] transition-colors">Salvar</button>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
