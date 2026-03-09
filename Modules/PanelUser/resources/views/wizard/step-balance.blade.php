@extends('paneluser::wizard.layout')

@section('content')
<div class="w-full space-y-6 animate-in fade-in duration-500">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Quanto você tem na conta principal ou carteira hoje?</h1>
        <p class="text-gray-600 dark:text-gray-400">Criaremos uma conta &quot;Minha Conta&quot; com esse saldo para você começar.</p>
    </div>

    <div class="rounded-xl border border-primary-200 dark:border-primary-800/50 bg-primary-50/50 dark:bg-primary-950/30 p-4 mb-6">
        <p class="text-sm text-primary-800 dark:text-primary-200">
            <strong>Dica:</strong> Informe o valor que você tem disponível hoje. Você pode adicionar outras contas depois no painel.
        </p>
    </div>

    <form action="{{ route('paneluser.wizard.account.store') }}" method="POST" class="rounded-3xl border border-gray-200 dark:border-white/5 bg-white dark:bg-gray-950 p-6 sm:p-8 shadow-sm">
        @csrf
        <div>
            <label for="balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saldo atual (R$)</label>
            <div class="relative max-w-xs mx-auto">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">R$</span>
                <input type="number" name="balance" id="balance" value="{{ old('balance', '0') }}" step="0.01" min="0" required inputmode="decimal"
                    class="w-full pl-12 pr-4 py-4 text-xl font-bold rounded-2xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('balance') border-red-500 dark:border-red-500 @enderror">
            </div>
            @error('balance')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400 text-center" role="alert">{{ $message }}</p>
            @enderror
            @error('limit')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400 text-center" role="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="mt-6 flex flex-wrap gap-4 justify-center">
            <a href="{{ route('paneluser.wizard.show', ['step' => 0]) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Próximo
                <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
            </button>
        </div>
    </form>
</div>
@endsection
