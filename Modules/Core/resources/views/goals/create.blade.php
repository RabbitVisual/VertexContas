@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Criar Nova Meta'">
    <div class="space-y-8 pb-8" x-data="{
        targetAmount: '',
        currentAmount: '',
        isPriority: false,
        formatCurrency(field) {
            let value = this[field].replace(/\D/g, '');
            if (value === '') {
                this[field] = '';
                return;
            }
            value = (parseInt(value) / 100).toFixed(2);
            this[field] = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    }">
        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/20 border border-indigo-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="plus-circle" class="w-4 h-4 text-indigo-300" />
                        <span class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em]">Planejamento</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Nova Meta</h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base leading-relaxed">Defina o alvo da sua independência financeira</p>
                </div>
                <a href="{{ route('core.goals.index') }}" class="shrink-0 inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold transition-all backdrop-blur-md">
                    <x-icon name="arrow-left" class="w-4 h-4 text-white/70" />
                    Cancelar
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <form action="{{ route('core.goals.store') }}" method="POST">
                    @csrf

                    <div class="p-8 lg:p-12 space-y-10">
                        {{-- Name --}}
                        <div class="space-y-3">
                            <label for="name" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Como você chama este sonho? *</label>
                            <div class="relative group">
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors">
                                    <x-icon name="flag" style="solid" />
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                       class="w-full pl-12 pr-6 py-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none font-bold text-slate-700 dark:text-white"
                                       placeholder="Ex: Reserva de Emergência, Viagem Disney..." required>
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Amounts Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Target Amount --}}
                            <div class="space-y-3">
                                <label for="target_amount_display" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Qual é o valor alvo? *</label>
                                <div class="relative">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 flex items-center gap-2 pointer-events-none">
                                        <span class="text-xl font-black text-slate-400">R$</span>
                                    </div>
                                    <input type="text"
                                           id="target_amount_display"
                                           x-model="targetAmount"
                                           @input="formatCurrency('targetAmount')"
                                           placeholder="0,00"
                                           class="w-full pl-16 pr-6 py-6 text-3xl font-black rounded-3xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none text-slate-900 dark:text-white"
                                           required>
                                    <input type="hidden" name="target_amount" :value="targetAmount.replace(/\./g, '').replace(',', '.')">
                                </div>
                                @error('target_amount')
                                    <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Current Amount --}}
                            <div class="space-y-3">
                                <label for="current_amount_display" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Já possui algum valor?</label>
                                <div class="relative">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 flex items-center gap-2 pointer-events-none">
                                        <span class="text-xl font-black text-slate-400">R$</span>
                                    </div>
                                    <input type="text"
                                           id="current_amount_display"
                                           x-model="currentAmount"
                                           @input="formatCurrency('currentAmount')"
                                           placeholder="0,00"
                                           class="w-full pl-16 pr-6 py-6 text-3xl font-black rounded-3xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none text-slate-900 dark:text-white">
                                    <input type="hidden" name="current_amount" :value="currentAmount.replace(/\./g, '').replace(',', '.')">
                                </div>
                                @error('current_amount')
                                    <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Deadline --}}
                        <div class="space-y-3">
                            <label for="deadline" class="block text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 ml-1">Quando deseja realizar este objetivo? (Prazo)</label>
                            <div class="relative group">
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <x-icon name="calendar-clock" />
                                </div>
                                <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}"
                                       class="w-full pl-12 pr-6 py-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none font-bold text-slate-700 dark:text-white">
                            </div>
                            @error('deadline')
                                <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PRO Features --}}
                        @if($isPro)
                            <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="p-6 bg-amber-50 dark:bg-amber-900/10 rounded-3xl border border-amber-200 dark:border-amber-900/30">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="star" class="text-amber-600 dark:text-amber-400" />
                                            <span class="text-xs font-black uppercase tracking-widest text-amber-700 dark:text-amber-300">Prioritária</span>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="isPriority" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500"></div>
                                        </label>
                                    </div>
                                    <p class="text-[11px] text-amber-800 dark:text-amber-500/80 leading-relaxed">Metas prioritárias aparecem em destaque no seu painel principal.</p>
                                </div>

                                <div class="p-6 bg-indigo-50 dark:bg-indigo-900/10 rounded-3xl border border-indigo-200 dark:border-indigo-900/30 relative opacity-60">
                                    <div class="absolute inset-0 flex items-center justify-center z-10">
                                        <span class="bg-indigo-600 text-white text-[9px] font-black px-2 py-1 rounded-lg uppercase tracking-widest">Em breve</span>
                                    </div>
                                    <div class="flex items-center gap-2 mb-4">
                                        <x-icon name="list-check" class="text-indigo-600 dark:text-indigo-400" />
                                        <span class="text-xs font-black uppercase tracking-widest text-indigo-700 dark:text-indigo-300">Etapas (Múltiplas)</span>
                                    </div>
                                    <p class="text-[11px] text-indigo-800 dark:text-indigo-500/80 leading-relaxed">Quebre sua meta em pequenos passos para facilitar a realização.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Form Actions --}}
                    <div class="p-8 lg:p-12 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-3 px-8 py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-wider rounded-2xl shadow-xl shadow-indigo-900/30 transition-all transform hover:-translate-y-1">
                            <x-icon name="check" size="lg" />
                            Começar este Planejamento
                        </button>
                        <a href="{{ route('core.goals.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-5 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm uppercase tracking-widest font-black">
                            Desistir
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
