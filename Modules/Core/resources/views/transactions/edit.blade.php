<x-paneluser::layouts.master>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="pen" style="solid" class="text-primary" /> Editar Transação
            </h2>
            <a href="{{ route('core.transactions.index') }}"
               class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                <x-icon name="arrow-left" style="solid" /> Voltar
            </a>
        </div>
    </div>

    <div class="py-12 font-['Poppins']" x-data="{
        type: '{{ old('type', $transaction->type) }}',
        amount: '{{ number_format($transaction->amount, 2, ',', '.') }}',
        formatCurrency() {
            let value = this.amount.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            this.amount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-xl border border-slate-100 dark:border-slate-700">
                <form action="{{ route('core.transactions.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Type Toggle --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Tipo *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button"
                                    @click="type = 'income'"
                                    :class="type === 'income' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'"
                                    class="px-6 py-4 rounded-xl font-bold transition-all transform hover:scale-105">
                                <x-icon name="arrow-up" style="solid" class="text-2xl mb-2" />
                                <p>Receita</p>
                            </button>
                            <button type="button"
                                    @click="type = 'expense'"
                                    :class="type === 'expense' ? 'bg-red-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'"
                                    class="px-6 py-4 rounded-xl font-bold transition-all transform hover:scale-105">
                                <x-icon name="arrow-down" style="solid" class="text-2xl mb-2" />
                                <p>Despesa</p>
                            </button>
                        </div>
                        <input type="hidden" name="type" :value="type">
                    </div>

                    {{-- Amount --}}
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Valor *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xl">R$</span>
                            <input type="text"
                                   x-model="amount"
                                   @input="formatCurrency()"
                                   class="w-full pl-14 pr-4 py-4 text-2xl font-black rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="0,00"
                                   required>
                            <input type="hidden" name="amount" :value="amount.replace(/\./g, '').replace(',', '.')">
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Account --}}
                    <div class="mb-6">
                        <label for="account_id" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Conta *</label>
                        <select name="account_id" id="account_id" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                            <option value="">Selecione a conta</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} (R$ {{ number_format($account->balance, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('account_id')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Categoria *</label>
                        <select name="category_id" id="category_id" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                            <option value="">Selecione a categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date --}}
                    <div class="mb-6">
                        <label for="date" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Data *</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                        @error('date')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Descrição</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Ex: Compra no supermercado">{{ old('description', $transaction->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-8">
                        <label for="status" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Status *</label>
                        <select name="status" id="status" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                            <option value="completed" {{ old('status', $transaction->status) == 'completed' ? 'selected' : '' }}>Concluída</option>
                            <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Pendente</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                            <x-icon name="check" style="solid" /> Atualizar Transação
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
