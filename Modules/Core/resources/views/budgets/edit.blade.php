@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Editar Orçamento'">
    <div class="space-y-8 pb-8" x-data="{
        limitAmount: '{{ number_format($budget->limit_amount, 2, ',', '.') }}',
        alertThreshold: {{ $budget->alert_threshold ?? 80 }},
        allowExceed: {{ $budget->allow_exceed ? 'true' : 'false' }},
        formatCurrency() {
            let value = this.limitAmount.replace(/\D/g, '');
            if (value === '') {
                this.limitAmount = '';
                return;
            }
            value = (parseInt(value) / 100).toFixed(2);
            this.limitAmount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    }">
        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/20 border border-indigo-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="pen-to-square" class="w-4 h-4 text-indigo-300" />
                        <span class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em]">Planejamento Financeiro</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Editar Orçamento</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base leading-relaxed">Ajuste seus limites para manter o equilíbrio financeiro</p>
                </div>
                <a href="{{ route('core.budgets.index') }}" class="shrink-0 inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold transition-all backdrop-blur-md">
                    <x-icon name="arrow-left" class="w-4 h-4 text-white/70" />
                    Voltar
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <form action="{{ route('core.budgets.update', $budget) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-8 lg:p-12 space-y-10">
                        {{-- Category --}}
                        <div class="space-y-3">
                            <label for="category_id" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Para qual categoria? *</label>
                            <div class="relative group opacity-80 cursor-not-allowed">
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <x-icon name="tags" style="solid" />
                                </div>
                                <select name="category_id_disabled" id="category_id_disabled" disabled
                                        class="w-full pl-12 pr-6 py-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 font-bold text-slate-500 dark:text-slate-500 appearance-none">
                                    <option value="{{ $budget->category_id }}">{{ $budget->category->name }}</option>
                                </select>
                                <input type="hidden" name="category_id" value="{{ $budget->category_id }}">
                            </div>
                            <p class="text-[10px] text-slate-400 italic ml-1">A categoria não pode ser alterada após a criação do orçamento.</p>
                        </div>

                        {{-- Limit Amount --}}
                        <div class="space-y-3">
                            <label for="limit_amount_display" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Qual é o novo limite de gastos? *</label>
                            <div class="relative">
                                <div class="absolute left-6 top-1/2 -translate-y-1/2 flex items-center gap-2 pointer-events-none">
                                    <span class="text-xl font-black text-slate-400">R$</span>
                                </div>
                                <input type="text"
                                       id="limit_amount_display"
                                       x-model="limitAmount"
                                       @input="formatCurrency()"
                                       placeholder="0,00"
                                       class="w-full pl-16 pr-6 py-6 text-3xl font-black rounded-3xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none text-slate-900 dark:text-white"
                                       required>
                                <input type="hidden" name="limit_amount" :value="limitAmount.replace(/\./g, '').replace(',', '.')">
                            </div>
                            @error('limit_amount')
                                <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Period --}}
                        <div class="space-y-3">
                            <label for="period" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Periodicidade do controle *</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="period" value="monthly" class="peer sr-only" {{ old('period', $budget->period) == 'monthly' ? 'checked' : '' }}>
                                    <div class="p-4 flex flex-col items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/5 transition-all group-hover:bg-slate-100 dark:group-hover:bg-slate-800">
                                        <x-icon name="calendar-days" class="text-xl text-slate-400 peer-checked:text-indigo-600 mb-2 transition-colors" />
                                        <span class="text-xs font-black uppercase tracking-widest text-slate-500 peer-checked:text-indigo-600">Mensal</span>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="period" value="yearly" class="peer sr-only" {{ old('period', $budget->period) == 'yearly' ? 'checked' : '' }}>
                                    <div class="p-4 flex flex-col items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/5 transition-all group-hover:bg-slate-100 dark:group-hover:bg-slate-800">
                                        <x-icon name="calendar-check" class="text-xl text-slate-400 peer-checked:text-indigo-600 mb-2 transition-colors" />
                                        <span class="text-xs font-black uppercase tracking-widest text-slate-500 peer-checked:text-indigo-600">Anual</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- PRO Features - Silent for Free --}}
                        @if($isPro)
                            <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="p-6 bg-indigo-50 dark:bg-indigo-900/10 rounded-3xl border border-indigo-200 dark:border-indigo-900/30">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="bell" class="text-indigo-600 dark:text-indigo-400" />
                                            <span class="text-xs font-black uppercase tracking-widest text-indigo-700 dark:text-indigo-300">Alerta de Consumo</span>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Notificar em: <span x-text="alertThreshold + '%'"></span></span>
                                        </div>
                                        <input type="range" name="alert_threshold" x-model="alertThreshold" min="50" max="100" step="5"
                                               class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                        <p class="text-[11px] text-indigo-800/60 dark:text-indigo-500/60 leading-relaxed italic">Receba um alerta quando atingir este limite.</p>
                                    </div>
                                </div>

                                <div class="p-6 bg-rose-50 dark:bg-rose-900/10 rounded-3xl border border-rose-200 dark:border-rose-900/30">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="circle-xmark" class="text-rose-600 dark:text-rose-400" />
                                            <span class="text-xs font-black uppercase tracking-widest text-rose-700 dark:text-rose-300">Bloquear Excessos</span>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="allow_exceed" x-model="allowExceed" value="0" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-rose-500"></div>
                                        </label>
                                    </div>
                                    <p class="text-[11px] text-rose-800/60 dark:text-rose-500/60 leading-relaxed italic">Impedir novas transações nesta categoria se o limite for atingido.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Form Actions --}}
                    <div class="p-8 lg:p-12 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-3 px-8 py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-wider rounded-2xl shadow-xl shadow-indigo-900/30 transition-all transform hover:-translate-y-1">
                            <x-icon name="floppy-disk" size="lg" />
                            Salvar Alterações
                        </button>
                        <a href="{{ route('core.budgets.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-5 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm uppercase tracking-widest font-black">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
