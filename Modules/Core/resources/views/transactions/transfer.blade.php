@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Transferência entre Contas'">
    <div class="space-y-8 pb-8" x-data="{
        amount: '',
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
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/20 border border-indigo-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="right-left" class="w-4 h-4 text-indigo-300" />
                        <span class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em]">Movimentação Interna</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Transferência</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base leading-relaxed">Mova saldo entre suas contas de forma rápida e segura</p>
                </div>
                <a href="{{ route('core.transactions.index') }}" class="shrink-0 inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold transition-all backdrop-blur-md">
                    <x-icon name="arrow-left" class="w-4 h-4 text-white/70" />
                    Cancelar
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <form action="{{ route('core.transactions.processTransfer') }}" method="POST">
                    @csrf

                    <div class="p-8 lg:p-12 space-y-12">

                        {{-- Visual Flow Selector --}}
                        <div class="grid grid-cols-1 md:grid-cols-11 items-center gap-6">
                            {{-- From Account --}}
                            <div class="md:col-span-5 space-y-4 text-center">
                                <label for="from_account_id" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Origem do Saldo</label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                        <x-icon name="building-columns" style="solid" />
                                    </div>
                                    <select name="from_account_id" id="from_account_id" class="w-full pl-11 pr-10 py-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all appearance-none font-bold text-slate-700 dark:text-slate-200 text-lg" required>
                                        <option value="">De onde sai?</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <x-icon name="chevron-down" size="xs" />
                                    </div>
                                </div>
                                @error('from_account_id')
                                    <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Transfer Icon --}}
                            <div class="md:col-span-1 flex justify-center py-4">
                                <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner border border-indigo-100 dark:border-indigo-800 rotate-90 md:rotate-0">
                                    <x-icon name="arrow-right" style="solid" class="animate-pulse" />
                                </div>
                            </div>

                            {{-- To Account --}}
                            <div class="md:col-span-5 space-y-4 text-center">
                                <label for="to_account_id" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Destino do Saldo</label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                        <x-icon name="wallet" style="solid" />
                                    </div>
                                    <select name="to_account_id" id="to_account_id" class="w-full pl-11 pr-10 py-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all appearance-none font-bold text-slate-700 dark:text-slate-200 text-lg" required>
                                        <option value="">Para onde vai?</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <x-icon name="chevron-down" size="xs" />
                                    </div>
                                </div>
                                @error('to_account_id')
                                    <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Amount Section --}}
                        <div class="relative group max-w-2xl mx-auto">
                            <label for="amount" class="block text-center text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-6">Qual o valor desejado?</label>
                            <div class="relative">
                                <div class="absolute left-10 top-1/2 -translate-y-1/2 flex items-center gap-2 pointer-events-none">
                                    <span class="text-3xl font-black text-slate-400 dark:text-slate-500">R$</span>
                                </div>
                                <input type="text"
                                       id="amount"
                                       x-model="amount"
                                       @input="formatCurrency()"
                                       placeholder="0,00"
                                       class="w-full pl-28 pr-12 py-10 text-6xl font-black rounded-[40px] bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-8 focus:ring-indigo-500/5 transition-all outline-none text-slate-900 dark:text-white placeholder-slate-200 dark:placeholder-slate-800 text-center"
                                       required>
                                <input type="hidden" name="amount" :value="amount.replace(/\./g, '').replace(',', '.')">
                            </div>
                            @error('amount')
                                <p class="mt-4 text-center text-sm text-rose-500 font-bold flex items-center justify-center gap-2">
                                    <x-icon name="circle-exclamation" style="solid" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Date and Description --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6">
                            <div class="space-y-3">
                                <label for="date" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Data da Efetivação</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                        <x-icon name="calendar-day" />
                                    </div>
                                    <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="w-full pl-11 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all font-bold text-slate-700 dark:text-slate-200" required>
                                </div>
                                @error('date')
                                    <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <label for="description" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Descrição Opcional</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                        <x-icon name="note" />
                                    </div>
                                    <input type="text" name="description" id="description" placeholder="Ex: Transferência para reserva" value="{{ old('description') }}" class="w-full pl-11 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all font-bold text-slate-700 dark:text-slate-200">
                                </div>
                                @error('description')
                                    <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="p-8 lg:p-12 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-3 px-8 py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-wider rounded-2xl shadow-xl shadow-indigo-900/30 transition-all transform hover:-translate-y-1">
                            <x-icon name="check-double" size="lg" />
                            Confirmar Transferência
                        </button>
                    </div>
                </form>
            </div>

            {{-- PRO Tip --}}
            @if($isPro)
                <div class="mt-8 p-6 bg-slate-900 dark:bg-slate-800 rounded-3xl border border-slate-800 flex items-center gap-6 shadow-2xl relative overflow-hidden group">
                     <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-600/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                     <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shrink-0 shadow-lg shadow-indigo-900/40">
                         <x-icon name="lightbulb" size="lg" />
                     </div>
                     <div class="relative">
                         <h4 class="text-white font-black uppercase tracking-widest text-xs mb-1">Dica Vertex Pro</h4>
                         <p class="text-slate-400 text-sm leading-relaxed">As transferências entre contas não afetam o seu saldo total líquido, apenas reorganizam seus ativos por instituição.</p>
                     </div>
                </div>
            @endif
        </div>
    </div>
</x-paneluser::layouts.master>
