<x-paneluser::layouts.master>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="pen" style="solid" class="text-primary" /> Editar Meta
            </h2>
            <a href="{{ route('core.goals.index') }}"
               class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                <x-icon name="arrow-left" style="solid" /> Voltar
            </a>
        </div>
    </div>

    <div class="py-12 font-['Poppins']" x-data="{
        targetAmount: '{{ number_format($goal->target_amount, 2, ',', '.') }}',
        currentAmount: '{{ number_format($goal->current_amount, 2, ',', '.') }}',
        formatCurrency(field) {
            let value = this[field].replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            this[field] = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-xl border border-slate-100 dark:border-slate-700">
                <form action="{{ route('core.goals.update', $goal) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nome da Meta *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $goal->name) }}" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Ex: Viagem para Europa, Carro Novo" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="target_amount" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Valor da Meta *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xl">R$</span>
                            <input type="text"
                                   x-model="targetAmount"
                                   @input="formatCurrency('targetAmount')"
                                   class="w-full pl-14 pr-4 py-4 text-2xl font-black rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="0,00"
                                   required>
                            <input type="hidden" name="target_amount" :value="targetAmount.replace(/\./g, '').replace(',', '.')">
                        </div>
                        @error('target_amount')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="current_amount" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Valor Atual</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">R$</span>
                            <input type="text"
                                   x-model="currentAmount"
                                   @input="formatCurrency('currentAmount')"
                                   class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="0,00">
                            <input type="hidden" name="current_amount" :value="currentAmount.replace(/\./g, '').replace(',', '.')">
                        </div>
                        @error('current_amount')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label for="deadline" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Prazo (Opcional)</label>
                        <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $goal->deadline?->format('Y-m-d')) }}" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('deadline')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                            <x-icon name="check" style="solid" /> Atualizar Meta
                        </button>
                        <a href="{{ route('core.goals.index') }}" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                            <x-icon name="times" style="solid" /> Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-paneluser::layouts.master>
