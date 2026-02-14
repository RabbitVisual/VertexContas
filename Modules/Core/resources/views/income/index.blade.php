@php
    $isPro = auth()->user()?->isPro() ?? false;
    $existingIncomes = $existingIncomes ?? [];
    $initialRows = !empty($existingIncomes)
        ? collect($existingIncomes)->map(fn ($i) => [
            'description' => $i['description'] ?? '',
            'amount' => isset($i['amount']) ? number_format((float) $i['amount'], 2, ',', '.') : '',
            'day' => (string) ($i['day'] ?? '1'),
        ])->values()->all()
        : [['description' => '', 'amount' => '', 'day' => '1']];
    $dashboardRoute = $isPro && Route::has('core.dashboard') ? route('core.dashboard') : route('paneluser.index');
@endphp
<x-paneluser::layouts.master :title="'Minha Renda'">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('incomeForm', () => ({
                rows: @json($initialRows),
                isPro: @json($isPro),
                showUpgradeModal: false,
                init() {
                    this.rows = this.rows.map(r => ({ ...r, day: r.day || '1' }));
                },
                add() {
                    if (this.rows.length >= 1 && !this.isPro) {
                        this.showUpgradeModal = true;
                        return;
                    }
                    this.rows.push({ description: '', amount: '', day: '1' });
                },
                remove(index) {
                    if (this.rows.length <= 1) return;
                    this.rows.splice(index, 1);
                }
            }));
        });
    </script>
    <div x-data="incomeForm()" class="space-y-8 pb-12">
        {{-- Hero Header - Vertex CBAV style --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900/80 text-white shadow-xl">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px] opacity-50"></div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-emerald-600/20 to-transparent"></div>
            <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 border border-emerald-400/30 rounded-full backdrop-blur-md mb-4">
                        <x-icon name="money-bill-trend-up" style="duotone" class="w-4 h-4 text-emerald-300" />
                        <span class="text-emerald-200 text-xs font-black uppercase tracking-[0.2em]">Linha de base</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                        Gerenciar Renda Mensal
                    </h1>
                    <p class="text-slate-400 font-medium max-w-xl mt-2 text-base md:text-lg leading-relaxed">
                        Cadastre suas fontes de receita recorrente. Com isso calculamos sua capacidade mensal e o indicador de saúde financeira.
                    </p>
                </div>
                <a href="{{ $dashboardRoute }}" class="shrink-0 inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold hover:bg-white/20 transition-colors">
                    <x-icon name="arrow-left" style="duotone" class="w-5 h-5 text-slate-200" />
                    Voltar
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 md:px-8 md:py-6 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-black uppercase tracking-[0.15em] text-slate-500 dark:text-slate-400">Fontes de receita</h3>
                    <p class="text-slate-700 dark:text-slate-300 font-medium mt-0.5">Salário, pensão, bônus, vale-refeição ou outras entradas mensais fixas</p>
                </div>

                <form action="{{ route('core.income.store') }}" method="POST" class="p-6 md:p-8 lg:p-10">
                    @csrf

                    <div class="space-y-6">
                        <template x-for="(row, index) in rows" :key="index">
                            <div class="group relative flex flex-col gap-4 p-5 md:p-6 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-700/80 hover:border-slate-200 dark:hover:border-slate-600 transition-colors">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold uppercase tracking-wider"
                                          x-text="'Fonte ' + (index + 1)"></span>
                                    <button type="button"
                                            x-show="rows.length > 1"
                                            @click="remove(index)"
                                            class="p-2 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors"
                                            aria-label="Remover fonte">
                                        <x-icon name="trash" style="solid" class="w-4 h-4" />
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-5">
                                        <label :for="'desc-' + index" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Descrição</label>
                                        <input type="text"
                                               :name="'incomes[' + index + '][description]'"
                                               :id="'desc-' + index"
                                               x-model="row.description"
                                               placeholder="Ex: Salário CLT, Pensão, Bônus"
                                               class="w-full rounded-xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-3.5 font-medium focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all"
                                               required>
                                    </div>
                                    <div class="md:col-span-4">
                                        <label :for="'amount-' + index" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Valor mensal (R$)</label>
                                        <input type="text"
                                               :name="'incomes[' + index + '][amount]'"
                                               :id="'amount-' + index"
                                               x-model="row.amount"
                                               x-mask="'money'"
                                               placeholder="0,00"
                                               class="w-full rounded-xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-3.5 font-mono tabular-nums focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all"
                                               required>
                                    </div>
                                    <div class="md:col-span-3">
                                        <label :for="'day-' + index" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Dia do recebimento</label>
                                        <select :name="'incomes[' + index + '][day]'"
                                                :id="'day-' + index"
                                                x-model="row.day"
                                                class="w-full rounded-xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-3.5 font-medium focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all"
                                                required>
                                            @for ($d = 1; $d <= 31; $d++)
                                                <option value="{{ $d }}">{{ $d }}</option>
                                            @endfor
                                        </select>
                                        <p class="mt-1 text-[10px] text-slate-400 dark:text-slate-500">31 = último dia do mês</p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-4">
                        <button type="button"
                                @click="add()"
                                class="order-2 sm:order-1 inline-flex items-center justify-center gap-2.5 px-5 py-3.5 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-400 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 font-semibold transition-all">
                            <x-icon name="circle-plus" style="solid" class="w-5 h-5" />
                            Adicionar outra fonte
                        </button>
                        <button type="submit"
                                class="order-1 sm:order-2 flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-500/25 transition-all">
                            <x-icon name="check" style="solid" class="w-5 h-5" />
                            Atualizar Renda Base
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Upgrade PRO --}}
        <div x-show="showUpgradeModal"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="showUpgradeModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-sm" @click="showUpgradeModal = false"></div>
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden" @click.stop>
                <div class="p-6 md:p-8">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center mb-4">
                        <x-icon name="crown" style="solid" size="2xl" class="text-amber-600 dark:text-amber-400" />
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Recurso Premium</h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        Múltiplas fontes de receita (salário, bônus, pensão, vale-refeição) é um benefício Vertex PRO. No plano gratuito use uma única receita total.
                    </p>
                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('user.subscription.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold transition-colors shadow-lg shadow-amber-500/25">
                            <x-icon name="crown" style="solid" />
                            Conhecer Vertex PRO
                        </a>
                        <button type="button"
                                @click="showUpgradeModal = false"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
