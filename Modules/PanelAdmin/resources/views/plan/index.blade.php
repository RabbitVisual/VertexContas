<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Página de Planos</x-slot>

    <x-paneladmin::page title="Conteúdo da Página Pública de Planos" subtitle="Edite o copy exibido em /planos. Deixe em branco para usar o padrão na view.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-4 mb-6">
            <a href="{{ url('/planos') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="arrow-up-right-from-square" style="duotone" class="w-4 h-4" /> Ver página pública
            </a>
        </div>

        <form action="{{ route('admin.plan.update') }}" method="POST" class="space-y-6">
            @csrf
            <x-paneladmin::card title="Hero" subtitle="Título e subtítulo da página de planos.">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título (headline)</label>
                        <input type="text" name="plan_page_headline" value="{{ old('plan_page_headline', $headline) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="Assuma o Controle da sua Elite Financeira">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subtítulo</label>
                        <textarea name="plan_page_subhead" rows="2" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="Pare de apenas anotar gastos...">{{ old('plan_page_subhead', $subhead) }}</textarea>
                    </div>
                </div>
            </x-paneladmin::card>

            <x-paneladmin::card title="Preços e CTA" subtitle="Valores exibidos e texto do botão.">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preço mensal (ex.: 29,90)</label>
                        <input type="text" name="plan_page_monthly_price" value="{{ old('plan_page_monthly_price', $monthlyPrice) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preço anual (ex.: 197,00)</label>
                        <input type="text" name="plan_page_yearly_price" value="{{ old('plan_page_yearly_price', $yearlyPrice) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Economia anual (ex.: 160,00)</label>
                        <input type="text" name="plan_page_yearly_savings" value="{{ old('plan_page_yearly_savings', $yearlySavings) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Texto do botão CTA</label>
                        <input type="text" name="plan_page_cta_text" value="{{ old('plan_page_cta_text', $ctaText) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="QUERO SER VERTEX PRO">
                    </div>
                </div>
            </x-paneladmin::card>

            <x-paneladmin::card title="Bloco de benefícios (HTML)" subtitle="HTML dos benefícios PRO. Deixe vazio para não exibir bloco extra.">
                <div class="p-6">
                    <textarea name="plan_page_features_html" rows="12" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] font-mono text-sm" placeholder="<h2>O que você desbloqueia</h2>...">{{ old('plan_page_features_html', $featuresHtml) }}</textarea>
                </div>
            </x-paneladmin::card>

            <x-paneladmin::card title="Tabela comparativa (HTML)" subtitle="HTML da tabela FREE vs PRO. Deixe vazio para não exibir tabela.">
                <div class="p-6">
                    <textarea name="plan_page_table_html" rows="14" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] font-mono text-sm" placeholder="<table>...</table>">{{ old('plan_page_table_html', $tableHtml) }}</textarea>
                </div>
            </x-paneladmin::card>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                    Salvar conteúdo da página de planos
                </button>
            </div>
        </form>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
