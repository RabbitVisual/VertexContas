@extends('paneluser::wizard.layout')

@section('content')
<div class="w-full space-y-6 animate-in fade-in duration-500">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Sua primeira conta</h1>
        <p class="text-gray-600 dark:text-gray-400">Para registrar entradas e saídas no <strong>Extrato</strong> você precisa de pelo menos uma conta (banco, poupança ou dinheiro).</p>
    </div>

    <div class="rounded-xl border border-primary-200 dark:border-primary-800/50 bg-primary-50/50 dark:bg-primary-950/30 p-4 mb-6">
        <p class="text-sm text-primary-800 dark:text-primary-200">
            <strong>Dica:</strong> O saldo inicial deve refletir o que você tem hoje nesta conta. Você pode adicionar mais contas depois em <strong>Financeiro</strong>.
        </p>
    </div>

    <form action="{{ route('paneluser.wizard.account.store') }}" method="POST" class="rounded-3xl border border-gray-200 dark:border-white/5 bg-white dark:bg-gray-950 p-6 sm:p-8 shadow-sm">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome da conta *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ex: Nubank, Carteira" required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 dark:border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo *</label>
                <div class="relative">
                    <select name="type" id="type" required
                        style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-2 focus:ring-primary-500 appearance-none [&::-ms-expand]:hidden @error('type') border-red-500 dark:border-red-500 @enderror">
                        <option value="">Selecione</option>
                        <option value="checking" {{ old('type') === 'checking' ? 'selected' : '' }}>Conta corrente</option>
                        <option value="savings" {{ old('type') === 'savings' ? 'selected' : '' }}>Poupança</option>
                        <option value="cash" {{ old('type') === 'cash' ? 'selected' : '' }}>Dinheiro em espécie</option>
                    </select>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"><x-icon name="chevron-down" style="solid" class="w-4 h-4" /></span>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saldo inicial *</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">R$</span>
                    <input type="number" name="balance" id="balance" value="{{ old('balance', '0') }}" step="0.01" min="0" required inputmode="decimal"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white pl-12 pr-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('balance') border-red-500 dark:border-red-500 @enderror">
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Informe o saldo atual desta conta</p>
                @error('balance')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                @enderror
            </div>
        </div>
        @if($errors->any() && !$errors->has('name') && !$errors->has('type') && !$errors->has('balance'))
            <div class="mt-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mt-6 flex flex-wrap gap-4">
            <a href="{{ route('paneluser.wizard.show', ['step' => 1]) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors">
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
