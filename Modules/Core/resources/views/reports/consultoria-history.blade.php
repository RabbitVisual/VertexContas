<x-paneluser::layouts.master :title="'Histórico de Consultoria'">
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    {{-- Header --}}
    <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 shadow-sm">
        <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4" aria-label="Navegação">
            <a href="{{ route('core.reports.index') }}" class="hover:underline">Relatórios</a>
            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
            <span class="text-gray-400 dark:text-gray-500">Histórico Consultoria</span>
        </nav>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Relatórios Salvos</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Seus relatórios de consultoria com IA. Clique para abrir e imprimir.</p>
    </div>

    {{-- List --}}
    <div class="space-y-3">
        @forelse($reports as $report)
        @php
            $periodDate = \Carbon\Carbon::createFromFormat('Y-m', $report->period);
            $periodLabel = $periodDate->locale('pt_BR')->translatedFormat('F Y');
        @endphp
        <div class="group p-6 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 hover:shadow-lg transition-all flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('core.reports.consultoria.view', ['period' => $report->period]) }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center gap-4 min-w-0">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <x-icon name="file-lines" style="duotone" class="w-6 h-6" />
                </div>
                <div class="min-w-0">
                    <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $periodLabel }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Gerado em {{ $report->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <x-icon name="arrow-right" style="solid" class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all shrink-0 ml-auto sm:ml-0" />
            </a>
            <a href="{{ route('core.reports.consultoria.view', ['period' => $report->period, 'nova' => 1]) }}" target="_blank" rel="noopener noreferrer"
               class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-emerald-500/50 text-emerald-600 dark:text-emerald-400 text-xs font-semibold hover:bg-emerald-500/10 transition-colors"
               title="Gerar nova versão com dados atualizados">
                <x-icon name="arrows-rotate" style="solid" class="w-4 h-4" />
                Atualizar
            </a>
        </div>
        @empty
        <div class="p-12 rounded-2xl bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-white/5 text-center">
            <x-icon name="file-lines" style="duotone" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" />
            <p class="font-semibold text-gray-700 dark:text-gray-300">Nenhum relatório salvo ainda</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gere sua primeira consultoria para que ela apareça aqui.</p>
            <a href="{{ route('core.reports.consultoria.view', ['nova' => 1]) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition-colors">
                Gerar Consultoria
                <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
            </a>
        </div>
        @endforelse
    </div>

    <div class="flex justify-center">
        <a href="{{ route('core.reports.index') }}" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
            ← Voltar aos Relatórios
        </a>
    </div>
</div>
</x-paneluser::layouts.master>
