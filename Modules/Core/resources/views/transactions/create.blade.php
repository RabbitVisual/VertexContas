@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

<x-paneluser::layouts.master :title="'Nova Transação'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
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

    @php
        $planningByCategory = $recurringTransactions->groupBy('category_id')->map(fn($g) => (float) $g->sum('amount'))->toArray();
    @endphp
    <script type="application/json" id="planning-data-create">@json($planningByCategory)</script>

    {{-- Formulário --}}
    <form action="{{ route('core.transactions.store') }}" method="POST" class="space-y-8"
          x-data="{
              type: '{{ $type ?? 'expense' }}',
              amount: '',
              isRecurring: false,
              categoryId: '',
              planning: {},
              init() {
                  var el = document.getElementById('planning-data-create');
                  if (el) this.planning = JSON.parse(el.textContent || '{}');
              },
              formatCurrency() {
                  var value = String(this.amount || '').replace(/\D/g, '');
                  if (value === '') { this.amount = ''; return; }
                  value = (parseInt(value) / 100).toFixed(2);
                  this.amount = value.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
              },
              recurringInfo() {
                  if (this.categoryId && this.planning[this.categoryId]) {
                      return 'Valor planejado para esta categoria: R$ ' + parseFloat(this.planning[this.categoryId]).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                  }
                  return null;
              }
          }"
          x-init="init()">
        @csrf

        <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="p-8 sm:p-10 space-y-8">
                {{-- Tipo --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4">Tipo de movimentação</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="type = 'income'"
                                :class="type === 'income' ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-white/5 text-gray-500 hover:border-emerald-500/50'"
                                class="flex items-center gap-4 p-6 rounded-2xl border-2 transition-all">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" :class="type === 'income' ? 'bg-white/20' : 'bg-emerald-500/10'">
                                <x-icon name="arrow-up" style="solid" class="w-6 h-6" />
                            </div>
                            <span class="font-bold text-sm uppercase tracking-wide">Receita</span>
                        </button>
                        <button type="button" @click="type = 'expense'"
                                :class="type === 'expense' ? 'bg-rose-600 border-rose-600 text-white shadow-lg shadow-rose-500/20' : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-white/5 text-gray-500 hover:border-rose-500/50'"
                                class="flex items-center gap-4 p-6 rounded-2xl border-2 transition-all">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" :class="type === 'expense' ? 'bg-white/20' : 'bg-rose-500/10'">
                                <x-icon name="arrow-down" style="solid" class="w-6 h-6" />
                            </div>
                            <span class="font-bold text-sm uppercase tracking-wide">Despesa</span>
                        </button>
                    </div>
                    <input type="hidden" name="type" :value="type">
                </div>

                {{-- Valor --}}
                <div>
                    <label for="amount" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Valor (R$)</label>
                    <div class="relative max-w-xs">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xl font-bold">R$</span>
                        <input type="text" id="amount" x-model="amount" @input="formatCurrency()" placeholder="0,00"
                               class="w-full pl-14 pr-5 py-4 text-2xl font-black rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none text-gray-900 dark:text-white tabular-nums" required>
                        <input type="hidden" name="amount" :value="(typeof amount === 'string' ? amount : '').replace(/\./g, '').replace(',', '.')">
                    </div>
                    @error('amount')<p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Conta e Categoria --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="account_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3" x-text="type === 'income' ? 'Conta (onde entra o valor)' : 'Conta (de onde sai o valor)'"></label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                            </div>
                            <select name="account_id" id="account_id" class="w-full pl-12 pr-10 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium text-gray-800 dark:text-gray-200 appearance-none" required>
                                <option value="">Selecione a conta</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }} — R$ {{ number_format($account->balance, 2, ',', '.') }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="chevron-down" style="solid" class="w-4 h-4" />
                            </div>
                        </div>
                        @error('account_id')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Categoria</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none z-10">
                                <x-icon name="tag" style="duotone" class="w-5 h-5" />
                            </div>
                            <select name="category_id" id="category_id" x-model="categoryId" class="w-full pl-12 pr-10 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium text-gray-800 dark:text-gray-200 appearance-none" required>
                                <option value="">Selecione</option>
                                <template x-if="type === 'income'">
                                    <optgroup label="Receitas">
                                        @foreach($categories->where('type', 'income') as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </template>
                                <template x-if="type === 'expense'">
                                    <optgroup label="Despesas">
                                        @foreach($categories->where('type', 'expense') as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </template>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="chevron-down" style="solid" class="w-4 h-4" />
                            </div>
                        </div>
                        <p x-show="recurringInfo()" x-text="recurringInfo()" class="mt-2 text-xs text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                            <x-icon name="lightbulb" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                        </p>
                        @error('category_id')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Data, Status, Descrição --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Data</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <x-icon name="calendar" style="duotone" class="w-5 h-5" />
                            </div>
                            <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="w-full pl-12 pr-4 py-3 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium" required>
                        </div>
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-3 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium">
                            <option value="completed" {{ old('status', 'completed') == 'completed' ? 'selected' : '' }}>Concluída</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Descrição (opcional)</label>
                    <textarea name="description" id="description" rows="2" class="w-full px-4 py-3 rounded-2xl bg-gray-50 dark:bg-gray-950 border-2 border-gray-200 dark:border-white/10 focus:border-emerald-500 font-medium placeholder-gray-400" placeholder="Ex: Pagamento mercado, Salário">{{ old('description') }}</textarea>
                </div>

                @if($isPro)
                    {{-- Vertex Pro: Repetir (visível só para Pro) --}}
                    <div class="p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <x-icon name="repeat" style="duotone" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">Repetir todo mês</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Vertex Pro: esta transação será criada automaticamente todo mês na mesma data.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_recurring" value="1" x-model="isRecurring" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 rounded-full peer-checked:bg-emerald-500 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full border border-gray-200 shadow transition-transform peer-checked:translate-x-5 pointer-events-none"></div>
                            </label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-8 sm:p-10 py-6 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-white/5 flex flex-wrap items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/20">
                    <x-icon name="check" style="solid" class="w-5 h-5" />
                    Registrar transação
                </button>
                <a href="{{ route('core.transactions.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-medium rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                    Voltar ao extrato
                </a>
            </div>
        </div>
    </form>

    {{-- Dica: Como funciona no Vertex Contas --}}
    <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">Como funciona no Vertex Contas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Cada transação altera o <strong>saldo da conta</strong> que você escolher: receitas somam e despesas subtraem. O valor da sua <strong>capacidade mensal</strong> vem do planejamento (Minha Renda), não da soma das transações. Use categorias para organizar e, se for Vertex Pro, marque &quot;Repetir&quot; para agendar o mesmo lançamento todo mês.</p>
            </div>
        </div>
    </div>
</div>
</x-paneluser::layouts.master>
