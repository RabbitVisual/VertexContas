<x-paneluser::layouts.master :title="'Nova Conta'">
    <div class="max-w-2xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                        <span>Financeiro</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">Nova conta</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Nova <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Conta</span></h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Informe o nome, tipo e saldo inicial. O nome aparece ao escolher a conta no Extrato.</p>
                </div>
                <a href="{{ route('core.accounts.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                    <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                    Voltar
                </a>
            </div>
        </div>

        {{-- Dica --}}
        <div class="rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-5 shadow-sm">
            <div class="flex gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <x-icon name="circle-info" style="duotone" class="w-4 h-4" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-0.5">Dica</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Use um nome que você reconheça (ex: Nubank, Carteira João). O saldo inicial deve refletir o valor real desta conta hoje.</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-900/50">
                <h2 class="font-bold text-gray-900 dark:text-white">Dados da conta</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Preencha os campos para criar sua conta</p>
            </div>
            <form action="{{ route('core.accounts.store') }}" method="POST" class="p-6 lg:p-8 space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Nome da conta *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-3 focus:border-emerald-500 focus:ring-0 outline-none transition-colors @error('name') border-red-500 @enderror"
                           placeholder="Ex: Nubank, Cartão Pessoal, Carteira" required>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Esse nome será exibido ao escolher a conta nas transações</p>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Tipo *</label>
                    <select name="type" id="type" required
                            class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-3 focus:border-emerald-500 outline-none transition-colors @error('type') border-red-500 @enderror">
                        <option value="">Selecione</option>
                        <option value="checking" {{ old('type') === 'checking' ? 'selected' : '' }}>Conta corrente</option>
                        <option value="savings" {{ old('type') === 'savings' ? 'selected' : '' }}>Poupança</option>
                        <option value="cash" {{ old('type') === 'cash' ? 'selected' : '' }}>Dinheiro em espécie</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Saldo inicial *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">R$</span>
                        <input type="number" name="balance" id="balance" value="{{ old('balance', '0') }}" step="0.01" min="0"
                               class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white pl-12 pr-4 py-3 font-mono tabular-nums focus:border-emerald-500 outline-none transition-colors @error('balance') border-red-500 @enderror"
                               placeholder="0,00" required>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Informe o saldo atual desta conta</p>
                    @error('balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                    <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center justify-center gap-2 py-3 px-5 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                        <x-icon name="check" style="solid" class="w-5 h-5" />
                        Criar conta
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
