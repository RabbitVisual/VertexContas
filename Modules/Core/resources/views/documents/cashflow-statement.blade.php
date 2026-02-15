@extends('core::documents._layout')

@section('documentTitle', 'Fluxo de Caixa')
@section('documentRightValue', $periodLabel ?? 'Últimos ' . ($months ?? 6) . ' meses')

@section('content')
<table>
    <thead>
        <tr class="heading-row">
            <th>Mês</th>
            <th class="text-right">Receitas</th>
            <th class="text-right">Despesas</th>
            <th class="text-right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cashFlow ?? [] as $item)
        <tr class="item-row">
            <td>{{ $item['month'] ?? '—' }}</td>
            <td class="text-right">{{ format_currency($item['income'] ?? 0) }}</td>
            <td class="text-right">{{ format_currency($item['expense'] ?? 0) }}</td>
            <td class="text-right" style="{{ ($item['balance'] ?? 0) >= 0 ? 'color: #059669;' : 'color: #dc2626;' }}">{{ format_currency($item['balance'] ?? 0) }}</td>
        </tr>
        @endforeach
        @if(($cashFlow ?? collect())->isNotEmpty())
        <tr class="total-row">
            <td>TOTAL</td>
            <td class="text-right">{{ format_currency(collect($cashFlow)->sum('income')) }}</td>
            <td class="text-right">{{ format_currency(collect($cashFlow)->sum('expense')) }}</td>
            <td class="text-right">{{ format_currency(collect($cashFlow)->sum('balance')) }}</td>
        </tr>
        @endif
    </tbody>
</table>

@if(($cashFlowByAccount ?? collect())->isNotEmpty())
<h3 class="section-title">Por Conta (Fonte)</h3>
<table>
    <thead>
        <tr class="heading-row">
            <th>Mês</th>
            <th>Conta</th>
            <th class="text-right">Receitas</th>
            <th class="text-right">Despesas</th>
            <th class="text-right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cashFlowByAccount as $row)
        <tr class="item-row">
            <td>{{ $row['month_name'] }}</td>
            <td>{{ $row['account_name'] }}</td>
            <td class="text-right">{{ format_currency($row['income']) }}</td>
            <td class="text-right">{{ format_currency($row['expense']) }}</td>
            <td class="text-right" style="{{ $row['balance'] >= 0 ? 'color: #059669;' : 'color: #dc2626;' }}">{{ format_currency($row['balance']) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(($topCategories ?? collect())->isNotEmpty())
<h3 class="section-title">Top Categorias de Despesa</h3>
<table>
    <thead>
        <tr class="heading-row">
            <th>Categoria</th>
            <th style="text-align: center;">Transações</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @php $totalExp = $topCategories->sum('total'); @endphp
        @foreach($topCategories as $row)
        <tr class="item-row">
            <td>{{ $row['category'] }}</td>
            <td style="text-align: center;">{{ $row['count'] }}</td>
            <td class="text-right">{{ format_currency($row['total']) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
