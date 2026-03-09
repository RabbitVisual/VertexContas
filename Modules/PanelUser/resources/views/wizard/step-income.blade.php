@php
    $existingIncomes = $existingIncomes ?? [];
    $isPro = $isPro ?? false;
    $oldIncomes = old('incomes', []);
    $initialRows = !empty($oldIncomes)
        ? collect($oldIncomes)->map(fn ($i) => [
            'description' => $i['description'] ?? '',
            'amount' => $i['amount'] ?? '',
            'day' => (string) ($i['day'] ?? '1'),
        ])->values()->all()
        : (!empty($existingIncomes) ? collect($existingIncomes)->map(fn ($i) => [
            'description' => $i['description'] ?? '',
            'amount' => isset($i['amount']) ? format_number((float) $i['amount'], 2) : '',
            'day' => (string) ($i['day'] ?? '1'),
        ])->values()->all() : [['description' => '', 'amount' => '', 'day' => '1']]);
@endphp
@extends('paneluser::wizard.layout')

@section('content')
{{-- x-data em aspas simples para o JSON (com aspas duplas) não fechar o atributo HTML --}}
<div class="w-full space-y-6 animate-in fade-in duration-500" x-data='{
    "rows": @json($initialRows),
    "isPro": @json($isPro),
    "add"() {
        if (!this.isPro && this.rows.length >= 1) return;
        this.rows.push({ description: "", amount: "", day: "1" });
    },
    "remove"(index) {
        if (this.rows.length <= 1) return;
        this.rows.splice(index, 1);
    },
    "parseToNumber"(value) {
        if (!value) return 0;
        const numeric = String(value).replace(/[^0-9]/g, "");
        if (!numeric) return 0;
        return parseInt(numeric, 10) / 100;
    },
    "formatBRL"(value) {
        const num = Number(value) || 0;
        return num.toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
    },
    get "mainIncome"() {
        const first = this.rows[0] || { amount: "" };
        return this.parseToNumber(first.amount || "");
    },
    get "needsAmount"() {
        return this.mainIncome * 0.5;
    },
    get "wantsAmount"() {
        return this.mainIncome * 0.3;
    },
    get "goalsAmount"() {
        return this.mainIncome * 0.2;
    }
}' x-init="rows = rows.map(r => ({ ...r, day: r.day || '1' }))">
    <div class="text-center mb-6">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Qual é a sua renda principal prevista para este mês?</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
            Vamos usar este valor como ponto de partida para desenhar a sua bússola <strong>50/30/20</strong>.
            Você não precisa fazer contas: o Vertex mostra, em tempo real, como essa renda se divide em
            <strong>Necessidades</strong>, <strong>Desejos</strong> e <strong>Metas</strong>.
        </p>
    </div>

    <div class="rounded-2xl border border-primary-200 dark:border-primary-800/50 bg-primary-50/60 dark:bg-primary-950/40 p-4 sm:p-5 mb-4">
        <p class="text-sm text-primary-900 dark:text-primary-100">
            <strong>Dica:</strong> Comece apenas com a sua renda principal. Mais tarde, em <strong>Minha Renda</strong>, você pode detalhar outras fontes com calma.
            O importante agora é dar o primeiro passo.
        </p>
    </div>

    <form action="{{ route('paneluser.wizard.income.store') }}" method="POST" class="rounded-3xl border border-gray-200 dark:border-white/5 bg-white dark:bg-gray-950 p-6 sm:p-8 shadow-sm">
        @csrf
        <div class="space-y-6">
            <template x-for="(row, index) in rows" :key="index">
                <div class="p-4 rounded-xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 space-y-4"
                     :class="index === 0 ? 'ring-2 ring-primary-500/60 ring-offset-2 ring-offset-white dark:ring-offset-gray-950' : ''">
                    @if($isPro)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                <span x-show="index === 0">Sua Renda Principal</span>
                                <span x-show="index !== 0">Fonte <span x-text="index + 1"></span></span>
                            </span>
                            <button type="button" x-show="rows.length > 1" @click="remove(index)" class="text-red-600 dark:text-red-400 hover:underline text-sm" aria-label="Remover fonte">Remover</button>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="sm:col-span-6">
                            <label :for="'desc-' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                            <input type="text" :name="'incomes[' + index + '][description]'" :id="'desc-' + index" x-model="row.description"
                                placeholder="Ex: Salário, Pensão" required
                                class="w-full rounded-lg border {{ $errors->has('incomes.0.description') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @error('incomes.0.description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-3">
                            <label :for="'amount-' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor (R$)</label>
                            <input type="text" :name="'incomes[' + index + '][amount]'" :id="'amount-' + index" x-model="row.amount"
                                placeholder="0,00" required inputmode="decimal"
                                class="w-full rounded-lg border {{ $errors->has('incomes.0.amount') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @error('incomes.0.amount')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-3">
                            <label :for="'day-' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dia</label>
                            <select :name="'incomes[' + index + '][day]'" :id="'day-' + index" x-model="row.day" required
                                class="w-full rounded-lg border {{ $errors->has('incomes.0.day') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                                @for ($d = 1; $d <= 31; $d++)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endfor
                            </select>
                            @error('incomes.0.day')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </template>
            @if($isPro)
                <div>
                    <button type="button" @click="add()" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                        <x-icon name="plus" style="solid" class="w-4 h-4" />
                        Adicionar outra fonte de renda
                    </button>
                </div>
            @endif
        </div>
        {{-- Visualização 50/30/20 em tempo real baseada na renda principal --}}
        <div class="mt-8 rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/70 p-5 sm:p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-gray-500 dark:text-gray-400">
                        Sua bússola 50/30/20
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-200 mt-1 max-w-xl">
                        Assim que você preenche sua renda principal, o Vertex mostra quanto, idealmente, iria para
                        <strong>Necessidades</strong>, <strong>Desejos</strong> e <strong>Metas</strong>. Não é regra rígida — é um ponto de partida.
                    </p>
                </div>
                <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                    <span class="block">Renda principal considerada:</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100" x-text="formatBRL(mainIncome)"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 p-4 space-y-2">
                    <p class="text-xs font-semibold text-emerald-800 dark:text-emerald-200">Necessidades · 50%</p>
                    <p class="text-lg font-black text-emerald-900 dark:text-emerald-100" x-text="formatBRL(needsAmount)"></p>
                    <p class="text-[11px] text-emerald-900/80 dark:text-emerald-100/80">
                        Moradia, alimentação, contas básicas e transporte. Quando este bloco cabe em metade da renda, sobra espaço para respirar.
                    </p>
                </div>
                <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 p-4 space-y-2">
                    <p class="text-xs font-semibold text-amber-800 dark:text-amber-200">Desejos · 30%</p>
                    <p class="text-lg font-black text-amber-900 dark:text-amber-100" x-text="formatBRL(wantsAmount)"></p>
                    <p class="text-[11px] text-amber-900/80 dark:text-amber-100/80">
                        Lazer, presentes e pequenos prazeres do dia a dia. A ideia não é cortar tudo, e sim escolher com mais intenção.
                    </p>
                </div>
                <div class="rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700 p-4 space-y-2">
                    <p class="text-xs font-semibold text-indigo-800 dark:text-indigo-200">Metas e Futuro · 20%</p>
                    <p class="text-lg font-black text-indigo-900 dark:text-indigo-100" x-text="formatBRL(goalsAmount)"></p>
                    <p class="text-[11px] text-indigo-900/80 dark:text-indigo-100/80">
                        Reserva de emergência e objetivos importantes. Mesmo um valor pequeno, mantido mês a mês, faz muita diferença lá na frente.
                    </p>
                </div>
            </div>
            <p class="text-[11px] text-gray-600 dark:text-gray-300">
                Viu? É assim que o Vertex vai organizar sua vida: transformando um número em três blocos claros e cuidando, com você, de um passo de cada vez.
            </p>
        </div>
        @if($errors->any() && !$errors->has('incomes.0.description') && !$errors->has('incomes.0.amount') && !$errors->has('incomes.0.day'))
            <div class="mt-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mt-6 flex flex-wrap gap-4">
            <a href="{{ route('paneluser.wizard.show', ['step' => 1]) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Próximo
                <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
            </button>
        </div>
        @if(isset($isPro) && !$isPro && Route::has('user.subscription.index'))
            @php $returnStep1 = route('paneluser.wizard.show', ['step' => 1]); @endphp
            <p class="mt-4 text-xs text-gray-400 dark:text-gray-500 text-center">
                Várias fontes de renda? <a href="{{ route('user.subscription.index', ['return' => $returnStep1]) }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ plan_pro_name() }}</a> permite cadastrar cada uma separadamente. Você volta aqui após assinar.
            </p>
        @endif
    </form>
</div>
@endsection
