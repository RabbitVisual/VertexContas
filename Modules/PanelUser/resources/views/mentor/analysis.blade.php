<x-paneluser::layouts.master :title="'Análise do Mentor Vertex'">
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ url()->previous() !== route('user.vertex-bot.analysis') ? url()->previous() : route('paneluser.index') }}"
           class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">
            <i class="fa-pro fa-solid fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 shadow-sm overflow-hidden">
        <div class="p-6 bg-gradient-to-br from-slate-900 to-slate-800 dark:from-slate-800 dark:to-slate-900 border-b border-slate-700/50">
            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-300 dark:text-indigo-400">Mentor Vertex</span>
            <h1 class="mt-1 text-xl font-bold text-white dark:text-slate-100">Análise detalhada</h1>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-[#2563eb] to-[#1e3a8a] shadow-lg border border-indigo-400/20">
                    <x-icon name="robot" style="solid" class="w-6 h-6 text-white" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[15px] leading-relaxed text-slate-700 dark:text-slate-300">{{ $insight['content'] }}</p>
                </div>
            </div>
            @if(isset($insight['trigger']) && $insight['trigger'] !== 'daily_tip')
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Contexto: {{ match($insight['trigger'] ?? '') {
                        'low_balance' => 'Reserva de emergência',
                        'budget_reached' => 'Orçamento',
                        'savings_milestone' => 'Poupança',
                        default => 'Dica do Mentor'
                    } }}
                </p>
            @endif
            @if($financialScore > 0)
                <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Seu indicador financeiro atual: <strong class="text-slate-800 dark:text-slate-200">{{ $financialScore }}/100</strong>
                    </p>
                </div>
            @endif
        </div>
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
            <a href="{{ route('paneluser.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                Ir para o painel
            </a>
        </div>
    </div>
</div>
</x-paneluser::layouts.master>
