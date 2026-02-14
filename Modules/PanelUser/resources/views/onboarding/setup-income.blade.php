@php
    $isPro = auth()->user()?->isPro() ?? false;
    $isEditMode = $isEditMode ?? false;
    $existingIncomes = $existingIncomes ?? [];
    $initialRows = !empty($existingIncomes)
        ? collect($existingIncomes)->map(fn ($i) => [
            'description' => $i['description'] ?? '',
            'amount' => isset($i['amount']) ? number_format((float) $i['amount'], 2, ',', '.') : '',
            'day' => (string) ($i['day'] ?? '1'),
        ])->values()->all()
        : [['description' => '', 'amount' => '', 'day' => '1']];
@endphp
<x-paneluser::layouts.master :title="$isEditMode ? 'Gerenciar Renda Mensal' : 'Configurar receitas'">
    <div
        x-data="{
            rows: @json($initialRows),
            isPro: @json($isPro),
            showUpgradeModal: false,
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
        }"
        x-init="rows = rows.map(r => ({ ...r, day: r.day || '1' }))"
        class="max-w-3xl mx-auto py-8 px-4"
    >
        {{-- Breadcrumb / Back to Dashboard --}}
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('paneluser.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-1">
                <x-icon name="arrow-left" style="solid" size="xs" />
                Dashboard
            </a>
            <span class="text-slate-400 dark:text-slate-500">/</span>
            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $isEditMode ? 'Gerenciar Renda' : 'Configurar receitas' }}</span>
        </nav>

        <div class="rounded-3xl border border-slate-200/80 dark:border-slate-700/80 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-xl shadow-slate-200/20 dark:shadow-slate-900/20 overflow-hidden">
            <div class="p-6 md:p-10 border-b border-slate-200 dark:border-slate-700">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                    @if($isEditMode)
                        Gerenciar Renda Mensal
                    @else
                        Sua linha de base financeira
                    @endif
                </h1>
                <p class="mt-2 text-slate-600 dark:text-slate-400">
                    Cadastre suas fontes de receita recorrente (salário, pensão, bônus, vale-refeição). Assim calculamos sua capacidade mensal.
                </p>
            </div>

            <form action="{{ route('paneluser.onboarding.store-income') }}" method="POST" class="p-6 md:p-10">
                @csrf
                <div class="space-y-6">
                    <template x-for="(row, index) in rows" :key="index">
                        <div class="flex flex-col sm:flex-row gap-4 p-4 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                            <div class="flex-1 min-w-0">
                                <label :for="'desc-' + index" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Descrição</label>
                                <input
                                    type="text"
                                    :name="'incomes[' + index + '][description]'"
                                    :id="'desc-' + index"
                                    x-model="row.description"
                                    placeholder="Ex: Salário, Pensão, Bônus"
                                    class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 px-4 py-2.5"
                                    required
                                />
                            </div>
                            <div class="w-full sm:w-36">
                                <label :for="'amount-' + index" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Valor (R$)</label>
                                <input
                                    type="text"
                                    :name="'incomes[' + index + '][amount]'"
                                    :id="'amount-' + index"
                                    x-model="row.amount"
                                    x-mask="'money'"
                                    placeholder="R$ 0,00"
                                    class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 px-4 py-2.5"
                                    required
                                />
                            </div>
                            <div class="w-full sm:w-28">
                                <label :for="'day-' + index" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dia do recebimento</label>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block mb-0.5">31 = último dia do mês</span>
                                <select
                                    :name="'incomes[' + index + '][day]'"
                                    :id="'day-' + index"
                                    x-model="row.day"
                                    class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 px-4 py-2.5"
                                    required
                                >
                                    @for ($d = 1; $d <= 31; $d++)
                                        <option value="{{ $d }}">{{ $d }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="flex items-end gap-2">
                                <button
                                    type="button"
                                    @click="remove(index)"
                                    x-show="rows.length > 1"
                                    class="p-2.5 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 focus:ring-2 focus:ring-primary-500 transition-colors"
                                    aria-label="Remover fonte"
                                >
                                    <x-icon name="trash" style="solid" size="sm" />
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-4">
                    <button
                        type="button"
                        @click="add()"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:border-primary-500 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all font-medium"
                    >
                        <x-icon name="circle-plus" style="solid" />
                        Adicionar outra fonte de receita
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-white font-bold hover:bg-primary-600 dark:bg-primary-500 dark:hover:bg-primary-600 shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                    >
                        @if($isEditMode)
                            Atualizar Renda Base
                        @else
                            Salvar e continuar
                        @endif
                    </button>
                </div>
            </form>
        </div>

        {{-- Upgrade modal: Free user tried to add 2nd income source --}}
        <div
            x-show="showUpgradeModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="showUpgradeModal = false"
        >
            <div
                class="absolute inset-0 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-sm"
                @click="showUpgradeModal = false"
            ></div>
            <div
                class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden"
                @click.stop
            >
                <div class="p-6 md:p-8">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center mb-4">
                        <x-icon name="crown" style="solid" size="2xl" class="text-amber-600 dark:text-amber-400" />
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                        Recurso Premium
                    </h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        Acompanhar várias fontes de receita separadamente (salário, bônus, pensão, vale-refeição) é um benefício Vertex PRO. No plano gratuito você pode cadastrar uma única receita total.
                    </p>
                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <a
                            href="{{ route('user.subscription.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold transition-colors shadow-lg shadow-amber-500/25"
                        >
                            <x-icon name="crown" style="solid" />
                            Conhecer Vertex PRO
                        </a>
                        <button
                            type="button"
                            @click="showUpgradeModal = false"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-medium transition-colors"
                        >
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
