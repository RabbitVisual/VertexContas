@extends('paneluser::wizard.layout')

@section('content')
<div class="w-full text-center space-y-8 animate-in fade-in duration-500">
    <div class="w-20 h-20 mx-auto rounded-2xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
        <x-logo class="w-12 h-12 text-primary-600 dark:text-primary-400" />
    </div>
    <div>
        <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
            Bem-vindo ao Vertex Contas
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed mb-6">
            Vamos configurar seu planejamento em poucos passos. Assim você usa o sistema com clareza desde o início.
        </p>
        <ul class="text-left max-w-sm mx-auto space-y-2 text-gray-700 dark:text-gray-300 font-medium" role="list">
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-primary-500 dark:text-primary-400 shrink-0" />
                <span>Renda mensal (para calcular sua capacidade)</span>
            </li>
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-primary-500 dark:text-primary-400 shrink-0" />
                <span>Primeira conta (saldo e tipo)</span>
            </li>
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-primary-500 dark:text-primary-400 shrink-0" />
                <span>Orçamento por categoria (opcional)</span>
            </li>
            <li class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <x-icon name="circle-info" style="solid" class="w-5 h-5 shrink-0" />
                <span>Despesas fixas (opcional, em Minha Renda depois)</span>
            </li>
        </ul>
    </div>
    <a href="{{ route('paneluser.wizard.show', ['step' => 1]) }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-bold text-lg shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
        Começar
        <x-icon name="arrow-right" style="solid" class="w-5 h-5" />
    </a>
</div>
@endsection
