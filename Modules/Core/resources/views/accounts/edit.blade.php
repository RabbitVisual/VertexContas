<x-paneluser::layouts.master :title="'Editar ' . $account->name">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-primary/10 dark:bg-primary/15 border border-primary/20 dark:border-primary/30">
                    <x-icon name="pencil" style="duotone" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                Editar conta
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 ml-14">{{ $account->name }}</p>
        </div>
        <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-medium transition-colors">
            <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
            Voltar
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                <h3 class="font-semibold text-slate-900 dark:text-white">Dados da conta</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Edite os campos conforme necessário</p>
            </div>
            <form action="{{ route('core.accounts.update', $account) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Nome no cartão *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}"
                           class="bg-slate-50 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full p-3.5 transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="Ex: Nubank, Cartão Pessoal"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Tipo *</label>
                    <select name="type" id="type" required
                            class="bg-slate-50 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full p-3.5 transition-colors @error('type') border-red-500 @enderror">
                        <option value="checking" {{ old('type', $account->type) === 'checking' ? 'selected' : '' }}>Conta Corrente</option>
                        <option value="savings" {{ old('type', $account->type) === 'savings' ? 'selected' : '' }}>Poupança</option>
                        <option value="cash" {{ old('type', $account->type) === 'cash' ? 'selected' : '' }}>Dinheiro em espécie</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Saldo atual *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 font-medium">R$</span>
                        <input type="number" name="balance" id="balance" value="{{ old('balance', $account->balance) }}" step="0.01"
                               class="bg-slate-50 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-4 p-3.5 font-mono tabular-nums transition-colors @error('balance') border-red-500 @enderror"
                               required>
                    </div>
                    <p class="mt-1.5 text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1">
                        <x-icon name="triangle-exclamation" style="solid" class="w-4 h-4 shrink-0" />
                        Alterar o saldo manualmente pode desconfigurar o controle de transações
                    </p>
                    @error('balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-semibold rounded-xl shadow-sm transition-colors">
                        <x-icon name="check" style="solid" class="w-5 h-5" />
                        Salvar alterações
                    </button>
                    <a href="{{ route('core.accounts.index') }}" class="px-5 py-3 bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-200 font-medium rounded-xl transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
