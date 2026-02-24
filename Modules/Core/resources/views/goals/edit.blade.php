@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Editar Meta'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8" x-data="{
    targetAmount: '{{ format_number($goal->target_amount, 2) }}',
    currentAmount: '{{ format_number($goal->current_amount, 2) }}',
    formatCurrency(field) {
        let value = String(this[field] || '').replace(/\D/g, '');
        if (value === '') { this[field] = ''; return; }
        value = (parseInt(value) / 100).toFixed(2);
        this[field] = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }
}">
    {{-- Hero CBAV --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <a href="{{ route('core.goals.index') }}" class="hover:underline">Metas</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Editar</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Editar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Meta</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Ajuste o valor, o prazo ou o nome. O progresso é mantido.</p>
            </div>
            <a href="{{ route('core.goals.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar às metas
            </a>
        </div>
    </div>

    {{-- Dica rápida ao editar --}}
    <div class="rounded-2xl border border-amber-200/60 dark:border-amber-800/40 bg-amber-50/40 dark:bg-amber-950/20 p-4 sm:p-5 flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <x-icon name="lightbulb" style="duotone" class="w-5 h-5" />
        </div>
        <div class="min-w-0 text-sm text-gray-700 dark:text-gray-300">
            <strong class="text-amber-800 dark:text-amber-200">Lembrete:</strong> ao alterar o &quot;Valor acumulado&quot;, você está ajustando manualmente o progresso. Ao mudar a <strong>contribuição automática</strong> (valor, conta, categoria ou dia), o sistema atualiza o agendamento: no próximo dia configurado, a despesa será gerada e somada à meta. Desative a contribuição se não quiser mais débitos automáticos.
        </div>
    </div>

    <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
        <form action="{{ route('core.goals.update', $goal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 sm:p-8 lg:p-10 space-y-8">
                {{-- Nome --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Nome da meta *</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                            <x-icon name="flag" style="duotone" class="w-5 h-5" />
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $goal->name) }}" required
                               class="w-full pl-12 pr-5 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none font-medium text-gray-900 dark:text-white"
                               placeholder="Ex: Reserva de emergência, Viagem...">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Valores --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="target_amount_display" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Valor alvo (R$) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-bold">R$</span>
                            <input type="text" id="target_amount_display" x-model="targetAmount" @input="formatCurrency('targetAmount')" required
                                   class="w-full pl-12 pr-5 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none text-xl font-black text-gray-900 dark:text-white tabular-nums"
                                   placeholder="0,00">
                            <input type="hidden" name="target_amount" :value="(typeof targetAmount === 'string' ? targetAmount : '').replace(/\./g, '').replace(',', '.')">
                        </div>
                        @error('target_amount')
                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="current_amount_display" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Valor acumulado (R$)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-bold">R$</span>
                            <input type="text" id="current_amount_display" x-model="currentAmount" @input="formatCurrency('currentAmount')"
                                   class="w-full pl-12 pr-5 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none text-xl font-black text-gray-900 dark:text-white tabular-nums"
                                   placeholder="0,00">
                            <input type="hidden" name="current_amount" :value="(typeof currentAmount === 'string' ? currentAmount : '').replace(/\./g, '').replace(',', '.')">
                        </div>
                        @error('current_amount')
                            <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Prazo --}}
                <div>
                    <label for="deadline" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Prazo desejado</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                            <x-icon name="calendar-days" style="duotone" class="w-5 h-5" />
                        </div>
                        <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $goal->deadline?->format('Y-m-d')) }}"
                               class="w-full pl-12 pr-5 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none font-medium text-gray-900 dark:text-white">
                    </div>
                    @error('deadline')
                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contribuição mensal automática --}}
                @php $hasContribution = (float)($goal->monthly_contribution ?? 0) > 0 || old('monthly_contribution'); @endphp
                <div class="pt-6 border-t border-gray-200 dark:border-white/5 space-y-4" x-data="{ enableContribution: {{ $hasContribution ? 'true' : 'false' }} }">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2">
                        <x-icon name="arrows-rotate" style="duotone" class="w-4 h-4" />
                        Contribuição mensal automática
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Dedico todo mês um valor para esta meta. O valor será debitado da conta e registrado no extrato.</p>
                    <label class="relative inline-flex items-center cursor-pointer gap-3">
                        <input type="checkbox" x-model="enableContribution" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Ativar contribuição automática</span>
                    </label>
                    <div x-show="enableContribution" x-collapse class="space-y-4 rounded-2xl bg-gray-50 dark:bg-gray-950/50 border border-gray-200 dark:border-white/10 p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="monthly_contribution" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Valor mensal (R$)</label>
                                <input type="number" id="monthly_contribution" name="monthly_contribution" value="{{ old('monthly_contribution', $goal->monthly_contribution) }}" step="0.01" min="0"
                                       class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-4 py-2.5 font-medium tabular-nums">
                                @error('monthly_contribution')
                                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contribution_recurrence_day" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Dia do mês (1–31)</label>
                                <div class="relative">
                                    <select name="contribution_recurrence_day" id="contribution_recurrence_day" style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-4 py-2.5 pr-10 font-medium appearance-none [&::-ms-expand]:hidden">
                                        @foreach(range(1, 31) as $d)
                                            <option value="{{ $d }}" {{ old('contribution_recurrence_day', $goal->contribution_recurrence_day ?? 1) == $d ? 'selected' : '' }}>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"><x-icon name="chevron-down" style="solid" class="w-4 h-4" /></span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contribution_account_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Conta</label>
                                <div class="relative">
                                    <select name="contribution_account_id" id="contribution_account_id" style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-4 py-2.5 pr-10 font-medium appearance-none [&::-ms-expand]:hidden">
                                        <option value="">Selecione</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}" {{ old('contribution_account_id', $goal->contribution_account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"><x-icon name="chevron-down" style="solid" class="w-4 h-4" /></span>
                                </div>
                                @error('contribution_account_id')
                                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contribution_category_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Categoria</label>
                                <div class="relative">
                                    <select name="contribution_category_id" id="contribution_category_id" style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-4 py-2.5 pr-10 font-medium appearance-none [&::-ms-expand]:hidden">
                                        <option value="">Selecione</option>
                                        @foreach($expenseCategories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('contribution_category_id', $goal->contribution_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"><x-icon name="chevron-down" style="solid" class="w-4 h-4" /></span>
                                </div>
                                @error('contribution_category_id')
                                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recursos Pro (ocultos para free) --}}
                @if($isPro)
                    <div class="pt-6 border-t border-gray-200 dark:border-white/5 space-y-4">
                        <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider flex items-center gap-2">
                            <x-icon name="sparkles" style="duotone" class="w-4 h-4" />
                            Vertex Pro
                        </p>
                        <div class="p-4 rounded-2xl bg-amber-500/5 dark:bg-amber-500/10 border border-amber-200/50 dark:border-amber-800/30 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                    <x-icon name="star" style="duotone" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">Meta prioritária</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Aparece em destaque no painel.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_priority" value="1" class="sr-only peer" {{ old('is_priority') ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:bg-amber-500 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-6 sm:px-8 lg:px-10 py-5 border-t border-gray-200 dark:border-white/5 flex flex-col-reverse sm:flex-row gap-3">
                <a href="{{ route('core.goals.index') }}" class="inline-flex items-center justify-center gap-2 py-3 px-5 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                    <x-icon name="floppy-disk" style="solid" class="w-5 h-5" />
                    Salvar alterações
                </button>
            </div>
        </form>
    </div>
</div>
</x-paneluser::layouts.master>
