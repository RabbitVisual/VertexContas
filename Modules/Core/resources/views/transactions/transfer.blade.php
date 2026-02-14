<x-paneluser::layouts.master :title="'Transferência entre Contas'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500" x-data="{
    amount: '',
    formatCurrency() {
        var value = this.amount.replace(/\D/g, '');
        if (value === '') { this.amount = ''; return; }
        value = (parseInt(value) / 100).toFixed(2);
        this.amount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }
}">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-slate-600/5 dark:bg-slate-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <a href="{{ route('core.transactions.index') }}" class="hover:underline">Extrato</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Transferência</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Transferir <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">entre Contas</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Mova saldo de uma conta para outra. Não altera Minha Renda nem a capacidade mensal — apenas a liquidez em cada conta.</p>
            </div>

            <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors shrink-0">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar ao extrato
            </a>
        </div>
    </div>

    <form action="{{ route('core.transactions.processTransfer') }}" method="POST">
        @csrf

        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="p-8 sm:p-10 space-y-10">
                {{-- Origem e Destino --}}
                <div class="grid grid-cols-1 md:grid-cols-12 items-end gap-6">
                    <div class="md:col-span-5 space-y-3">
                        <label for="from_account_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Conta de origem (de onde sai)</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                            </div>
                            <select name="from_account_id" id="from_account_id" class="w-full pl-12 pr-10 py-4 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-rose-500 font-medium text-gray-800 dark:text-gray-200 appearance-none" required>
                                <option value="">Selecione</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="chevron-down" style="solid" class="w-4 h-4" />
                            </div>
                        </div>
                        @error('from_account_id')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2 flex justify-center pb-2">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 shrink-0">
                            <x-icon name="right-left" style="solid" class="w-6 h-6" />
                        </div>
                    </div>

                    <div class="md:col-span-5 space-y-3">
                        <label for="to_account_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Conta de destino (para onde vai)</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="wallet" style="duotone" class="w-5 h-5" />
                            </div>
                            <select name="to_account_id" id="to_account_id" class="w-full pl-12 pr-10 py-4 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium text-gray-800 dark:text-gray-200 appearance-none" required>
                                <option value="">Selecione</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="chevron-down" style="solid" class="w-4 h-4" />
                            </div>
                        </div>
                        @error('to_account_id')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Valor --}}
                <div class="max-w-md">
                    <label for="amount" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Valor da transferência (R$)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xl font-bold">R$</span>
                        <input type="text" id="amount" x-model="amount" @input="formatCurrency()" placeholder="0,00"
                               class="w-full pl-14 pr-5 py-4 text-2xl font-black rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none text-gray-900 dark:text-white tabular-nums" required>
                        <input type="hidden" name="amount" :value="amount.replace(/\./g, '').replace(',', '.')">
                    </div>
                    @error('amount')<p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Data e descrição --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Data</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="calendar" style="duotone" class="w-5 h-5" />
                            </div>
                            <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="w-full pl-12 pr-4 py-3 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium" required>
                        </div>
                    </div>
                    <div>
                        <label for="description" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Descrição (opcional)</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="comment-dots" style="duotone" class="w-5 h-5" />
                            </div>
                            <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Ex: Ajuste para reserva" class="w-full pl-12 pr-4 py-3 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium placeholder-gray-400">
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 sm:p-10 py-6 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-white/5 flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                    <x-icon name="right-left" style="solid" class="w-5 h-5" />
                    Confirmar transferência
                </button>
                <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-medium rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                    Cancelar
                </a>
            </div>
        </div>
    </form>

    {{-- Dica --}}
    <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">Como funciona a Transferência no Vertex Contas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">A transferência <strong>só move o saldo</strong> da conta de origem para a de destino. Nada é criado nem destruído: o total das suas contas continua o mesmo. Sua <strong>Minha Renda</strong> e sua <strong>capacidade mensal</strong> não mudam — elas vêm do planejamento. Use transferências para organizar a liquidez entre conta corrente, poupança, investimentos etc.</p>
            </div>
        </div>
    </div>
</div>
</x-paneluser::layouts.master>
