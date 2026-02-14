<x-paneluser::layouts.master :title="'Editar ' . $account->name">
    <div class="space-y-8 pb-8">
        {{-- Hero Header - CBAV style --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-primary-600/20 to-transparent"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/20 border border-amber-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="pencil" style="duotone" class="w-4 h-4 text-amber-300" />
                        <span class="text-amber-200 text-xs font-black uppercase tracking-[0.2em]">Editar</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Editar conta</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base md:text-lg leading-relaxed">{{ $account->name }}</p>
                </div>
                <a href="{{ route('core.accounts.index') }}" class="shrink-0 inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold hover:bg-white/20 transition-colors">
                    <x-icon name="arrow-left" style="duotone" class="w-5 h-5 text-slate-200" />
                    Voltar
                </a>
            </div>
        </div>

        <div class="max-w-2xl">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white">Dados da conta</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Edite os campos conforme necessário</p>
                </div>
                <form action="{{ route('core.accounts.update', $account) }}" method="POST" class="p-6 lg:p-8 space-y-6">
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
    </div>
</x-paneluser::layouts.master>
