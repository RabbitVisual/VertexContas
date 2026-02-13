<x-paneluser::layouts.master>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="plus-circle" style="solid" class="text-primary" /> Nova Conta
            </h2>
            <a href="{{ route('core.accounts.index') }}"
               class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                <x-icon name="arrow-left" style="solid" /> Voltar
            </a>
        </div>
    </div>

    <div class="py-12 font-['Poppins']">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-xl border border-slate-100 dark:border-slate-700">
                <form action="{{ route('core.accounts.store') }}" method="POST">
                    @csrf

                    {{-- Name Field --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            Nome da Conta *
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all @error('name') border-red-500 @enderror"
                               placeholder="Ex: Banco Inter, Nubank, Carteira"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type Field --}}
                    <div class="mb-6">
                        <label for="type" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            Tipo de Conta *
                        </label>
                        <select name="type"
                                id="type"
                                class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Selecione o tipo</option>
                            <option value="checking" {{ old('type') === 'checking' ? 'selected' : '' }}>
                                <x-icon name="credit-card" style="solid" /> Conta Corrente
                            </option>
                            <option value="savings" {{ old('type') === 'savings' ? 'selected' : '' }}>
                                <x-icon name="piggy-bank" style="solid" /> Poupança
                            </option>
                            <option value="cash" {{ old('type') === 'cash' ? 'selected' : '' }}>
                                <x-icon name="money-bill-wave" style="solid" /> Dinheiro
                            </option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Balance Field --}}
                    <div class="mb-8">
                        <label for="balance" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            Saldo Inicial *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">R$</span>
                            <input type="number"
                                   name="balance"
                                   id="balance"
                                   value="{{ old('balance', '0.00') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all @error('balance') border-red-500 @enderror"
                                   placeholder="0,00"
                                   required>
                        </div>
                        @error('balance')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-500 mt-2">Informe o saldo atual desta conta</p>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                            <x-icon name="check" style="solid" /> Criar Conta
                        </button>
                        <a href="{{ route('core.accounts.index') }}"
                           class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                            <x-icon name="times" style="solid" /> Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-paneluser::layouts.master>
