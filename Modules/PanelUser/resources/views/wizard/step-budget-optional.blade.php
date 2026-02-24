@extends('paneluser::wizard.layout')

@section('content')
<div class="w-full space-y-8 animate-in fade-in duration-500 text-center">
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Quer definir um orçamento?</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">Orçamentos ajudam a limitar gastos por categoria (alimentação, lazer, etc.). Definir um limite por categoria ajuda a não estourar o orçamento.</p>
    </div>

    <div class="rounded-xl border border-primary-200 dark:border-primary-800/50 bg-primary-50/50 dark:bg-primary-950/30 p-4 mb-6 max-w-md mx-auto text-left">
        <p class="text-sm text-primary-800 dark:text-primary-200">
            <strong>Dica:</strong> Você pode pular e criar depois em <strong>Financeiro &rarr; Orçamentos</strong>. Se criar agora, ao concluir poderá voltar ao painel ou finalizar a configuração aqui.
        </p>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        @if(Route::has('core.budgets.create'))
            <a href="{{ route('core.budgets.create', ['return' => route('paneluser.wizard.show', ['step' => 4])]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                Sim, criar orçamento
            </a>
        @endif
        <form action="{{ route('paneluser.wizard.skip-budget') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors">
                Agora não
                <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
            </button>
        </form>
    </div>
    <div class="pt-6">
        <a href="{{ route('paneluser.wizard.show', ['step' => 2]) }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center gap-1">
            <x-icon name="arrow-left" style="solid" class="w-3 h-3" />
            Voltar
        </a>
    </div>
</div>
@endsection
