<x-paneluser::layouts.master :title="'Transferência'">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="arrow-right-arrow-left" style="solid" class="text-purple-600" /> Transferir Entre Contas
            </h2>
            <a href="{{ route('core.transactions.index') }}"
               class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                <x-icon name="arrow-left" style="solid" /> Voltar
            </a>
        </div>
    </div>

    <div class="py-12 font-['Poppins']" x-data="{
        amount: '',
        formatCurrency() {
            let value = this.amount.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            this.amount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                    <x-icon name="exclamation-circle" style="solid" /> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-xl border border-slate-100 dark:border-slate-700">
                <form action="{{ route('core.transactions.process-transfer') }}" method="POST">
                    @csrf

                    {{-- Visual Transfer Flow --}}
                    <div class="mb-8 flex items-center justify-center gap-4">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-2">
                                <x-icon name="arrow-left" style="solid" class="text-red-600 text-2xl" />
                            </div>
                            <p class="text-sm font-bold text-slate-600">Origem</p>
                        </div>
                        <x-icon name="arrow-right" style="solid" class="text-4xl text-purple-600" />
                        <div class="text-center">
                            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-2">
                                <x-icon name="arrow-right" style="solid" class="text-emerald-600 text-2xl" />
                            </div>
                            <p class="text-sm font-bold text-slate-600">Destino</p>
                        </div>
                    </div>

                    {{-- From Account --}}
                    <div class="mb-6">
                        <label for="from_account_id" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            <x-icon name="arrow-left" style="solid" class="text-red-600" /> Conta de Origem *
                        </label>
                        <select name="from_account_id" id="from_account_id" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                            <option value="">Selecione a conta de origem</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} (<span class="sensitive-value">R$ {{ number_format($account->balance, 2, ',', '.') }}</span>)
                                </option>
                            @endforeach
                        </select>
                        @error('from_account_id')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- To Account --}}
                    <div class="mb-6">
                        <label for="to_account_id" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                            <x-icon name="arrow-right" style="solid" class="text-emerald-600" /> Conta de Destino *
                        </label>
                        <select name="to_account_id" id="to_account_id" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                            <option value="">Selecione a conta de destino</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} (<span class="sensitive-value">R$ {{ number_format($account->balance, 2, ',', '.') }}</span>)
                                </option>
                            @endforeach
                        </select>
                        @error('to_account_id')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Valor da Transferência *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xl">R$</span>
                            <input type="text"
                                   x-model="amount"
                                   @input="formatCurrency()"
                                   class="w-full pl-14 pr-4 py-4 text-2xl font-black rounded-lg border-2 border-purple-200 dark:border-purple-600 dark:bg-slate-700 dark:text-white focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 transition-all"
                                   placeholder="0,00"
                                   required>
                            <input type="hidden" name="amount" :value="amount.replace(/\./g, '').replace(',', '.')">
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Descrição (Opcional)</label>
                        <textarea name="description" id="description" rows="2" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Ex: Transferência para poupança">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category (Optional) --}}
                    <div class="mb-8">
                        <label for="category_id" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Categoria (Opcional)</label>
                        <select name="category_id" id="category_id" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="">Nenhuma</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-purple-600/25 transform hover:-translate-y-0.5 transition-all">
                            <x-icon name="arrow-right-arrow-left" style="solid" /> Realizar Transferência
                        </button>
                        <a href="{{ route('core.transactions.index') }}" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                            <x-icon name="times" style="solid" /> Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-paneluser::layouts.master>
