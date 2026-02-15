<x-paneluser::layouts.master :title="'Editar ' . $account->name">
    <div class="max-w-2xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-600/5 dark:bg-amber-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-4">
                        <span>Financeiro</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">Editar conta</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Editar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-teal-600 dark:from-amber-400 dark:to-teal-400">conta</span></h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">{{ $account->name }}</p>
                </div>
                <a href="{{ route('core.accounts.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                    <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                    Voltar
                </a>
            </div>
        </div>

        <div class="rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-5 shadow-sm">
            <div class="flex gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                    <x-icon name="triangle-exclamation" style="duotone" class="w-4 h-4" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-0.5">Atenção</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Alterar o saldo manualmente pode desenquadrar o controle com as transações. Prefira registrar entradas e saídas pelo <a href="{{ route('core.transactions.index') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">Extrato</a>.</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-900/50">
                <h2 class="font-bold text-gray-900 dark:text-white">Dados da conta</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Edite os campos conforme necessário</p>
            </div>
            <form action="{{ route('core.accounts.update', $account) }}" method="POST" class="p-6 lg:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Nome da conta *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}"
                           class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-3 focus:border-emerald-500 outline-none transition-colors @error('name') border-red-500 @enderror"
                           placeholder="Ex: Nubank, Cartão Pessoal" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Tipo *</label>
                    <select name="type" id="type" required
                            class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-3 focus:border-emerald-500 outline-none transition-colors @error('type') border-red-500 @enderror">
                        <option value="checking" {{ old('type', $account->type) === 'checking' ? 'selected' : '' }}>Conta corrente</option>
                        <option value="savings" {{ old('type', $account->type) === 'savings' ? 'selected' : '' }}>Poupança</option>
                        <option value="cash" {{ old('type', $account->type) === 'cash' ? 'selected' : '' }}>Dinheiro em espécie</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Saldo atual *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">R$</span>
                        @if(\Modules\Core\Services\InspectionGuard::shouldHideFinancialData())
                            <input type="hidden" name="balance" value="0">
                            <input type="text" id="balance" value="••••••••" placeholder="Oculto"
                                   class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 pl-12 pr-4 py-3 font-mono tabular-nums cursor-not-allowed"
                                   readonly disabled>
                            <p class="mt-1 text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                <x-icon name="lock" style="solid" class="w-3.5 h-3.5" /> Oculto por privacidade durante a inspeção
                            </p>
                        @else
                            <input type="number" name="balance" id="balance" value="{{ old('balance', $account->balance) }}" step="0.01"
                                   class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white pl-12 pr-4 py-3 font-mono tabular-nums focus:border-emerald-500 outline-none transition-colors @error('balance') border-red-500 @enderror"
                                   required>
                        @endif
                    </div>
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
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
