<x-paneluser::layouts.master>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="pen" style="solid" class="text-primary" /> Editar Orçamento
            </h2>
            <a href="{{ route('core.dashboard') }}"
               class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                <x-icon name="arrow-left" style="solid" /> Voltar
            </a>
        </div>
    </div>

    <div class="py-12 font-['Poppins']" x-data="{
        limitAmount: '{{ number_format($budget->limit_amount, 2, ',', '.') }}',
        formatCurrency() {
            let value = this.limitAmount.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            this.limitAmount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-xl border border-slate-100 dark:border-slate-700">
                <form action="{{ route('core.budgets.update', $budget) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Categoria *</label>
                        <select name="category_id" id="category_id" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                            <option value="">Selecione a categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $budget->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="limit_amount" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Limite de Gastos *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xl">R$</span>
                            <input type="text"
                                   x-model="limitAmount"
                                   @input="formatCurrency()"
                                   class="w-full pl-14 pr-4 py-4 text-2xl font-black rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="0,00"
                                   required>
                            <input type="hidden" name="limit_amount" :value="limitAmount.replace(/\./g, '').replace(',', '.')">
                        </div>
                        @error('limit_amount')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label for="period" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Período *</label>
                        <select name="period" id="period" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                            <option value="monthly" {{ old('period', $budget->period) == 'monthly' ? 'selected' : '' }}>Mensal</option>
                            <option value="yearly" {{ old('period', $budget->period) == 'yearly' ? 'selected' : '' }}>Anual</option>
                        </select>
                        @error('period')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                            <x-icon name="check" style="solid" /> Atualizar Orçamento
                        </button>
                        <a href="{{ route('core.dashboard') }}" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                            <x-icon name="times" style="solid" /> Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-paneluser::layouts.master>
