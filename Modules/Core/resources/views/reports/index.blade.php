<x-paneluser::layouts.master :title="'Relatórios'">
@php $isPro = auth()->user()?->isPro() ?? false; @endphp
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700" x-data="{ showConsultingModal: false }">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none" data-tour="reports-intro">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                    <a href="{{ ($isPro && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index') }}" class="hover:underline">Dashboard</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
                    <span class="text-gray-400 dark:text-gray-500">Relatórios</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Seus <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Relatórios</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">{{ $isPro ? 'Analise suas finanças com relatórios detalhados e exporte quando precisar.' : 'Visualize um resumo básico da sua conta. Faça upgrade para relatórios completos, filtros e exportação.' }}</p>
                @if($isPro)
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                        <x-icon name="chart-line" style="solid" class="w-3.5 h-3.5" /> Comparativos
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 text-xs font-semibold">
                        <x-icon name="lightbulb" style="solid" class="w-3.5 h-3.5" /> Dicas de uso
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs font-semibold">
                        <x-icon name="file-export" style="solid" class="w-3.5 h-3.5" /> Exportação
                    </span>
                </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if(!empty($pageTourId) && count($pageTourSteps ?? []) > 0)
                    <x-core::tour-guide :tour-id="$pageTourId" label="Ver tour desta página" />
                @endif
                <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0" role="region" aria-label="Resumo">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 rounded-2xl {{ $isPro ? 'bg-emerald-600/10 dark:bg-emerald-500/20' : 'bg-amber-500/10 dark:bg-amber-500/20' }} flex items-center justify-center {{ $isPro ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }} shrink-0">
                        <x-icon name="{{ $isPro ? 'chart-simple' : 'eye' }}" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">
                            {{ $isPro ? plan_pro_name() : 'Visualização básica' }}
                        </p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white leading-tight">
                            {{ $isPro ? ($transactionCount ?? 0) . ' transações' : 'Apenas visualização' }}
                        </p>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Cards --}}
    <div class="grid grid-cols-1 gap-6" data-tour="reports-cards">
        {{-- Consultoria Mensal (PRO) - Destaque --}}
        @if($isPro)
        <div class="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 dark:from-blue-500 dark:to-purple-600 rounded-3xl border border-blue-500/20 dark:border-purple-500/20 shadow-lg hover:shadow-xl transition-all duration-500">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-white/20">
                    <span class="text-xs font-black text-blue-100 uppercase tracking-[0.2em]">{{ plan_pro_name() }}</span>
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <x-icon name="crown" style="solid" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-white group-hover:text-blue-100 transition-colors mb-2">Consultoria Mensal</h3>
                    <p class="text-blue-100 text-sm leading-relaxed">Análise 50/30/20, score financeiro, recomendações personalizadas e medalhas conquistadas. Imprima ou salve como PDF.</p>
                </div>
                <div class="lg:w-64 p-8 flex flex-col items-center justify-center gap-3 bg-white/10 border-t lg:border-t-0 lg:border-l border-white/10">
                    <a href="{{ route('core.reports.consultoria.view', ['nova' => 1]) }}" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-white text-blue-600 hover:bg-blue-50 font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg">
                        Gerar Consultoria
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                    <a href="{{ route('core.reports.consultoria.history') }}" class="text-xs font-medium text-blue-100 hover:text-white transition-colors">
                        Ver histórico de relatórios
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="group relative overflow-hidden bg-gradient-to-r from-blue-600/90 to-purple-600/90 dark:from-blue-600/80 dark:to-purple-600/80 rounded-3xl border border-blue-500/20 dark:border-purple-500/20 shadow-lg">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-white/20">
                    <span class="text-xs font-black text-blue-100 uppercase tracking-[0.2em]">{{ plan_pro_name() }}</span>
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <x-icon name="crown" style="solid" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-white mb-2">Consultoria Mensal</h3>
                    <p class="text-blue-100 text-sm leading-relaxed">Análise 50/30/20, score financeiro, recomendações personalizadas e medalhas conquistadas.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-white/10 border-t lg:border-t-0 lg:border-l border-white/10">
                    <button type="button" @click="showConsultingModal = true" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-white text-blue-600 hover:bg-blue-50 font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg">
                        Ver Consultoria
                        <x-icon name="crown" style="solid" class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Resumo Mensal (Wrapped) --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/40 shadow-sm hover:shadow-xl">
            <div class="absolute right-4 top-4">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-[10px] font-black uppercase tracking-[0.18em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                    Novo
                </span>
            </div>
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-[0.2em]">Resumo</span>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="chart-pie" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors mb-2">Resumo Mensal (Wrapped)</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Uma visão de story do mês: quanto foi para necessidades, desejos e futuro na prática, junto com as medalhas conquistadas.
                    </p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('core.reports.monthly-wrap') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                        Ver meu mês
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        {{-- Fluxo de Caixa --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 shadow-sm hover:shadow-xl">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-[0.2em]">Relatório</span>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="money-bill-trend-up" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors mb-2">Fluxo de Caixa</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Visualize receitas e despesas ao longo do tempo para entender seu saldo mensal.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('core.reports.cashflow') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                        Acessar
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        {{-- Ranking de Categorias --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-purple-500/30 shadow-sm hover:shadow-xl">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-purple-600 dark:text-purple-500 uppercase tracking-[0.2em]">Relatório</span>
                    <div class="w-14 h-14 rounded-2xl bg-purple-600/10 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0">
                        <x-icon name="chart-pie" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors mb-2">Ranking de Categorias</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Descubra para onde seu dinheiro está indo com despesas agrupadas por categoria.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('core.reports.categories') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-purple-500/20">
                        Acessar
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        {{-- Extrato Vertex (PRO only) --}}
        @if($isPro)
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-500 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-teal-500/30 shadow-sm hover:shadow-xl">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-teal-600 dark:text-teal-500 uppercase tracking-[0.2em]">{{ plan_pro_name() }}</span>
                    <div class="w-14 h-14 rounded-2xl bg-teal-600/10 dark:bg-teal-500/20 flex items-center justify-center text-teal-600 dark:text-teal-400 shrink-0">
                        <x-icon name="building-columns" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors mb-2">Extrato Vertex</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Lista de transações com data, descrição, débito, crédito e saldo acumulado. Estilo extrato de conta.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('core.reports.extrato') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-teal-500/20">
                        Acessar
                        <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm opacity-90">
            <div class="flex flex-col lg:flex-row items-stretch">
                <div class="lg:w-48 bg-gray-50 dark:bg-gray-900 p-8 flex flex-row lg:flex-col items-center justify-center gap-4 lg:gap-1 text-center border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-white/5">
                    <span class="text-xs font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.2em]">{{ plan_pro_name() }}</span>
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                        <x-icon name="building-columns" style="duotone" class="w-7 h-7" />
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Extrato Vertex</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Relatório exclusivo para {{ plan_pro_name() }}. Assine para acessar extrato completo com filtros e exportação.</p>
                </div>
                <div class="lg:w-64 p-8 flex items-center justify-center bg-gray-50 dark:bg-gray-900/30 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-white/5">
                    <a href="{{ route('user.subscription.index') }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-lg">
                        <x-icon name="crown" style="solid" class="w-4 h-4" />
                        Fazer Upgrade
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Guia rápido: o que cada relatório faz (apenas PRO) --}}
    @if($isPro)
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 p-6 sm:p-8 shadow-sm">
        <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <x-icon name="circle-question" style="duotone" class="w-5 h-5 text-emerald-600" />
            Guia rápido dos relatórios
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
            <div class="p-4 rounded-xl bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/10 dark:to-purple-900/10 border border-blue-100 dark:border-blue-800/30">
                <span class="font-bold text-blue-700 dark:text-blue-400 block mb-2">Consultoria Mensal</span>
                <p class="text-slate-600 dark:text-slate-400">Score financeiro, análise 50/30/20 (Essencial/Estilo de Vida/Financeiro), recomendações personalizadas e medalhas do mês. Imprima ou salve como PDF.</p>
            </div>
            <div class="p-4 rounded-xl bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30">
                <span class="font-bold text-emerald-700 dark:text-emerald-400 block mb-2">Fluxo de Caixa</span>
                <p class="text-slate-600 dark:text-slate-400">Mostra receitas e despesas ao longo do tempo. Use para ver tendências, taxa de poupança e identificar meses atípicos.</p>
            </div>
            <div class="p-4 rounded-xl bg-purple-50/50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-800/30">
                <span class="font-bold text-purple-700 dark:text-purple-400 block mb-2">Ranking de Categorias</span>
                <p class="text-slate-600 dark:text-slate-400">Para onde vai seu dinheiro? Despesas agrupadas por categoria para você priorizar onde cortar ou ajustar.</p>
            </div>
            <div class="p-4 rounded-xl bg-teal-50/50 dark:bg-teal-900/10 border border-teal-100 dark:border-teal-800/30">
                <span class="font-bold text-teal-700 dark:text-teal-400 block mb-2">Extrato Vertex</span>
                <p class="text-slate-600 dark:text-slate-400">Lista transação por transação com saldo acumulado. Ideal para conferir contra o banco ou exportar.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal: Consultoria exclusiva PRO (Free users) --}}
    <div x-show="showConsultingModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="showConsultingModal = false" role="dialog" aria-modal="true" aria-labelledby="consulting-modal-title">
        <div x-show="showConsultingModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 shadow-2xl p-8" @click.stop>
            <button type="button" @click="showConsultingModal = false" class="absolute top-4 right-4 p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors" aria-label="Fechar">
                <x-icon name="xmark" style="solid" class="w-5 h-5" />
            </button>
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center text-white mb-6">
                    <x-icon name="crown" style="solid" class="w-8 h-8" />
                </div>
                <h2 id="consulting-modal-title" class="text-xl font-black text-gray-900 dark:text-white mb-3">Consultoria Exclusiva {{ plan_pro_name() }}</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-6">A análise 50/30/20 e o relatório de consultoria financeira são exclusivos do plano {{ plan_pro_name() }}. Faça upgrade para receber recomendações personalizadas, score financeiro e medalhas.</p>
                <a href="{{ route('user.subscription.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-black text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg">
                    <x-icon name="crown" style="solid" class="w-5 h-5" />
                    Fazer Upgrade
                </a>
            </div>
        </div>
    </div>

    @if(!$isPro)
    <div class="relative overflow-hidden bg-slate-900 rounded-3xl p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-purple-600/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-indigo-600/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <span class="bg-gradient-to-r from-purple-400 to-pink-400 text-transparent bg-clip-text font-black text-sm uppercase tracking-widest mb-2 block">{{ plan_pro_name() }}</span>
                <h3 class="text-2xl font-black text-white mb-2">Relatórios Avançados e Exportação</h3>
                <p class="text-slate-400 max-w-xl">Desbloqueie exportação PDF/CSV, extrato bancário, filtros avançados e relatórios exclusivos.</p>
            </div>
            <a href="{{ route('user.subscription.index') }}" class="whitespace-nowrap inline-flex items-center px-8 py-4 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-50 transition-all transform hover:scale-105 shadow-lg">
                <x-icon name="crown" style="solid" class="text-purple-600 mr-2" />
                Fazer Upgrade
            </a>
        </div>
    </div>
    @endif
</div>

@if(!empty($pageTourId) && !empty($pageTourSteps))
@push('scripts')
<script>
(function() {
    var tourId = @json($pageTourId);
    var steps = @json($pageTourSteps);
    function register() {
        if (window.registerVertexTourSteps && steps && steps.length) {
            window.registerVertexTourSteps(tourId, steps);
            return;
        }
        setTimeout(register, 50);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', register);
    } else {
        register();
    }
})();
</script>
@endpush
@endif
</x-paneluser::layouts.master>
