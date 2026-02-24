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
    }
}' x-init="rows = rows.map(r => ({ ...r, day: r.day || '1' }))">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Sua renda</h1>
        <p class="text-gray-600 dark:text-gray-400">Assim calculamos sua <strong>capacidade mensal</strong> (o que sobra para gastar após receitas e despesas fixas).</p>
    </div>

    <div class="rounded-xl border border-primary-200 dark:border-primary-800/50 bg-primary-50/50 dark:bg-primary-950/30 p-4 mb-6">
        <p class="text-sm text-primary-800 dark:text-primary-200">
            <strong>Dica:</strong> Esta informação compõe sua capacidade mensal no dashboard. Você pode editar ou adicionar mais fontes depois em <strong>Minha Renda</strong>.
        </p>
    </div>

    <form action="{{ route('paneluser.wizard.income.store') }}" method="POST" class="rounded-3xl border border-gray-200 dark:border-white/5 bg-white dark:bg-gray-950 p-6 sm:p-8 shadow-sm">
        @csrf
        <div class="space-y-6">
            <template x-for="(row, index) in rows" :key="index">
                <div class="p-4 rounded-xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 space-y-4">
                    @if($isPro)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Fonte <span x-text="index + 1"></span></span>
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
            <a href="{{ route('paneluser.wizard.show', ['step' => 0]) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition-colors">
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
