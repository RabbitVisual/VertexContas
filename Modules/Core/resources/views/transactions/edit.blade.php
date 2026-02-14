@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Editar Transação'">
    <div class="space-y-8 pb-8" x-data="{
        type: '{{ old('type', $transaction->type) }}',
        amount: '{{ number_format($transaction->amount, 2, ',', '.') }}',
        isRecurring: false,
        formatCurrency() {
            let value = this.amount.replace(/\D/g, '');
            if (value === '') {
                this.amount = '';
                return;
            }
            value = (parseInt(value) / 100).toFixed(2);
            this.amount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    }">
        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500/20 border border-primary-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="pen-to-square" class="w-4 h-4 text-primary-300" />
                        <span class="text-primary-200 text-xs font-black uppercase tracking-[0.2em]">Edição</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Editar Transação</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base leading-relaxed">Atualize os detalhes da sua movimentação financeira</p>
                </div>
                <a href="{{ route('core.transactions.index') }}" class="shrink-0 inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold transition-all backdrop-blur-md">
                    <x-icon name="arrow-left" class="w-4 h-4 text-white/70" />
                    Voltar para o extrato
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <form action="{{ route('core.transactions.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-12">
                        <div class="lg:col-span-12 p-8 lg:p-12 space-y-10">

                            {{-- Type Selector (Disabled in edit for integrity) --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-6">Tipo de Movimentação</label>
                                <div class="grid grid-cols-2 gap-4 opacity-70">
                                    <div :class="type === 'income' ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'bg-slate-50 dark:bg-slate-900 border-slate-100 dark:border-slate-800 text-slate-500'"
                                         class="flex flex-col items-center gap-3 p-6 rounded-3xl border-2 transition-all group cursor-not-allowed">
                                        <div :class="type === 'income' ? 'bg-white/20' : 'bg-emerald-50 dark:bg-emerald-900/20'" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors">
                                            <x-icon name="arrow-up" style="solid" class="text-xl" />
                                        </div>
                                        <span class="font-black uppercase tracking-wider text-sm">Receita</span>
                                    </div>

                                    <div :class="type === 'expense' ? 'bg-rose-600 border-rose-600 text-white shadow-lg shadow-rose-900/20' : 'bg-slate-50 dark:bg-slate-900 border-slate-100 dark:border-slate-800 text-slate-500'"
                                         class="flex flex-col items-center gap-3 p-6 rounded-3xl border-2 transition-all group cursor-not-allowed">
                                        <div :class="type === 'expense' ? 'bg-white/20' : 'bg-rose-50 dark:bg-rose-900/20'" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors">
                                            <x-icon name="arrow-down" style="solid" class="text-xl" />
                                        </div>
                                        <span class="font-black uppercase tracking-wider text-sm">Despesa</span>
                                    </div>
                                </div>
                                <input type="hidden" name="type" :value="type">
                            </div>

                            {{-- Amount --}}
                            <div class="relative group">
                                <label for="amount" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-4 ml-1">Valor da Operação</label>
                                <div class="relative">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 flex items-center gap-2 pointer-events-none">
                                        <span class="text-2xl font-black text-slate-400 dark:text-slate-500">R$</span>
                                    </div>
                                    <input type="text"
                                           id="amount"
                                           x-model="amount"
                                           @input="formatCurrency()"
                                           placeholder="0,00"
                                           class="w-full pl-20 pr-8 py-8 text-5xl font-black rounded-3xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-primary-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none text-slate-900 dark:text-white placeholder-slate-200 dark:placeholder-slate-800"
                                           required>
                                    <input type="hidden" name="amount" :value="amount.replace(/\./g, '').replace(',', '.')">
                                </div>
                                @error('amount')
                                    <p class="mt-2 text-sm text-rose-500 font-bold flex items-center gap-1.5 ml-2">
                                        <x-icon name="circle-exclamation" style="solid" />
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Form Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Account --}}
                                <div class="space-y-3">
                                    <label for="account_id" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Conta de Origem/Destino</label>
                                    <div class="relative">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500">
                                            <x-icon name="piggy-bank" />
                                        </div>
                                        <select name="account_id" id="account_id" class="w-full pl-11 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all appearance-none font-bold text-slate-700 dark:text-slate-200" required>
                                            <option value="">Selecione uma conta</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}>
                                                    {{ $account->name }} (R$ {{ number_format($account->balance, 2, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <x-icon name="chevron-down" size="xs" />
                                        </div>
                                    </div>
                                    @error('account_id')
                                        <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Category --}}
                                <div class="space-y-3">
                                    <label for="category_id" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Categoria</label>
                                    <div class="relative">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500">
                                            <x-icon name="tags" />
                                        </div>
                                        <select name="category_id" id="category_id" class="w-full pl-11 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all appearance-none font-bold text-slate-700 dark:text-slate-200" required>
                                            <option value="">Selecione uma categoria</option>
                                            <template x-if="type === 'income'">
                                                <optgroup label="Receitas">
                                                    @foreach($categories->where('type', 'income') as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </template>
                                            <template x-if="type === 'expense'">
                                                <optgroup label="Despesas">
                                                    @foreach($categories->where('type', 'expense') as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </template>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <x-icon name="chevron-down" size="xs" />
                                        </div>
                                    </div>
                                    @error('category_id')
                                        <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Date --}}
                                <div class="space-y-3">
                                    <label for="date" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Data da Efetivação</label>
                                    <div class="relative">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                            <x-icon name="calendar" />
                                        </div>
                                        <input type="date" name="date" id="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" class="w-full pl-11 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all font-bold text-slate-700 dark:text-slate-200" required>
                                    </div>
                                    @error('date')
                                        <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="space-y-3">
                                    <label for="status" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Status da Operação</label>
                                    <div class="relative">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                            <x-icon name="circle-notch" />
                                        </div>
                                        <select name="status" id="status" class="w-full pl-11 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all appearance-none font-bold text-slate-700 dark:text-slate-200" required>
                                            <option value="completed" {{ old('status', $transaction->status) == 'completed' ? 'selected' : '' }}>Concluída / Paga</option>
                                            <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Pendente / Agendada</option>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <x-icon name="chevron-down" size="xs" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="space-y-3">
                                <label for="description" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Descrição / Notas</label>
                                <div class="relative">
                                    <textarea name="description" id="description" rows="3" class="w-full p-6 bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-3xl focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400" placeholder="Digite uma descrição para identificar esta transação...">{{ old('description', $transaction->description) }}</textarea>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- PRO Exclusive: Recurring & Attachments --}}
                            @if($isPro)
                                <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="p-6 bg-amber-50 dark:bg-amber-900/10 rounded-3xl border border-amber-200 dark:border-amber-900/30">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="repeat" class="text-amber-600 dark:text-amber-400" />
                                                <span class="text-xs font-black uppercase tracking-widest text-amber-700 dark:text-amber-300">Repetir</span>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" x-model="isRecurring" class="sr-only peer">
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500"></div>
                                            </label>
                                        </div>
                                        <p class="text-[11px] text-amber-800 dark:text-amber-500/80 leading-relaxed">Habilite para gerenciar esta transação recorrente.</p>
                                    </div>

                                    <div class="p-6 bg-primary-50 dark:bg-primary-900/10 rounded-3xl border border-primary-200 dark:border-primary-900/30">
                                        <div class="flex items-center gap-2 mb-4">
                                            <x-icon name="paperclip" class="text-primary-600 dark:text-primary-400" />
                                            <span class="text-xs font-black uppercase tracking-widest text-primary-700 dark:text-primary-300">Anexo PRO</span>
                                        </div>
                                        <div class="flex items-center justify-center p-4 border-2 border-dashed border-primary-200 dark:border-primary-800 rounded-2xl group cursor-pointer hover:bg-white dark:hover:bg-slate-900 transition-all">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform">Alterar Comprovante</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="p-8 lg:p-12 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-3 px-8 py-5 bg-primary-600 hover:bg-primary-500 text-white font-black uppercase tracking-wider rounded-2xl shadow-xl shadow-primary-900/30 transition-all transform hover:-translate-y-1">
                            <x-icon name="floppy-disk" style="solid" class="text-lg" />
                            Salvar Alterações
                        </button>
                        <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-5 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
