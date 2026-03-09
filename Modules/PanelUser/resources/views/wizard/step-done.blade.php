@extends('paneluser::wizard.layout')

@section('content')
<div class="w-full text-center space-y-8 animate-in fade-in duration-500">
    <div class="w-20 h-20 mx-auto rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center">
        <x-icon name="circle-check" style="solid" class="w-12 h-12 text-emerald-600 dark:text-emerald-400" />
    </div>
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-4">Tudo pronto</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed mb-6">
            Você configurou o essencial para começar. No painel você verá o saldo total, receitas e despesas do mês e a capacidade mensal.
        </p>
        <ul class="text-left max-w-sm mx-auto space-y-2 text-gray-700 dark:text-gray-300 font-medium mb-8" role="list">
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-emerald-500 dark:text-emerald-400 shrink-0" />
                <span>Objetivo definido</span>
            </li>
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-emerald-500 dark:text-emerald-400 shrink-0" />
                <span>Conta &quot;Minha Conta&quot; com saldo cadastrada</span>
            </li>
            <li class="flex items-center gap-2">
                <x-icon name="circle-check" style="solid" class="w-5 h-5 text-emerald-500 dark:text-emerald-400 shrink-0" />
                <span>Renda principal cadastrada</span>
            </li>
        </ul>
        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
            Use o <strong>Extrato</strong> para lançar transações, <strong>Metas</strong> para objetivos de economia e <strong>Minha Renda</strong> para ajustar receitas e despesas fixas.
        </p>

        @if(isset($isPro) && $isPro)
            {{-- Usuário já é Pro: só concluir --}}
            @if(request()->query('payment') === 'success')
                <div class="max-w-md mx-auto mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50">
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Assinatura concluída.</p>
                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">Você agora é <strong>{{ plan_pro_name() }}</strong>. Clique em &quot;Ir ao painel&quot; para continuar.</p>
                </div>
            @else
                <p class="text-sm text-amber-700 dark:text-amber-300 max-w-md mx-auto mb-8">
                    Você está no <strong>{{ plan_pro_name() }}</strong>. Aproveite relatórios, mais contas e suporte prioritário no painel.
                </p>
            @endif
        @elseif(Route::has('user.subscription.index'))
            {{-- Oferta guiada: assinar agora (com retorno ao wizard) ou continuar com plano gratuito --}}
            @php $wizardReturnUrl = route('paneluser.wizard.show', ['step' => 3]); @endphp
            <div class="max-w-md mx-auto mb-8 rounded-2xl border border-amber-200 dark:border-amber-800/50 bg-amber-50/30 dark:bg-amber-950/20 p-6 text-left">
                <p class="text-sm font-bold text-gray-900 dark:text-white mb-2">Quer mais recursos?</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Com o {{ plan_pro_name() }} você tem relatórios avançados, várias contas e fontes de renda, metas ilimitadas e suporte prioritário. Você pode assinar agora e voltará aqui para concluir a configuração.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('user.subscription.index', ['return' => $wizardReturnUrl]) }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-white font-bold text-sm shadow-lg shadow-amber-500/25 transition-all focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        <x-icon name="crown" style="solid" class="w-4 h-4" />
                        Assinar {{ plan_pro_name() }}
                    </a>
                    <span class="text-xs text-gray-500 dark:text-gray-400 self-center sm:self-auto">ou</span>
                    <form action="{{ route('paneluser.wizard.complete') }}" method="POST" class="inline" x-data="{ loading: false }" @submit="loading = true">
                        @csrf
                        <button type="submit" :disabled="loading" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium text-sm transition-colors disabled:opacity-70">
                            <span x-show="!loading">Continuar com {{ plan_free_name() }} e ir ao painel</span>
                            <span x-show="loading" x-cloak class="inline-flex items-center gap-2"><span class="inline-block w-4 h-4 border-2 border-gray-400 border-t-transparent rounded-full animate-spin"></span> Redirecionando...</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <form action="{{ route('paneluser.wizard.complete') }}" method="POST" class="inline" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <button type="submit" :disabled="loading" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition-colors disabled:opacity-70">
                    <span x-show="!loading">Ir ao painel</span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-2"><span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span> Redirecionando...</span>
                </button>
            </form>
        @endif
    </div>

    @if(isset($isPro) && $isPro)
    <form action="{{ route('paneluser.wizard.complete') }}" method="POST" class="inline" x-data="{ loading: false }" @submit="loading = true">
        @csrf
        <button type="submit" :disabled="loading" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-bold text-lg shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-70 disabled:pointer-events-none">
            <span x-show="!loading" class="inline-flex items-center gap-2">Ir ao painel <x-icon name="arrow-right" style="solid" class="w-5 h-5" /></span>
            <span x-show="loading" x-cloak class="inline-flex items-center gap-2">
                <span class="inline-block w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" aria-hidden="true"></span>
                Redirecionando...
            </span>
        </button>
    </form>
    @endif
</div>
@endsection
