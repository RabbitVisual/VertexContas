@extends('paneluser::wizard.layout')

@section('content')
<div class="w-full text-center space-y-8 animate-in fade-in duration-500">
    <div class="w-20 h-20 mx-auto rounded-2xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center">
        <x-logo class="w-12 h-12 text-primary-600 dark:text-primary-400" />
    </div>
    <div>
        <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
            Bem-vindo ao seu novo jeito de olhar para o dinheiro
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed mb-4">
            Aqui você não precisa amar planilhas, fórmulas nem termos difíceis. O Vertex Contas foi pensado para pessoas reais, com uma rotina cheia e pouco tempo.
        </p>
        <p class="text-base text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed mb-6">
            A partir de agora, vamos usar a <strong>Regra 50/30/20</strong> como uma espécie de bússola mágica: ela divide sua renda em
            <strong>Necessidades</strong>, <strong>Desejos</strong> e <strong>Metas de Futuro</strong>. Você só precisa contar um pouco sobre sua vida; o Vertex traduz os números em próximos passos simples.
        </p>
        <ul class="text-left max-w-sm mx-auto space-y-2 text-gray-700 dark:text-gray-300 font-medium" role="list">
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-primary-500 dark:text-primary-400 shrink-0" />
                <span>Sua renda mensal (para desenhar sua bússola 50/30/20)</span>
            </li>
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-primary-500 dark:text-primary-400 shrink-0" />
                <span>Sua primeira conta principal (onde o dinheiro entra)</span>
            </li>
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-primary-500 dark:text-primary-400 shrink-0" />
                <span>Um rascunho de orçamentos por categoria (se você quiser)</span>
            </li>
            <li class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <x-icon name="circle-info" style="solid" class="w-5 h-5 shrink-0" />
                <span>Despesas fixas mais detalhadas você pode ajustar depois, com calma, em Minha Renda.</span>
            </li>
        </ul>
    </div>
    <a href="{{ route('paneluser.wizard.show', ['step' => 1]) }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-bold text-lg shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
        Começar
        <x-icon name="arrow-right" style="solid" class="w-5 h-5" />
    </a>
</div>
@endsection
