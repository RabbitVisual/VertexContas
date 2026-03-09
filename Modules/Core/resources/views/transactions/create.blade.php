@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Nova Transação'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none" data-tour="transactions-intro">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-slate-600/5 dark:bg-slate-600/10 rounded-full blur-[100px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4">
                    <a href="{{ route('core.transactions.index') }}" class="hover:underline">Extrato</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Nova transação</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Registrar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Receita ou Despesa</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Cada lançamento atualiza o saldo da conta escolhida. Sua capacidade mensal vem do <a href="{{ route('core.income.index') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">planejamento</a>.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if(!empty($pageTourId) && count($pageTourSteps ?? []) > 0)
                    <x-core::tour-guide :tour-id="$pageTourId" label="Ver tour desta página" />
                @endif
                <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">Contas ativas</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white leading-tight">{{ $accounts->count() }}</p>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    @if($accounts->isNotEmpty())
    @php
        $planningByCategory = $recurringTransactions->groupBy('category_id')->map(fn($g) => (float) $g->sum('amount'))->toArray();
        $categoryIdsWithBudget = $categoryIdsWithBudget ?? [];
    @endphp
    <script type="application/json" id="planning-data-create">@json($planningByCategory)</script>
    <script type="application/json" id="budgets-data-create">@json($budgetsByCategory ?? [])</script>

    @php
        $defaultAccountId = old('account_id', $defaultAccount->id ?? null);
        $defaultCategoryId = old('category_id', ($type ?? 'expense') === 'income' ? ($defaultCategoryIncome->id ?? null) : ($defaultCategoryExpense->id ?? null));
    @endphp
    {{-- Formulário frictionless: 4 campos visíveis + Avançado --}}
    <form action="{{ route('core.transactions.store') }}" method="POST" class="space-y-6"
          x-data="{
              type: '{{ $type ?? 'expense' }}',
              amount: '',
              isRecurring: false,
              categoryId: '{{ $defaultCategoryId }}',
              advancedOpen: false,
              defaultCategoryIncome: '{{ $defaultCategoryIncome->id ?? '' }}',
              defaultCategoryExpense: '{{ $defaultCategoryExpense->id ?? '' }}',
              setType(t) {
                  this.type = t;
                  this.categoryId = t === 'income' ? this.defaultCategoryIncome : this.defaultCategoryExpense;
              },
              formatCurrency() {
                  var value = String(this.amount || '').replace(/\D/g, '');
                  if (value === '') { this.amount = ''; return; }
                  value = (parseInt(value) / 100).toFixed(2);
                  this.amount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
              }
          }"
          x-init="if (type === 'income') categoryId = defaultCategoryIncome; else categoryId = defaultCategoryExpense;">
        @csrf

        <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 space-y-6">
                {{-- 1. Toggle Entrou / Saiu (grande) --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">O que entrou ou saiu?</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="setType('income')"
                                :class="type === 'income' ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg' : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400'"
                                class="flex items-center justify-center gap-3 p-5 rounded-2xl border-2 transition-all font-bold text-base">
                            <x-icon name="arrow-up" style="solid" class="w-6 h-6" />
                            Entrou dinheiro
                        </button>
                        <button type="button" @click="setType('expense')"
                                :class="type === 'expense' ? 'bg-rose-600 border-rose-600 text-white shadow-lg' : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400'"
                                class="flex items-center justify-center gap-3 p-5 rounded-2xl border-2 transition-all font-bold text-base">
                            <x-icon name="arrow-down" style="solid" class="w-6 h-6" />
                            Saiu dinheiro
                        </button>
                    </div>
                    <input type="hidden" name="type" :value="type">
                </div>

                {{-- 2. Valor --}}
                <div>
                    <label for="amount" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Quanto?</label>
                    <div class="relative max-w-xs">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-lg font-bold">R$</span>
                        <input type="text" id="amount" x-model="amount" @input="formatCurrency()" placeholder="0,00" required
                               class="w-full pl-12 pr-4 py-4 text-2xl font-black rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none text-gray-900 dark:text-white tabular-nums">
                        <input type="hidden" name="amount" :value="(typeof amount === 'string' ? amount : '').replace(/\./g, '').replace(',', '.')">
                    </div>
                    @error('amount')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>

                {{-- 3. Título (descrição) --}}
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Título</label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Ex: Padaria" required maxlength="500"
                           class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-primary-500 font-medium text-gray-900 dark:text-white placeholder-gray-400">
                    @error('description')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>

                {{-- 4. Data --}}
                <div>
                    <label for="date" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Data</label>
                    <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-primary-500 font-medium text-gray-900 dark:text-white">
                    @error('date')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>

                {{-- Categoria enviada sempre (Alpine); conta e status vêm do select/hidden em Avançado --}}
                <input type="hidden" name="category_id" x-model="categoryId">

                {{-- Aba Avançado --}}
                <div class="pt-4 border-t border-gray-200 dark:border-white/10">
                    <button type="button" @click="advancedOpen = !advancedOpen" class="flex items-center gap-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <span class="inline-block transition-transform" :class="advancedOpen ? 'rotate-180' : ''"><x-icon name="chevron-down" style="solid" class="w-4 h-4" /></span>
                        Avançado (conta, categoria, status)
                    </button>
                    <div x-show="advancedOpen" x-collapse class="space-y-4 mt-4">
                        @if($accounts->count() > 1)
                        <div>
                            <label for="account_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Conta</label>
                            <select name="account_id" id="account_id" class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-white/10 focus:border-primary-500 font-medium text-gray-800 dark:text-gray-200" required>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ $defaultAccountId == $account->id ? 'selected' : '' }}>{{ $account->name }} — {{ format_currency($account->balance) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="account_id" value="{{ $defaultAccountId }}">
                        @endif
                        <div>
                            <label for="category_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Categoria</label>
                            <select id="category_id" x-model="categoryId" class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-white/10 focus:border-primary-500 font-medium text-gray-800 dark:text-gray-200" required>
                                <optgroup label="Receitas">
                                    @foreach($categories->where('type', 'income') as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Despesas">
                                    @foreach($categories->where('type', 'expense') as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" id="status" class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-white/10 focus:border-primary-500 font-medium text-gray-800 dark:text-gray-200">
                                <option value="completed" {{ old('status', 'completed') == 'completed' ? 'selected' : '' }}>Concluída</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            </select>
                        </div>
                        @if(isset($activeGoals) && $activeGoals->isNotEmpty())
                        <div x-show="type === 'expense'">
                            <label for="goal_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Vincular à meta (opcional)</label>
                            <select name="goal_id" id="goal_id" class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-white/10 focus:border-primary-500 font-medium text-gray-800 dark:text-gray-200">
                                <option value="">Nenhuma</option>
                                @foreach($activeGoals as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if($isPro)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm">Repetir todo mês</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ plan_pro_name() }}</p>
                            </div>
                            <input type="checkbox" name="is_recurring" value="1" x-model="isRecurring" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500/20">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-6 sm:p-8 py-5 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-white/5 flex flex-wrap items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-primary-500/20">
                    <x-icon name="check" style="solid" class="w-5 h-5" />
                    Registrar
                </button>
                <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center gap-2 px-5 py-3 text-gray-600 dark:text-gray-400 font-medium hover:underline">
                    Voltar ao extrato
                </a>
            </div>
        </div>
    </form>
    @else
    {{-- CTA: Sem contas --}}
    <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm p-8 sm:p-12">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[80px]"></div>
        <div class="relative z-10 flex flex-col items-center text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6">
                <x-icon name="building-columns" style="duotone" class="w-8 h-8" />
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Você ainda não tem nenhuma conta</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-8">Para registrar receitas e despesas, você precisa de pelo menos uma conta. Crie sua conta (ex: Conta corrente, Carteira) para começar.</p>
            <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                <x-icon name="plus" style="solid" class="w-5 h-5" />
                Criar minha primeira conta
            </a>
            <a href="{{ route('core.transactions.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar ao extrato
            </a>
        </div>
    </div>
    @endif

    {{-- Dica: Como funciona no Vertex Contas --}}
    <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">Como funciona no Vertex Contas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Categorizar cada lançamento ajuda a ver para onde vai seu dinheiro. Se vincular uma despesa a uma meta, o valor entra no progresso da meta. Orçamentos acompanham o gasto por categoria. Sua <strong>capacidade mensal</strong> vem do planejamento em <a href="{{ route('core.income.index') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">Minha Renda</a>. @if($isPro) Como {{ plan_pro_name() }}, você pode marcar &quot;Repetir&quot; para agendar o mesmo lançamento todo mês. @endif</p>
            </div>
        </div>
    </div>
</div>

@if(!empty($pageTourId) && !empty($pageTourSteps))
@push('scripts')
<script>
(function() {
    var tourId = @json($pageTourId);
    var steps = @json($pageTourSteps);
    function register() {
        if (window.registerVertexTourSteps && steps && steps.length) {
            window.registerVertexTourSteps(tourId, steps);
            return;
        }
        setTimeout(register, 50);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', register);
    } else {
        register();
    }
})();
</script>
@endpush
@endif
</x-paneluser::layouts.master>
