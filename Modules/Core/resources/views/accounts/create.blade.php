<x-paneluser::layouts.master :title="'Nova Conta'">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <div class="p-2 rounded-lg bg-primary/10 dark:bg-primary/20">
                    <x-icon name="plus" style="duotone" class="w-6 h-6 text-primary-500" />
                </div>
                Nova Conta
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-12">O nome que você digitar aparecerá no cartão</p>
        </div>
        <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors">
            <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
            Voltar
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('core.accounts.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Nome no cartão *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 @error('name') border-red-500 dark:border-red-500 @enderror"
                           placeholder="Ex: Nubank, Cartão Pessoal, Carteira João"
                           required>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Esse nome será exibido no cartão da conta</p>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Tipo *</label>
                    <select name="type" id="type" required
                            class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 @error('type') border-red-500 @enderror">
                        <option value="">Selecione</option>
                        <option value="checking" {{ old('type') === 'checking' ? 'selected' : '' }}>Conta Corrente</option>
                        <option value="savings" {{ old('type') === 'savings' ? 'selected' : '' }}>Poupança</option>
                        <option value="cash" {{ old('type') === 'cash' ? 'selected' : '' }}>Dinheiro em espécie</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Saldo inicial *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">R$</span>
                        <input type="number" name="balance" id="balance" value="{{ old('balance', '0.00') }}" step="0.01" min="0"
                               class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-4 p-3 @error('balance') border-red-500 @enderror"
                               placeholder="0,00" required>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Informe o saldo atual desta conta</p>
                    @error('balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-md transition-colors">
                        <x-icon name="check" style="solid" class="w-5 h-5" />
                        Criar conta
                    </button>
                    <a href="{{ route('core.accounts.index') }}" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
