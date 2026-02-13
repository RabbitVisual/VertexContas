<x-panelsuporte::layouts.master>
    <div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
        <!-- Header -->
        <div class="text-center space-y-4">
            <div class="inline-flex p-4 bg-primary/10 rounded-3xl text-primary mb-2">
                <x-icon name="book-user" style="duotone" class="w-12 h-12" />
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manual do Agente de Suporte</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Guia técnico para procedimentos e boas práticas de atendimento.</p>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Section 1 -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm">
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-primary text-white text-xs">01</span>
                    Acesso a Perfis de Usuário
                </h3>
                <div class="prose prose-slate dark:prose-invert max-w-none space-y-4 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                    <p>O acesso aos dados cadastrais dos usuários é restrito e exige autorização prévia. Siga os passos abaixo:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Solicite ao usuário que habilite o <strong>"Acesso para Suporte"</strong> em seu painel (Segurança).</li>
                        <li>Uma vez autorizado, o botão <strong>"Perfil Completo"</strong> ficará disponível na tela do ticket.</li>
                        <li>Lembre-se: o acesso expira automaticamente após 24 horas.</li>
                    </ul>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm">
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-primary text-white text-xs">02</span>
                    Auditoria e Rastreabilidade
                </h3>
                <div class="prose prose-slate dark:prose-invert max-w-none space-y-4 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                    <p>Todas as ações realizadas nos perfis de usuários são monitoradas:</p>
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20 rounded-2xl flex items-start gap-3">
                        <x-icon name="circle-exclamation" class="text-amber-600 mt-1" />
                        <p class="text-[11px] text-amber-700 dark:text-amber-400 font-bold leading-tight">
                            IMPORTANTE: Alterações em campos sensíveis (como e-mail ou status) geram logs automáticos com os valores anteriores e atuais para fins de auditoria.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm">
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-primary text-white text-xs">03</span>
                    Wiki Técnica
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Sempre consulte a Wiki antes de escalar um ticket para o nível administrativo.</p>
                <a href="{{ route('support.wiki.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                    Acessar Wiki Completa
                    <x-icon name="arrow-right" />
                </a>
            </div>
        </div>
    </div>
</x-panelsuporte::layouts.master>
