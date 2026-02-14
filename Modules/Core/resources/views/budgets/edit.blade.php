@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Editar Orçamento'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8" x-data="{
    limitAmount: '{{ number_format($budget->limit_amount, 2, ',', '.') }}',
    alertThreshold: {{ (int) ($budget->alert_threshold ?? 80) }},
    allowExceed: {{ $budget->allow_exceed ? 'true' : 'false' }},
    formatCurrency() {
        let value = String(this.limitAmount || '').replace(/\D/g, '');
        if (value === '') { this.limitAmount = ''; return; }
        value = (parseInt(value) / 100).toFixed(2);
        this.limitAmount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }
}">
    {{-- Hero CBAV --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <a href="{{ route('core.budgets.index') }}" class="hover:underline">Orçamentos</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Editar</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Editar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Orçamento</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Ajuste o limite ou a periodicidade. A categoria não pode ser alterada.</p>
            </div>
            <a href="{{ route('core.budgets.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar
            </a>
        </div>
    </div>

    <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
        <form action="{{ route('core.budgets.update', $budget) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 sm:p-8 lg:p-10 space-y-8">
                {{-- Categoria (somente leitura) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Categoria</label>
                    <div class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10">
                        <x-icon name="tags" style="duotone" class="w-5 h-5 text-gray-400 dark:text-gray-500 shrink-0" />
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $budget->category->name }}</span>
                        <input type="hidden" name="category_id" value="{{ $budget->category_id }}">
                    </div>
                    <p class="mt-1.5 text-[10px] text-gray-500 dark:text-gray-400">A categoria não pode ser alterada após a criação.</p>
                </div>

                {{-- Limite --}}
                <div>
                    <label for="limit_amount_display" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Limite de gastos (R$) *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-bold">R$</span>
                        <input type="text" id="limit_amount_display" x-model="limitAmount" @input="formatCurrency()" required
                               class="w-full pl-12 pr-5 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none text-xl font-black text-gray-900 dark:text-white tabular-nums"
                               placeholder="0,00">
                        <input type="hidden" name="limit_amount" :value="(typeof limitAmount === 'string' ? limitAmount : '').replace(/\./g, '').replace(',', '.')">
                    </div>
                    @error('limit_amount')
                        <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Periodicidade --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Periodicidade *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="period" value="monthly" class="peer sr-only" {{ old('period', $budget->period) == 'monthly' ? 'checked' : '' }}>
                            <div class="p-4 flex flex-col items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/5 transition-all group-hover:bg-gray-100 dark:group-hover:bg-white/5">
                                <x-icon name="calendar-days" style="duotone" class="w-6 h-6 text-gray-400 peer-checked:text-emerald-600 mb-2" />
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 peer-checked:text-emerald-600">Mensal</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="period" value="yearly" class="peer sr-only" {{ old('period', $budget->period) == 'yearly' ? 'checked' : '' }}>
                            <div class="p-4 flex flex-col items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/5 transition-all group-hover:bg-gray-100 dark:group-hover:bg-white/5">
                                <x-icon name="calendar-check" style="duotone" class="w-6 h-6 text-gray-400 peer-checked:text-emerald-600 mb-2" />
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 peer-checked:text-emerald-600">Anual</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Recursos Pro (ocultos para free) --}}
                @if($isPro)
                    <div class="pt-6 border-t border-gray-200 dark:border-white/5 space-y-4">
                        <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider flex items-center gap-2">
                            <x-icon name="sparkles" style="duotone" class="w-4 h-4" />
                            Vertex Pro
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-emerald-500/5 dark:bg-emerald-500/10 border border-emerald-200/50 dark:border-emerald-800/30">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-icon name="bell" style="duotone" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                                    <span class="font-bold text-gray-900 dark:text-white text-sm">Alerta de consumo</span>
                                </div>
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Notificar em: <span x-text="alertThreshold + '%'"></span></p>
                                <input type="range" name="alert_threshold" x-model="alertThreshold" min="50" max="100" step="5" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg accent-emerald-600">
                            </div>
                            <div class="p-4 rounded-2xl bg-rose-500/5 dark:bg-rose-500/10 border border-rose-200/50 dark:border-rose-800/30">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <x-icon name="circle-xmark" style="duotone" class="w-5 h-5 text-rose-600 dark:text-rose-400" />
                                        <span class="font-bold text-gray-900 dark:text-white text-sm">Bloquear excessos</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="allow_exceed" value="1">
                                        <input type="checkbox" name="allow_exceed" x-model="allowExceed" value="0" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:bg-rose-500 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Impedir novas despesas nesta categoria ao atingir o limite.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-6 sm:px-8 lg:px-10 py-5 border-t border-gray-200 dark:border-white/5 flex flex-col-reverse sm:flex-row gap-3">
                <a href="{{ route('core.budgets.index') }}" class="inline-flex items-center justify-center gap-2 py-3 px-5 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
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
