@php
    $isPro = auth()->user()?->isPro() ?? false;
    $prepareRows = function ($items) {
        return collect($items)->map(fn ($i) => [
            'description' => $i['description'] ?? '',
            'amount' => isset($i['amount']) ? number_format((float) $i['amount'], 2, ',', '.') : '',
            'day' => (string) ($i['day'] ?? '1'),
            'account_id' => $i['account_id'] ?? '',
            'category_id' => $i['category_id'] ?? '',
        ])->values()->all();
    };
    $initialIncomes = !empty($existingIncomes) ? $prepareRows($existingIncomes) : [['description' => '', 'amount' => '', 'day' => '1', 'account_id' => '', 'category_id' => '']];
    $initialExpenses = !empty($existingExpenses) ? $prepareRows($existingExpenses) : [];
    $dashboardRoute = ($isPro && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index');
@endphp

<x-paneluser::layouts.master :title="'Minha Renda'">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('planningForm', () => ({
                incomes: @json($initialIncomes),
                expenses: @json($initialExpenses),
                isPro: @json($isPro),
                showUpgradeModal: false,
                addIncome() {
                    if (this.incomes.length >= 1 && !this.isPro) {
                        this.showUpgradeModal = true;
                        return;
                    }
                    this.incomes.push({ description: '', amount: '', day: '1', account_id: '', category_id: '' });
                },
                removeIncome(index) {
                    if (this.incomes.length <= 1) return;
                    this.incomes.splice(index, 1);
                },
                addExpense() {
                    if (!this.isPro) {
                        this.showUpgradeModal = true;
                        return;
                    }
                    this.expenses.push({ description: '', amount: '', day: '1', account_id: '', category_id: '' });
                },
                removeExpense(index) {
                    this.expenses.splice(index, 1);
                }
            }));
        });
    </script>

    <div x-data="planningForm()" class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                        <span>Planejamento</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">Minha Renda</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Minha <br><span class="text-transparent bg-clip-text bg-linear-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Renda</span></h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Receitas e despesas fixas mensais. Essa é a base da sua capacidade de gasto no Vertex Contas.</p>
                </div>
                <a href="{{ $dashboardRoute }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                    <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                    Voltar
                </a>
            </div>
        </div>

        {{-- Dica: Como funciona --}}
        <div class="rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm">
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1">Como funciona no Vertex Contas</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Minha Renda define seu <strong>planejamento mensal</strong>: o que você espera receber e o que já compromete (despesas fixas). A <strong>capacidade mensal</strong> é receitas menos despesas fixas. As <strong>transações</strong> no extrato são os lançamentos reais nas contas. Aqui você só planeja; no Extrato você registra o que de fato entrou e saiu.</p>
                </div>
            </div>
        </div>

        @if($inspectionReadOnly ?? false)
            <div class="rounded-3xl border-2 border-dashed border-amber-200 dark:border-amber-800/50 bg-amber-50/50 dark:bg-amber-900/10 p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <x-icon name="eye" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Modo inspeção ativo</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Esta página não pode ser alterada durante a sessão de suporte. Apenas visualização.</p>
                    </div>
                </div>
            </div>
        @else
        <form action="{{ route('core.income.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Receitas --}}
            <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex flex-wrap items-center justify-between gap-4 bg-emerald-500/5 dark:bg-emerald-500/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <x-icon name="arrow-trend-up" style="duotone" class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-900 dark:text-white">Receitas recorrentes</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Salário, bônus, aluguéis — o que entra todo mês</p>
                        </div>
                    </div>
                    <button type="button" @click="addIncome()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider transition-colors">
                        <x-icon name="plus" style="solid" class="w-4 h-4" />
                        Adicionar receita
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <template x-for="(row, index) in incomes" :key="'inc-'+index">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-950/50 border border-gray-200 dark:border-white/5">
                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Descrição</label>
                                <input type="text" :name="'incomes['+index+'][description]'" x-model="row.description" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 focus:border-emerald-500 outline-none" placeholder="Ex: Salário" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Valor (R$)</label>
                                <input type="text" :name="'incomes['+index+'][amount]'" x-model="row.amount" x-mask="'money'" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 focus:border-emerald-500 outline-none tabular-nums" placeholder="0,00" required>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Dia</label>
                                <input type="number" :name="'incomes['+index+'][day]'" x-model="row.day" min="1" max="31" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-2 py-2.5 focus:border-emerald-500 outline-none tabular-nums" placeholder="1" title="Dia do mês (1-31)">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Conta</label>
                                <select :name="'incomes['+index+'][account_id]'" x-model="row.account_id" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 outline-none focus:border-emerald-500">
                                    <option value="">Planejamento</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Categoria</label>
                                <select :name="'incomes['+index+'][category_id]'" x-model="row.category_id" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 outline-none focus:border-emerald-500">
                                    <option value="">Sem categoria</option>
                                    @foreach($categories->where('type', 'income') as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-1 flex items-end justify-center pb-1">
                                <button type="button" @click="removeIncome(index)" x-show="incomes.length > 1" class="p-2 text-gray-400 hover:text-rose-500 transition-colors" aria-label="Remover">
                                    <x-icon name="trash-can" style="solid" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Despesas fixas: só Pro --}}
            @if($isPro)
                <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex flex-wrap items-center justify-between gap-4 bg-rose-500/5 dark:bg-rose-500/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-600/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                                <x-icon name="arrow-trend-down" style="duotone" class="w-5 h-5" />
                            </div>
                            <div>
                                <h2 class="font-bold text-gray-900 dark:text-white">Despesas fixas</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Aluguel, internet, assinaturas — o que sai todo mês</p>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">Vertex Pro</span>
                        </div>
                        <button type="button" @click="addExpense()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider transition-colors">
                            <x-icon name="plus" style="solid" class="w-4 h-4" />
                            Adicionar despesa
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <template x-if="expenses.length === 0">
                            <div class="text-center py-8 rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/10">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma despesa fixa. Clique em &quot;Adicionar despesa&quot; para planejar.</p>
                            </div>
                        </template>
                        <template x-for="(row, index) in expenses" :key="'exp-'+index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-950/50 border border-gray-200 dark:border-white/5">
                                <div class="md:col-span-3">
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Descrição</label>
                                    <input type="text" :name="'expenses['+index+'][description]'" x-model="row.description" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 focus:border-rose-500 outline-none" placeholder="Ex: Aluguel" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Valor (R$)</label>
                                    <input type="text" :name="'expenses['+index+'][amount]'" x-model="row.amount" x-mask="'money'" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 focus:border-rose-500 outline-none tabular-nums" placeholder="0,00" required>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Dia</label>
                                    <input type="number" :name="'expenses['+index+'][day]'" x-model="row.day" min="1" max="31" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-2 py-2.5 focus:border-rose-500 outline-none tabular-nums" placeholder="1">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Conta</label>
                                    <select :name="'expenses['+index+'][account_id]'" x-model="row.account_id" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 outline-none focus:border-rose-500">
                                        <option value="">Planejamento</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Categoria</label>
                                    <select :name="'expenses['+index+'][category_id]'" x-model="row.category_id" class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-sm font-medium px-3 py-2.5 outline-none focus:border-rose-500">
                                        <option value="">Sem categoria</option>
                                        @foreach($categories->where('type', 'expense') as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-1 flex items-end justify-center pb-1">
                                    <button type="button" @click="removeExpense(index)" class="p-2 text-gray-400 hover:text-rose-500 transition-colors" aria-label="Remover">
                                        <x-icon name="trash-can" style="solid" class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            @else
                <div class="rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-gray-950/50 p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                            <x-icon name="lock" style="solid" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Despesas fixas (Vertex Pro)</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Planeje aluguel, contas e assinaturas para ver sua capacidade mensal real.</p>
                        </div>
                    </div>
                    <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition-colors">
                        <x-icon name="sparkles" style="duotone" class="w-4 h-4" />
                        Vertex Pro
                    </a>
                </div>
            @endif

            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ $dashboardRoute }}" class="inline-flex items-center justify-center gap-2 py-3 px-5 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                    <x-icon name="check" style="solid" class="w-5 h-5" />
                    Salvar planejamento
                </button>
            </div>
        </form>
        @endif
    </div>

    {{-- Modal: Upgrade Vertex Pro --}}
    <div x-show="showUpgradeModal"
         x-cloak
         x-transition
         @keydown.escape.window="showUpgradeModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 dark:bg-gray-950/70 backdrop-blur-sm" @click="showUpgradeModal = false"></div>
        <div class="relative w-full max-w-lg rounded-3xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 shadow-2xl overflow-hidden" @click.stop>
            <div class="p-8">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center mb-5 text-amber-600 dark:text-amber-400">
                    <x-icon name="sparkles" style="duotone" class="w-7 h-7" />
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Vertex Pro</h2>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    Múltiplas receitas, despesas fixas e vínculo com contas são recursos exclusivos para assinantes Pro.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('user.subscription.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 py-3.5 px-5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-colors">
                        <x-icon name="sparkles" style="solid" class="w-4 h-4" />
                        Ver planos
                    </a>
                    <button type="button" @click="showUpgradeModal = false" class="py-3.5 px-5 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
